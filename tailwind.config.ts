import type { Config } from "tailwindcss";
import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";
import daisyui from "daisyui";
import daisyuiThemes from "daisyui/src/theming/themes";

export default {
    plugins: [forms, daisyui],
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },

    daisyui: {
        themes: [
            {
                fixflow: {
                    ...daisyuiThemes.night,
                    primary: daisyuiThemes.night.secondary,
                    secondary: daisyuiThemes.night.neutral,
                },
            },
        ],
        darkTheme: "fixflow",
    },
} satisfies Config;
