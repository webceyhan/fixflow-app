<script setup lang="ts">
import { DateTime } from '@/components/data';
import { FormActions } from '@/components/form';
import { DatePicker } from '@/components/ui/date-picker';
import {
    Field,
    FieldDescription,
    FieldError,
    FieldGroup,
    FieldLabel,
    FieldLegend,
    FieldSeparator,
    FieldSet,
} from '@/components/ui/field';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectGroup,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useSaveForm } from '@/composables/useSaveForm';
import { DEVICE_STATUS, DEVICE_TYPE } from '@/constants';
import { store, update } from '@/routes/devices';
import type { Device } from '@/types';
import { computed, ref, watch } from 'vue';
import DeviceTypeSelector from './DeviceTypeSelector.vue';

const WARRANTY_DURATIONS = [
    { value: '0', label: 'No Warranty' },
    { value: '3', label: '3 Months' },
    { value: '6', label: '6 Months' },
    { value: '12', label: '1 Year' },
    { value: '24', label: '2 Years' },
    { value: '36', label: '3 Years' },
];

interface Props {
    device: Device;
}

const { device } = defineProps<Props>();

const action = device.id ? update(device) : store(device.customer_id);

const { errors, form, isEditing, processing } = useSaveForm({
    entityName: 'Device',
    action,
    data: {
        model: device.model ?? '',
        brand: device.brand ?? '',
        color: device.color ?? '',
        imei: device.imei ?? '',
        serial_number: device.serial_number ?? '',
        passcode: device.passcode ?? '',
        purchase_date: device.purchase_date ?? '',
        warranty_expire_date: device.warranty_expire_date ?? '',
        note: device.note ?? '',
        type: device.type,
        status: device.status,
    },
});

const isMobile = computed(() =>
    [DEVICE_TYPE.Phone, DEVICE_TYPE.Tablet].includes(form.type as any),
);

// Calculate initial warranty duration from existing dates
const calculateInitialDuration = (): string => {
    const { purchase_date, warranty_expire_date } = device;

    // Return '0' if either date is missing
    if (!purchase_date || !warranty_expire_date) return '0';

    const purchase = new Date(purchase_date);
    const expiry = new Date(warranty_expire_date);
    const monthsDiff = Math.round(
        (expiry.getTime() - purchase.getTime()) / (1000 * 60 * 60 * 24 * 30.44),
    );

    // Find closest matching duration
    const closest = WARRANTY_DURATIONS.reduce((prev, curr) => {
        const prevDiff = Math.abs(Number(prev.value) - monthsDiff);
        const currDiff = Math.abs(Number(curr.value) - monthsDiff);
        return currDiff < prevDiff ? curr : prev;
    });

    return closest.value;
};

const warrantyDuration = ref<string>(calculateInitialDuration());

// Watch for warranty duration changes to update expiration date
watch(warrantyDuration, (value) => {
    if (form.purchase_date && Number(value)) {
        const purchaseDate = new Date(form.purchase_date);
        const months = Number(value);
        const expiryDate = new Date(purchaseDate);
        expiryDate.setMonth(expiryDate.getMonth() + months);
        form.warranty_expire_date = expiryDate.toISOString().split('T')[0];
    } else {
        // Clear warranty expiration if duration is 0 or no purchase date
        form.warranty_expire_date = '';
    }
});

// Watch for purchase date changes to recalculate warranty expiration
watch(
    () => form.purchase_date,
    (newDate) => {
        if (newDate && Number(warrantyDuration.value)) {
            const purchaseDate = new Date(newDate);
            const months = Number(warrantyDuration.value);
            const expiryDate = new Date(purchaseDate);
            expiryDate.setMonth(expiryDate.getMonth() + months);
            form.warranty_expire_date = expiryDate.toISOString().split('T')[0];
        } else {
            // Clear warranty expiration if duration is 0 or no purchase date
            form.warranty_expire_date = '';
        }
    },
);
</script>

