import js from '@eslint/js';

export default [
    js.configs.recommended,
    {
        languageOptions: {
            ecmaVersion: 2022,
            sourceType: 'module',
            globals: {
                alert: 'readonly',
                confirm: 'readonly',
                console: 'readonly',
                document: 'readonly',
                expect: 'readonly',
                fetch: 'readonly',
                it: 'readonly',
                describe: 'readonly',
                setTimeout: 'readonly',
                window: 'readonly',
            },
        },
        rules: {
            'no-unused-vars': ['warn', { argsIgnorePattern: '^_', varsIgnorePattern: '^_' }],
        },
    },
];
