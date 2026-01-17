export * from './enums';
export * from './models';
export * from './utils';

export interface Auth {
    user: User;
}

export type AppPageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    name: string;
    auth: Auth;
    sidebarOpen: boolean;
    [key: string]: unknown;
};

export type Appearance = 'light' | 'dark' | 'system';

export type StateColor = 'neutral' | 'success' | 'error' | 'warning' | 'info';

export type SortDirection = 'asc' | 'desc' | 'none';