<template>
    <form @submit.prevent="form.submit()">
        <FieldGroup>
            <Field>
                <DeviceTypeSelector v-model="form.type" />
            </Field>

            <FieldSet>
                <FieldLegend>Basic Information</FieldLegend>
                <FieldDescription>
                    Device identification and details
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="model">Model</FieldLabel>
                        <Input
                            id="model"
                            placeholder="iPhone 15 Pro"
                            v-model="form.model"
                        />
                        <FieldError :message="errors.model" />
                    </Field>

                    <Field>
                        <FieldLabel for="brand">Brand</FieldLabel>
                        <Input
                            id="brand"
                            placeholder="Apple"
                            v-model="form.brand"
                        />
                        <FieldError :message="errors.brand" />
                    </Field>

                    <Field>
                        <FieldLabel for="color">Color</FieldLabel>
                        <Input
                            id="color"
                            placeholder="Space Gray"
                            v-model="form.color"
                        />
                        <FieldError :message="errors.color" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet>
                <FieldLegend>Device Identifiers</FieldLegend>
                <FieldDescription>
                    Unique identifiers and security information
                </FieldDescription>

                <FieldGroup>
                    <Field v-if="isMobile">
                        <FieldLabel for="imei">IMEI</FieldLabel>
                        <Input
                            id="imei"
                            placeholder="123456789012345"
                            v-model="form.imei"
                        />
                        <FieldError :message="errors.imei" />
                    </Field>

                    <Field>
                        <FieldLabel for="serial_number">
                            Serial Number
                        </FieldLabel>
                        <Input
                            id="serial_number"
                            placeholder="ABCD1234EFGH"
                            v-model="form.serial_number"
                        />
                        <FieldError :message="errors.serial_number" />
                    </Field>

                    <Field>
                        <FieldLabel for="passcode">Passcode</FieldLabel>
                        <Input
                            id="passcode"
                            type="password"
                            placeholder="Device passcode"
                            v-model="form.passcode"
                        />
                        <FieldError :message="errors.passcode" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet>
                <FieldLegend>Warranty Information</FieldLegend>
                <FieldDescription>
                    Purchase and warranty dates
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="purchase_date">
                            Purchase Date
                        </FieldLabel>
                        <DatePicker
                            id="purchase_date"
                            v-model="form.purchase_date"
                        />
                        <FieldError :message="errors.purchase_date" />
                    </Field>

                    <Field>
                        <FieldLabel for="warranty_duration">
                            Warranty Duration
                        </FieldLabel>
                        <Select v-model="warrantyDuration">
                            <SelectTrigger>
                                <SelectValue
                                    placeholder="Select warranty duration"
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="duration in WARRANTY_DURATIONS"
                                        :key="duration.value"
                                        :value="duration.value"
                                    >
                                        {{ duration.label }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FieldDescription v-if="form.warranty_expire_date">
                            Warranty will expire on
                            <DateTime :value="form.warranty_expire_date" />
                        </FieldDescription>
                        <FieldError :message="errors.warranty_expire_date" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FieldSeparator />

            <FieldSet v-if="isEditing">
                <FieldLegend>Service Status</FieldLegend>
                <FieldDescription>
                    Device status and internal notes
                </FieldDescription>

                <FieldGroup>
                    <Field>
                        <FieldLabel for="status">Status</FieldLabel>
                        <Select v-model="form.status">
                            <SelectTrigger>
                                <SelectValue placeholder="Select a status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectGroup>
                                    <SelectItem
                                        v-for="(value, key) in DEVICE_STATUS"
                                        :key
                                        :value
                                    >
                                        {{ key }}
                                    </SelectItem>
                                </SelectGroup>
                            </SelectContent>
                        </Select>
                        <FieldError :message="errors.status" />
                    </Field>

                    <Field>
                        <FieldLabel for="note">Note</FieldLabel>
                        <Textarea
                            id="note"
                            placeholder="Additional information..."
                            v-model="form.note"
                        />
                        <FieldError :message="errors.note" />
                    </Field>
                </FieldGroup>
            </FieldSet>

            <FormActions :processing :is-editing />
        </FieldGroup>
    </form>
</template>
