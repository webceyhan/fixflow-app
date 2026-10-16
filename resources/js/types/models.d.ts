// Auto-generated from PHP models - DO NOT EDIT MANUALLY

import type {
    AdjustmentReason,
    AdjustmentType,
    AttachmentCategory,
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
    ref: string;
    invoice?: Invoice;
}

export interface Attachment extends Model {
    attachable_type: string;
    attachable_id: number;
    name: string;
    path: string;
    mime_type: string;
    size: number;
    category: AttachmentCategory;
    attachable?: Attachment;
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
    ref: string;
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
    ref: string;
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
    ref: string;
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
    ref: string;
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
    ref: string;
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
    ref: string;
    device?: Device;
    customer?: Customer;
    tasks?: Task[];
    orders?: Order[];
    attachments?: Attachment[];
    invoice?: Invoice;
    assignee?: User;
}

export interface Transaction extends Model {
    invoice_id: number;
    amount: number;
    note?: string;
    method: TransactionMethod;
    type: TransactionType;
    ref: string;
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
    ref: string;
    assigned_tickets?: Ticket[];
    assigned_tasks?: Task[];
    assigned_orders?: Order[];
    notifications?: DatabaseNotification[];
}
