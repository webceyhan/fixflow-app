import { onKeyDown } from "./onKeyDown";

export function onEscaped(listener: () => void) {
    onKeyDown((event: KeyboardEvent) => {
        if (event.key === "Escape") listener();
    });
}
