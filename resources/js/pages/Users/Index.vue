<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import type { User, Role } from '@/types/user'
import users from '@/routes/users'
import AppLayout from '@/layouts/AppLayout.vue'
import { Button } from '@/components/ui/button'
import CreateUserModal from './CreateUserModal.vue'
import EditUserModal from './EditUserModal.vue'
import { useCan } from '@/composables/useCan'
import { ROLES } from '@/constants/roles'

/* Permissions */
const can = useCan()

/* Props */
const { users: usersProp, roles } = defineProps<{
    users: { data: User[] }
    roles: Role[]
}>()

/* Create modal */
const showCreateModal = ref(false)

/* Edit modal */
const showEditModal = ref(false)
const selectedUser = ref<User | null>(null)

const openEditModal = (user: User) => {
    selectedUser.value = user
    showEditModal.value = true
}

const closeEditModal = () => {
    showEditModal.value = false
    selectedUser.value = null
}

/* Delete */
const deleteUser = (id: number) => {
    if (!confirm('Delete this user?')) return

    router.delete(users.destroy(id).url, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Users" />

    <AppLayout>
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Users</h1>

                <Button
                    v-if="can.users.create"
                    @click="showCreateModal = true"
                >
                    Create User
                </Button>
            </div>

            <!-- Users Table -->
            <table class="w-full border rounded-md">
                <thead>
                    <tr class="bg-muted">
                        <th class="p-2 text-left">Name</th>
                        <th class="p-2 text-left">Email</th>
                        <th class="p-2 text-left">Role</th>
                        <th class="p-2 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="user in usersProp.data"
                        :key="user.id"
                        class="border-t"
                    >
                        <td class="p-2">{{ user.name }}</td>
                        <td class="p-2">{{ user.email }}</td>

                        <!-- Role column -->
                        <td class="p-2">
                            <span
                                v-if="user.roles.length"
                                class="text-sm bg-secondary px-2 py-1 rounded"
                            >
                                {{ user.roles.map(r => r.name).join(', ') }}
                            </span>

                            <span
                                v-else
                                class="text-muted-foreground text-sm"
                            >
                                —
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="p-2 flex gap-2">
                            <Button
                                v-if="can.users.edit"
                                size="sm"
                                :disabled="user.roles.some(r => r.name === ROLES.SUPER_ADMIN)"
                                @click="openEditModal(user)"
                            >
                                Edit
                            </Button>

                            <Button
                                v-if="can.users.delete"
                                size="sm"
                                variant="destructive"
                                @click="deleteUser(user.id)"
                            >
                                Delete
                            </Button>
                        </td>
                    </tr>

                    <tr v-if="usersProp.data.length === 0">
                        <td
                            colspan="4"
                            class="p-4 text-center text-muted-foreground"
                        >
                            No users found
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Create Modal -->
            <CreateUserModal
                :show="showCreateModal"
                @close="showCreateModal = false"
            />

            <!-- Edit Modal -->
            <EditUserModal
                :show="showEditModal"
                :user="selectedUser"
                :roles="roles"
                @close="closeEditModal"
            />
        </div>
    </AppLayout>
</template>
