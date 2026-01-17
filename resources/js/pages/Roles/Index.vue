<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import { ref } from 'vue'
import type { Role } from '@/types/role'
import { Button } from '@/components/ui/button'
import { useCan } from '@/composables/useCan'
import roles from '@/routes/roles'
import RoleForm from './RoleForm.vue'
import AppLayout from '@/layouts/AppLayout.vue'
import { type BreadcrumbItem } from '@/types'

const props = defineProps<{
    roles: Role[]
    permissions: { id: number; name: string }[]
}>()

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Roles',
        href: roles.index().url,
    },
]

const can = useCan()

const showCreate = ref(false)
const editingRole = ref<Role | null>(null)

const deleteRole = (id: number) => {
    if (!confirm('Delete role?')) return

    router.delete(roles.destroy(id).url, {
        preserveScroll: true,
    })
}
</script>

<template>

    <Head title="Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6 space-y-4">
            <div class="flex justify-between items-center">
                <h1 class="text-2xl font-bold">Roles</h1>

                <Button v-if="can.roles.create" @click="showCreate = true">
                    Create Role
                </Button>
            </div>

            <table class="w-full border rounded-md">
                <thead>
                    <tr class="bg-muted">
                        <th class="p-2 text-left">Role</th>
                        <th class="p-2 text-left">Permissions</th>
                        <th class="p-2 text-left">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="role in props.roles" :key="role.id" class="border-t">
                        <td class="p-2">{{ role.name }}</td>

                        <td class="p-2">
                            {{role.permissions.map(p => p.name).join(', ')}}
                        </td>

                        <td class="p-2 flex gap-2">
                            <Button v-if="can.roles.edit" size="sm" @click="editingRole = role">
                                Edit
                            </Button>

                            <Button v-if="can.roles.delete" size="sm" variant="destructive"
                                @click="deleteRole(role.id)">
                                Delete
                            </Button>
                        </td>
                    </tr>

                    <tr v-if="props.roles.length === 0">
                        <td colspan="3" class="p-4 text-center text-muted-foreground">
                            No roles found
                        </td>
                    </tr>
                </tbody>
            </table>

            <RoleForm v-if="showCreate" :permissions="permissions" @close="showCreate = false" />

            <RoleForm v-if="editingRole" :role="editingRole" :permissions="permissions" @close="editingRole = null" />
        </div>
    </AppLayout>
</template>
