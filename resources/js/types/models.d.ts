import type {
    CustomerStatus,
    CustomerType,
    UserRole,
    UserStatus,
} from './enums';

export interface Model {
    id: number;
    created_at: string;
    updated_at: string;
}

export interface User extends Model {
    name: string;
    email: string;
    phone: string;
    note?: string;
    role: UserRole;
    status: UserStatus;
    email_verified_at?: string;
    two_factor_confirmed_at?: string;
    notifications?: DatabaseNotification[];
}

export interface Customer extends Model {
    name: string;
    company?: string;
    tax_number?: string;
    email?: string;
    phone?: string;
    address?: string;
    note?: string;
    type: CustomerType;
    status: CustomerStatus;
}
