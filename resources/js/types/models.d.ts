export interface Model {
    id: number;
    created_at: string;
    updated_at: string;
}

export interface User extends Model {
    name: string;
    email: string;
    email_verified_at?: string;
    two_factor_confirmed_at?: string;
    notifications?: DatabaseNotification[];
}
