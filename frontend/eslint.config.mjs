// Simplified ESLint configuration
// TypeScript checking is handled by `nuxt typecheck` command
// Prettier handles code formatting
export default [
  {
    ignores: [
      '.nuxt/**',
      '.output/**',
      'node_modules/**',
      'dist/**',
      '.git/**',
      'app/**',  // Skip app directory - TypeScript compiler handles this
      'tests/**', // Skip tests
      '**/*.ts',  // Skip TS files - TypeScript handles checking
      '**/*.vue'  // Skip Vue files
    ]
  },
  {
    files: ['**/*.{js,mjs,cjs}'],
    languageOptions: {
      ecmaVersion: 'latest',
      sourceType: 'module',
    },
    rules: {
      'no-console': process.env.NODE_ENV === 'production' ? 'warn' : 'off',
      'no-debugger': process.env.NODE_ENV === 'production' ? 'error' : 'off',
    }
  }
]
