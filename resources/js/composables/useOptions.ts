import { computed, Ref } from 'vue';

import type { Option } from '@/types';

type UseOptionsConfig<T> = {
    state: Ref<T>;
    initialOptions?: Option[];
    checkedPredicate?: (value: any, state: T) => boolean;
};

export function useOptions<T = string | null>({
    state,
    initialOptions = [],
    checkedPredicate = (value, state) => value === state,
}: UseOptionsConfig<T>) {
    // States
    const options = computed<Option[]>(() =>
        initialOptions.map((option) => ({
            ...option,
            checked: checkedPredicate(option.value, state.value),
        })),
    );

    const facades = computed<string[]>(() =>
        options.value
            .filter(({ checked }) => checked)
            .map(({ label }) => label),
    );

    // Methods
    const filteredBy = (predicate: (option: Option) => boolean) =>
        computed<Option[]>(() => options.value.filter(predicate));

    return {
        options,
        facades,
        filteredBy,
    };
}
