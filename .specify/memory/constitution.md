<!--
Sync Impact Report:
Version change: v1.0.0 → v1.1.0
Modified principles:
- None (existing principles unchanged)
Added principles:
- V. Documentation Language Standards (NON-NEGOTIABLE)
Added sections:
- None
Removed sections:
- None
Templates requiring updates:
✅ updated: .specify/templates/plan-template.md (added language requirement check)
✅ updated: .specify/templates/spec-template.md (added language requirement note)
✅ updated: .specify/templates/tasks-template.md (added documentation language task)
✅ updated: .specify/templates/checklist-template.md (added language compliance check)
✅ updated: .specify/templates/agent-file-template.md (added language requirement)
Follow-up TODOs:
- Consider creating README.md with constitution principles overview for new developers
- Consider adding pre-commit hooks configuration for ESLint/Prettier enforcement
- Consider adding GitHub Actions workflow to validate constitution compliance in PRs
- Add language validation tooling for documentation files
-->

# E-Commerce Shopping Website Constitution

## Core Principles

### I. Code Quality Standards
All code MUST maintain high quality standards through consistent formatting, clear naming 
conventions, and comprehensive documentation. Code reviews are mandatory for all changes. 
TypeScript/JavaScript code MUST use ESLint and Prettier with strict configurations. 
Functions MUST have single responsibilities and clear interfaces. Dead code MUST be removed. 
Component and utility functions MUST be reusable and well-documented with JSDoc comments.

### II. Testing Requirements (NON-NEGOTIABLE)
Test-Driven Development is mandatory: Tests MUST be written before implementation. 
Unit test coverage MUST be ≥90% for business logic. All user-facing features MUST have 
end-to-end tests covering critical user journeys: product browsing, cart management, 
checkout flow, and user authentication. Integration tests MUST verify API contracts 
and database interactions. Performance tests MUST validate response times under load.

### III. User Experience Consistency
UI components MUST follow established design system patterns. Navigation MUST be intuitive 
and consistent across all pages. Loading states MUST be implemented for all async operations 
with maximum 3-second perceived wait times. Error messages MUST be user-friendly and 
actionable. Mobile-first responsive design is mandatory with breakpoints at 768px and 1024px. 
Accessibility MUST meet WCAG 2.1 AA standards including keyboard navigation and screen readers.

### IV. Performance Requirements
Page load times MUST be ≤2 seconds on 3G networks. Core Web Vitals MUST meet Google's 
"Good" thresholds: LCP ≤2.5s, FID ≤100ms, CLS ≤0.1. Images MUST be optimized and use 
modern formats (WebP/AVIF) with lazy loading. Code splitting MUST be implemented for 
route-level and component-level chunks. Database queries MUST be optimized with proper 
indexing. API responses MUST implement caching strategies and pagination for large datasets.

### V. Documentation Language Standards (NON-NEGOTIABLE)
All specifications, implementation plans, and user-facing documentation MUST be written 
in Traditional Chinese (zh-TW). This includes: feature specifications (spec.md), 
implementation plans (plan.md), task lists (tasks.md), user stories, acceptance criteria, 
API documentation, and README files. Code comments and technical documentation SHOULD 
use Traditional Chinese where feasible. UI text, error messages, and user communications 
MUST be in Traditional Chinese. English MAY be used for: code identifiers (variables, 
functions, classes), technical terms without standard translations, and third-party 
library references.

## Security & Data Protection

Payment processing MUST use secure, PCI-DSS compliant third-party services (Stripe/PayPal). 
User passwords MUST be hashed using bcrypt with minimum 12 rounds. All API endpoints MUST 
implement rate limiting and input validation. HTTPS MUST be enforced in all environments. 
Personal data handling MUST comply with GDPR requirements including data minimization, 
consent management, and right to deletion. SQL injection and XSS vulnerabilities MUST 
be prevented through parameterized queries and content sanitization.

## Development Workflow

All features MUST follow the Git Flow branching model with feature branches from develop. 
Pull requests MUST include: feature description, testing evidence, performance impact 
assessment, and security considerations. Code reviews MUST verify: functionality, test 
coverage, performance implications, security considerations, and adherence to principles. 
Deployment MUST use automated CI/CD pipelines with staging environment validation. 
Database migrations MUST be backwards compatible and include rollback procedures.

## Governance

This constitution supersedes all other development practices and guidelines. All pull 
requests and code reviews MUST verify compliance with these principles. Any complexity 
that violates these principles MUST be justified with documented business requirements 
and approved by technical leadership. Constitutional amendments require: documented 
rationale, impact assessment, team consensus, and migration plan for existing code.

**Version**: 1.1.0 | **Ratified**: 2025-10-25 | **Last Amended**: 2025-10-25
