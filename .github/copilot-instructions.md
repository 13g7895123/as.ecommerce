# ecommerce Development Guidelines

Auto-generated from all feature plans. Last updated: 2025-10-25

## Constitution Principles (NON-NEGOTIABLE)

All development MUST adhere to the project constitution (`.specify/memory/constitution.md`):

1. **Code Quality Standards**: ESLint/Prettier enforcement, TypeScript strict mode, JSDoc documentation, code reviews mandatory
2. **Testing Requirements**: TDD mandatory, ≥90% unit test coverage, E2E tests for all critical flows
3. **User Experience Consistency**: Mobile-first responsive, WCAG 2.1 AA accessibility, design system compliance
4. **Performance Requirements**: ≤2s page loads, Core Web Vitals "Good" thresholds, optimized images/queries
5. **Documentation Language Standards**: All specifications, plans, and user-facing documentation MUST be in Traditional Chinese (zh-TW)
6. **Security & Data Protection**: HTTPS enforced, input validation, PCI-DSS for payments, GDPR compliance

## Active Technologies

- TypeScript 5.x / Node.js 20.x (LTS) + Nuxt 3, Vue 3, Pinia (state management), VueUse (composables), Tailwind CSS (styling) (001-shopping-flow)

## Project Structure

```text
src/
tests/
```

## Commands

npm test && npm run lint

## Code Style

TypeScript 5.x / Node.js 20.x (LTS): Follow standard conventions

## Recent Changes

- 001-shopping-flow: Added TypeScript 5.x / Node.js 20.x (LTS) + Nuxt 3, Vue 3, Pinia (state management), VueUse (composables), Tailwind CSS (styling)

<!-- MANUAL ADDITIONS START -->
<!-- MANUAL ADDITIONS END -->
