<script setup lang="ts">
import { watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import type { User, Role } from '@/types/user'
import users from '@/routes/users'
import { Button } from '@/components/ui/button'
import { useCan } from '@/composables/useCan'


const can = useCan()

const props = defineProps<{
    user: User | null
    roles: Role[]
    show: boolean
}>()

const emit = defineEmits<{ (e: 'close'): void }>()

const form = useForm({
    name: '',
    email: '',
    roles: [] as number[],
})

watch(
    () => props.user,
    (user) => {
        if (!user) return
        form.name = user.name
        form.email = user.email
        form.roles = user.roles.map(r => r.id)
    },
    { immediate: true }
)

const submit = () => {
    if (!props.user) return

    form.put(users.update(props.user.id).url, {
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
            emit('close')
        },
    })
}
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    >
        <div class="bg-background rounded-lg w-full max-w-md p-6">
            <h2 class="text-xl font-semibold mb-4">Edit User</h2>

            <form @submit.prevent="submit" class="space-y-4">
                <input v-model="form.name" class="w-full border p-2" />
                <input v-model="form.email" class="w-full border p-2" />

                <div v-if="can.roles.edit">
                    <label class="text-sm font-medium">Roles</label>
                    <div class="space-y-2 mt-2">
                        <label
                            v-for="role in roles"
                            :key="role.id"
                            class="flex items-center gap-2 text-sm"
                        >
                            <input
                                type="checkbox"
                                :value="role.id"
                                v-model="form.roles"
                            />
                            {{ role.name }}
                        </label>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <Button variant="outline" type="button" @click="emit('close')">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="form.processing">
                        Update
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
