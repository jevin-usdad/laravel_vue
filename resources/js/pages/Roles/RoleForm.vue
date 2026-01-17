<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import type { Role } from '@/types/role'
import { Button } from '@/components/ui/button'
import roles from '@/routes/roles'

const props = defineProps<{
    role?: Role
    permissions: { id: number; name: string }[]
}>()

const emit = defineEmits(['close'])

const form = useForm({
    name: props.role?.name ?? '',
    permissions: props.role?.permissions.map(p => p.id) ?? [],
})

const submit = () => {
    if (props.role) {
        form.put(roles.update(props.role.id).url, {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        })
    } else {
        form.post(roles.store().url, {
            preserveScroll: true,
            onSuccess: () => emit('close'),
        })
    }
}
</script>

<template>
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <form
            @submit.prevent="submit"
            class="w-96 space-y-4 rounded-md bg-white p-6"
        >
            <h2 class="text-lg font-semibold">
                {{ props.role ? 'Edit Role' : 'Create Role' }}
            </h2>

            <input
                v-model="form.name"
                class="w-full border p-2 rounded-md"
                placeholder="Role name"
            />

            <div class="space-y-2">
                <label
                    v-for="perm in permissions"
                    :key="perm.id"
                    class="flex items-center gap-2"
                >
                    <input
                        type="checkbox"
                        :value="perm.id"
                        v-model="form.permissions"
                    />
                    <span>{{ perm.name }}</span>
                </label>
            </div>

            <div class="flex justify-end gap-2">
                <Button type="button" variant="outline" @click="emit('close')">
                    Cancel
                </Button>

                <Button type="submit">
                    Save
                </Button>
            </div>
        </form>
    </div>
</template>
