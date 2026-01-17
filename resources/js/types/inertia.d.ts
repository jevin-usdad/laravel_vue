import type { PageProps as InertiaPageProps } from '@inertiajs/core'
import type { User } from './user'

export interface ModulePermissions {
    view: boolean
    create: boolean
    edit: boolean
    delete: boolean
}

export interface AuthProps {
    user: User | null
    can: {
        users: ModulePermissions
        roles: ModulePermissions
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps {
        auth: AuthProps
    }
}
