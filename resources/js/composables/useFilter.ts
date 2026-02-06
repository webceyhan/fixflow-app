import { router, usePage } from '@inertiajs/vue3';
import { computed, nextTick, ref } from 'vue';

type UseFilterConfig = {
    name: string;
    decode?: (value?: string) => any;
    encode?: (value?: any) => string | undefined;
    dirtyFn?: (value: any) => boolean;
    onChange?: (value: any) => void;
};

export function useFilter<TValue = string | null>({
    name,
    decode = (value) => value || null,
    encode = (value) => value || undefined,
    dirtyFn = (value) => value !== null,
    onChange = () => {},
}: UseFilterConfig) {
    // States
    const state = ref<TValue>(decode(filterData.value[name]));

    const dirty = computed(() => dirtyFn(state.value));

    // Methods
    const update = (value: TValue) => {
        state.value = value;
        nextTick(() => onChange(value));
        setFilterData(name, encode(value));
    };

    const clear = () => update(null as any);

    // Expose
    return {
        state,
        dirty,
        update,
        clear,
    };
}

export function useToggleFilter(config: UseFilterConfig) {
    // States
    const filter = useFilter<string[]>({
        ...config,
        decode: (value) => value?.split(',').filter(Boolean) || [],
        encode: (value) => value?.join(',') || undefined,
        dirtyFn: (value) => value?.length > 0,
    });

    const toggle = (value: string) => {
        const state = filter.state.value || [];

        filter.update(
            state.includes(value)
                ? state.filter((v: any) => v !== value)
                : [...state, value],
        );
    };

    return {
        ...filter,
        toggle,
    };
}

// HELPERS /////////////////////////////////////////////////////////////////////////////////////////

const page = usePage();

const filterData = computed<Record<string, any>>(
    () => page.props?.filter ?? {},
);

const setFilterData = (name: string, value: any) => {
    router.reload({
        data: {
            page: undefined, // Reset page
            filter: { ...filterData.value, [name]: value },
        },
    });
};
