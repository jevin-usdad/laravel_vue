<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'
import users from '@/routes/users'
import { Button } from '@/components/ui/button'

defineProps<{ show: boolean }>()

const emit = defineEmits<{
    (e: 'close'): void
}>()

const form = useForm({
    name: '',
    email: '',
    password: '',
})

const submit = () => {
    form.post(users.store().url, {
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
            <h2 class="text-xl font-semibold mb-4">Create User</h2>

            <form @submit.prevent="submit" class="space-y-4">
                <div>
                    <label class="text-sm font-medium">Name</label>
                    <input v-model="form.name" class="w-full border rounded p-2" />
                    <p v-if="form.errors.name" class="text-destructive text-sm">
                        {{ form.errors.name }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Email</label>
                    <input v-model="form.email" class="w-full border rounded p-2" />
                    <p v-if="form.errors.email" class="text-destructive text-sm">
                        {{ form.errors.email }}
                    </p>
                </div>

                <div>
                    <label class="text-sm font-medium">Password</label>
                    <input
                        type="password"
                        v-model="form.password"
                        class="w-full border rounded p-2"
                    />
                    <p v-if="form.errors.password" class="text-destructive text-sm">
                        {{ form.errors.password }}
                    </p>
                </div>

                <div class="flex justify-end gap-2 pt-4">
                    <Button
                        type="button"
                        variant="outline"
                        class="cursor-pointer"
                        @click="emit('close')"
                    >
                        Cancel
                    </Button>

                    <Button
                        type="submit"
                        class="cursor-pointer"
                        :disabled="form.processing"
                    >
                        Create
                    </Button>
                </div>
            </form>
        </div>
    </div>
</template>
