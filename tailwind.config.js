/* eslint-disable global-require,import/no-extraneous-dependencies */

module.exports = {
    content: [
        './assets/src/**/*.jsx',
        './assets/src/**/*.tsx',
        './src/**/*.phtml',
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/aspect-ratio'),
        require('@tailwindcss/forms'),
    ],
};
