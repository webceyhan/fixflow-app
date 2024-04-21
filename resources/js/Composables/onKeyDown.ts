import { onMounted, onUnmounted } from "vue";

export function onKeyDown(listener: (event: KeyboardEvent) => void): void {
    onMounted(() => {
        document.addEventListener("keydown", listener);
    });

    onUnmounted(() => {
        document.removeEventListener("keydown", listener);
    });
}
