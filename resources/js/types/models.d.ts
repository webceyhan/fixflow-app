// Auto-generated from PHP models - DO NOT EDIT MANUALLY

import type {
    AdjustmentReason,
    AdjustmentType,
    CustomerStatus,
    CustomerType,
    DeviceStatus,
    DeviceType,
    InvoiceStatus,
    OrderStatus,
    Priority,
    TaskStatus,
    TaskType,
    TicketStatus,
    TransactionMethod,
    TransactionType,
    UserRole,
    UserStatus,
} from './enums';

export interface Model {
    id: number;
    created_at: string;
    updated_at: string;
}

export interface Adjustment extends Model {
    invoice_id: number;
    amount: number;
    is_percent: boolean;
    note?: string;
    type: AdjustmentType;
    reason: AdjustmentReason;
    invoice?: Invoice;
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

export interface Invoice extends Model {
    ticket_id: number;
    total: number;
    task_total: number;
    order_total: number;
    discount_amount: number;
    fee_amount: number;
    paid_amount: number;
    refunded_amount: number;
    due_date: string;
    note?: string;
    status: InvoiceStatus;
    ticket?: Ticket;
    transactions?: Transaction[];
    adjustments?: Adjustment[];
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
    due_date?: string;
    note?: string;
    status: OrderStatus;
    approved_at?: string;
    ticket?: Ticket;
    assignee?: User;
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
    tasks?: Task[];
    orders?: Order[];
    invoice?: Invoice;
    assignee?: User;
}

export interface Transaction extends Model {
    invoice_id: number;
    amount: number;
    note?: string;
    method: TransactionMethod;
    type: TransactionType;
    invoice?: Invoice;
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
    assigned_tickets?: Ticket[];
    assigned_tasks?: Task[];
    assigned_orders?: Order[];
    notifications?: DatabaseNotification[];
}
