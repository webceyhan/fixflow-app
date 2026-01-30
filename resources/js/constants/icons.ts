import type {
    CustomerType,
    DeviceType,
    Icon,
    Priority,
    TaskType,
    UserRole,
} from '@/types';
import {
    Activity,
    BrushCleaning,
    Building2,
    ChevronsDown,
    ChevronsRight,
    ChevronsUp,
    CircleGauge,
    ClipboardList,
    Computer,
    FlaskConical,
    HardDrive,
    HardDriveDownload,
    HardDriveUpload,
    IdCard,
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
