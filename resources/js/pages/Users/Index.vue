<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3'
import { ref } from 'vue'
import type { User } from '@/types/user'

import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'

/* Wayfinder routes */
import users from '@/routes/users'

/* UI */
import { Button } from '@/components/ui/button'

/* Modals */
import CreateUserModal from './CreateUserModal.vue'
import EditUserModal from './EditUserModal.vue'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Users',
        href: users.index().url,
    },
]

/* -------------------------
   Props
-------------------------- */
const props = defineProps<{
    users: {
        data: User[]
    }
}>()

/* -------------------------
   Create modal
-------------------------- */
const showCreateModal = ref(false)

/* -------------------------
   Edit modal
-------------------------- */
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

/* -------------------------
   Delete user
-------------------------- */
const deleteUser = (id: number) => {
    if (!confirm('Delete this user?')) return

    router.delete(users.destroy(id).url, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Users</h1>

                <Button @click="showCreateModal = true">
                    Create User
                </Button>
            </div>

            <!-- Users Table -->
            <table class="w-full border rounded-md">
                <thead>
                    <tr class="bg-muted">
                        <th class="p-2 text-left">Name</th>
                        <th class="p-2 text-left">Email</th>
                        <th class="p-2 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="user in props.users.data"
                        :key="user.id"
                        class="border-t"
                    >
                        <td class="p-2">{{ user.name }}</td>
                        <td class="p-2">{{ user.email }}</td>
                        <td class="p-2 flex gap-2">
                            <Button
                                size="sm"
                                @click="openEditModal(user)"
                            >
                                Edit
                            </Button>

                            <Button
                                size="sm"
                                variant="destructive"
                                @click="deleteUser(user.id)"
                            >
                                Delete
                            </Button>
                        </td>
                    </tr>

                    <tr v-if="props.users.data.length === 0">
                        <td
                            colspan="3"
                            class="p-4 text-center text-muted-foreground"
                        >
                            No users found
                        </td>
                    </tr>
                </tbody>
            </table>

            <!-- Modals -->
            <CreateUserModal
                :show="showCreateModal"
                @close="showCreateModal = false"
            />

            <EditUserModal
                :show="showEditModal"
                :user="selectedUser"
                @close="closeEditModal"
            />
        </div>
    </AppLayout>
</template>
