import { usePage } from '@inertiajs/vue3'

export function useCan() {
    return usePage().props.auth.can
}
