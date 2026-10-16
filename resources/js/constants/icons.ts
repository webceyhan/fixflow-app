import {
    Activity,
    ArrowRightLeft,
    Banknote,
    BanknoteArrowDown,
    BanknoteArrowUp,
    BrushCleaning,
    Building2,
    ChevronsDown,
    ChevronsRight,
    ChevronsUp,
    CircleGauge,
    ClipboardList,
    Computer,
    CreditCard,
    FileText,
    FlaskConical,
    Globe,
    HandCoins,
    HardDrive,
    HardDriveDownload,
    HardDriveUpload,
    IdCard,
    Image,
    IterationCw,
    Laptop,
    ShieldCheck,
    ShieldUser,
    Smartphone,
    Tablet,
    UserCog,
    UserPen,
    Watch,
    Wrench,
} from 'lucide-vue-next';

import type {
    AdjustmentType,
    AttachmentCategory,
    CustomerType,
    DeviceType,
    Icon,
    Priority,
    TaskType,
    TransactionMethod,
    TransactionType,
    UserRole,
} from '@/types';

export const PRIORITY_ICON_MAP: Record<Priority, Icon> = {
    low: ChevronsDown,
    medium: ChevronsRight,
    high: ChevronsUp,
};

export const USER_ROLE_ICON_MAP: Record<UserRole, Icon> = {
    admin: ShieldUser,
    manager: UserPen,
    technician: UserCog,
};

export const CUSTOMER_TYPE_ICON_MAP: Record<CustomerType, Icon> = {
    individual: IdCard,
    business: Building2,
};

export const DEVICE_TYPE_ICON_MAP: Record<DeviceType, Icon> = {
    phone: Smartphone,
    tablet: Tablet,
    laptop: Laptop,
    desktop: Computer,
    wearable: Watch,
    other: HardDrive,
};

export const TASK_TYPE_ICON_MAP: Record<TaskType, Icon> = {
    repair: Wrench,
    diagnosis: Activity,
    testing: FlaskConical,
    cleaning: BrushCleaning,
    maintenance: ShieldCheck,
    upgrade: HardDriveUpload,
    calibration: CircleGauge,
    installation: HardDriveDownload,
    other: ClipboardList,
};

export const TRANSACTION_TYPE_ICON_MAP: Record<TransactionType, Icon> = {
    deposit: HandCoins,
    payment: ArrowRightLeft,
    refund: IterationCw,
};

export const TRANSACTION_METHOD_ICON_MAP: Record<TransactionMethod, Icon> = {
    cash: Banknote,
    card: CreditCard,
    online: Globe,
};

export const ADJUSTMENT_TYPE_ICON_MAP: Record<AdjustmentType, Icon> = {
    discount: BanknoteArrowDown,
    fee: BanknoteArrowUp,
};

export const ATTACHMENT_CATEGORY_ICON_MAP: Record<AttachmentCategory, Icon> = {
    photo: Image,
    other: FileText,
};
