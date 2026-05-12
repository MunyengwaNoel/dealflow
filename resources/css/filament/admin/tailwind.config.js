import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            borderRadius: {
                DEFAULT: '0.5rem',
                lg: '0.5rem',
                xl: '0.5rem',
                '2xl': '0.75rem',
            },
        },
    },
}
