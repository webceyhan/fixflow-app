import type {
    CustomerStatus,
    CustomerType,
    DeviceStatus,
    DeviceType,
    OrderStatus,
    Priority,
    TaskStatus,
    TaskType,
    TicketStatus,
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
    assigned_tickets?: Ticket[];
    assigned_tasks?: Task[];
    assigned_orders?: Order[];
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
    devices?: Device[];
    tickets?: Ticket[];
}

export interface Device extends Model {
    customer_id: number;
    model: string;
    brand: string;
    color?: string;
    imei?: string;
    serial_number?: string;
    passcode?: string;
    purchase_date?: string;
    warranty_expire_date?: string;
    note?: string;
    type: DeviceType;
    status: DeviceStatus;
    customer?: Customer;
    tickets?: Ticket[];
}

export interface Ticket extends Model {
    device_id: number;
    assignee_id?: number;
    title: string;
    note?: string;
    priority: Priority;
    status: TicketStatus;
    due_date?: string;
    device?: Device;
    customer?: Customer;
    assignee?: User;
    tasks?: Task[];
    orders?: Order[];
}

export interface Task extends Model {
    ticket_id: number;
    assignee_id?: number;
    cost: number;
    is_billable: boolean;
    note?: string;
    priority: Priority;
    type: TaskType;
    status: TaskStatus;
    approved_at?: string;
    ticket?: Ticket;
    assignee?: User;
}

export interface Order extends Model {
    ticket_id: number;
    assignee_id?: number;
    name: string;
    url?: string;
    supplier?: string;
    quantity: number;
    cost: number;
    is_billable: boolean;
    note?: string;
    due_date?: string;
    status: OrderStatus;
    approved_at?: string;
    ticket?: Ticket;
    assignee?: User;
}
