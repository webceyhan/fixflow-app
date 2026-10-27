export type ResolvedAppearance = 'light' | 'dark';
export type Appearance = ResolvedAppearance | 'system';

export type DefaultState = 'none';
export type DefaultStateColor = 'neutral';

export type StateColor =
    | DefaultStateColor
    | 'success'
    | 'error'
    | 'warning'
    | 'info';

export type FlashToast = {
    type: Exclude<StateColor, DefaultStateColor>;
    message: string;
};

export type SortDirection = DefaultState | 'asc' | 'desc';
