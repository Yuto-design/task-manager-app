/** @type {import('jest').Config} */
module.exports = {
    testEnvironment: 'node',
    testMatch: ['**/__tests__/**/*.test.js'],
    modulePathIgnorePatterns: ['<rootDir>/node_modules/', '<rootDir>/vendor/'],
};
