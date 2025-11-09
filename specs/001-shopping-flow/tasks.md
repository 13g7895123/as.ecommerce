---
description: "Task list for 購物網站完整流程 implementation"
---

# Tasks: 購物網站完整流程

**Input**: Design documents from `/specs/001-shopping-flow/`
**Prerequisites**: plan.md, spec.md, research.md, data-model.md, contracts/
**Tech Stack**: Nuxt 3, Vue 3, TypeScript, Pinia, Tailwind CSS, Vitest, Playwright

**Organization**: Tasks are grouped by user story to enable independent implementation and testing of each story.

## Format: `[ID] [P?] [Story] Description`

- **[P]**: Can run in parallel (different files, no dependencies)
- **[Story]**: Which user story this task belongs to (e.g., US1, US2, US3)
- Include exact file paths in descriptions

---

## Phase 1: Setup (Shared Infrastructure)

**Purpose**: Project initialization and constitution compliance setup

**⚠️ LANGUAGE REQUIREMENT**: All documentation and UI text MUST be in Traditional Chinese (zh-TW).

- [ ] T001 Create Nuxt 3 project structure in frontend/ directory
- [ ] T002 Initialize package.json with Nuxt 3, Vue 3, TypeScript 5.x dependencies
- [ ] T003 [P] Configure ESLint with @nuxt/eslint and Prettier with prettier-plugin-tailwindcss
- [ ] T004 [P] Setup TypeScript strict mode in tsconfig.json
- [ ] T005 [P] Configure Vitest for unit tests in vitest.config.ts
- [ ] T006 [P] Configure Playwright for E2E tests in playwright.config.ts
- [ ] T007 [P] Configure Tailwind CSS in tailwind.config.ts with custom theme
- [ ] T008 [P] Setup Lighthouse CI in .github/workflows/lighthouse.yml for Core Web Vitals monitoring
- [ ] T009 [P] Configure Nuxt Image module in nuxt.config.ts for WebP/AVIF optimization
- [ ] T010 [P] Setup Pinia store in nuxt.config.ts
- [ ] T011 [P] Create i18n configuration for Traditional Chinese (zh-TW) in frontend/i18n/zh-TW.json

---

## Phase 2: Foundational (Blocking Prerequisites)

**Purpose**: Core infrastructure that MUST be complete before ANY user story can be implemented

**⚠️ CRITICAL**: No user story work can begin until this phase is complete

- [ ] T012 Create TypeScript types for Product in frontend/types/product.ts
- [ ] T013 [P] Create TypeScript types for Cart in frontend/types/cart.ts
- [ ] T014 [P] Create TypeScript types for User in frontend/types/user.ts
- [ ] T015 [P] Create TypeScript types for Order in frontend/types/order.ts
- [ ] T016 [P] Create TypeScript types for Category in frontend/types/category.ts
- [ ] T017 [P] Create TypeScript types for Address in frontend/types/address.ts
- [ ] T018 Create mock data for products in frontend/server/mock/products.ts (100+ products)
- [ ] T019 [P] Create mock data for categories in frontend/server/mock/categories.ts
- [ ] T020 [P] Create mock data for users in frontend/server/mock/users.ts
- [ ] T021 Create localStorage utility in frontend/utils/storage.ts for guest cart persistence
- [ ] T022 [P] Create currency formatter utility in frontend/utils/currency.ts
- [ ] T023 [P] Create validation schemas with Zod in frontend/utils/validation.ts
- [ ] T024 Create base Button component in frontend/components/base/BaseButton.vue with accessibility
- [ ] T025 [P] Create base Input component in frontend/components/base/BaseInput.vue with validation
- [ ] T026 [P] Create base Modal component in frontend/components/base/BaseModal.vue with focus trap
- [ ] T027 [P] Create base Toast component in frontend/components/base/BaseToast.vue
- [ ] T028 Create default layout in frontend/layouts/default.vue with header/footer
- [ ] T029 [P] Create auth layout in frontend/layouts/auth.vue for login/register pages
- [ ] T030 Create useToast composable in frontend/composables/useToast.ts
- [ ] T031 Create Nuxt server route GET /api/products in frontend/server/api/products/index.get.ts
- [ ] T032 [P] Create Nuxt server route GET /api/products/:id in frontend/server/api/products/[id].get.ts
- [ ] T033 [P] Create Nuxt server route GET /api/categories in frontend/server/api/categories/index.get.ts

**Checkpoint**: Foundation ready - user story implementation can now begin in parallel

---

## Phase 3: User Story 1 - 瀏覽熱門產品 (Priority: P1) 🎯 MVP

**Goal**: 訪客可以在首頁看到熱門產品展示，每個產品卡片顯示圖片、名稱、價格與簡短描述

**Independent Test**: 直接訪問首頁 http://localhost:3000/ 驗證是否顯示 8-12 個產品卡片，每個卡片包含完整資訊

### Implementation for User Story 1

- [ ] T034 [P] [US1] Create ProductCard component in frontend/components/product/ProductCard.vue
- [ ] T035 [P] [US1] Create ProductGrid component in frontend/components/product/ProductGrid.vue
- [ ] T036 [US1] Create useProducts composable in frontend/composables/useProducts.ts
- [ ] T037 [US1] Create products store in frontend/stores/products.ts with caching
- [ ] T038 [US1] Implement home page in frontend/pages/index.vue with featured products
- [ ] T039 [US1] Add skeleton loaders for product cards during loading state
- [ ] T040 [US1] Add error boundary for product loading failures

**Checkpoint**: User Story 1 完成 - 首頁可以獨立展示熱門產品

---

## Phase 4: User Story 2 - 依類別瀏覽產品 (Priority: P1)

**Goal**: 使用者可以透過類別導航找到產品，支援階層式分類與篩選排序

**Independent Test**: 點擊首頁類別連結，驗證是否正確導航至分類頁面並顯示該類別產品

### Implementation for User Story 2

- [ ] T041 [P] [US2] Create CategoryNav component in frontend/components/product/CategoryNav.vue
- [ ] T042 [US2] Update default layout to include CategoryNav in header
- [ ] T043 [US2] Create category page in frontend/pages/products/category/[slug].vue
- [ ] T044 [US2] Add category filtering logic to useProducts composable
- [ ] T045 [US2] Add sorting functionality (price, newest, popular) to ProductGrid
- [ ] T046 [US2] Implement infinite scroll or pagination for product lists
- [ ] T047 [US2] Add subcategory filter UI when applicable

**Checkpoint**: User Stories 1 和 2 完成 - 產品瀏覽功能完整可用

---

## Phase 5: User Story 3 - 加入購物車 (Priority: P2)

**Goal**: 使用者可以選擇數量並加入購物車，支援調整數量、移除商品，並即時計算總金額

**Independent Test**: 在產品詳情頁點擊「加入購物車」，驗證購物車頁面正確顯示商品與金額計算

### Implementation for User Story 3

- [ ] T048 [P] [US3] Create ProductDetail component in frontend/components/product/ProductDetail.vue
- [ ] T049 [P] [US3] Create CartItem component in frontend/components/cart/CartItem.vue
- [ ] T050 [P] [US3] Create CartSummary component in frontend/components/cart/CartSummary.vue
- [ ] T051 [P] [US3] Create CartEmpty component in frontend/components/cart/CartEmpty.vue
- [ ] T052 [US3] Create cart store in frontend/stores/cart.ts with localStorage sync
- [ ] T053 [US3] Create useCart composable in frontend/composables/useCart.ts
- [ ] T054 [US3] Implement product detail page in frontend/pages/products/[id].vue
- [ ] T055 [US3] Implement cart page in frontend/pages/cart.vue
- [ ] T056 [US3] Create Nuxt server route GET /api/cart in frontend/server/api/cart/index.get.ts
- [ ] T057 [US3] Create Nuxt server route PUT /api/cart in frontend/server/api/cart/index.put.ts
- [ ] T058 [US3] Add cart badge to header showing item count
- [ ] T059 [US3] Implement shipping calculation logic (≥NT$1000 免運，否則 NT$100)
- [ ] T060 [US3] Add stock validation when adding/updating cart items
- [ ] T061 [US3] Add optimistic UI updates with rollback on error

**Checkpoint**: User Stories 1-3 完成 - 完整的產品瀏覽與購物車功能可用

---

## Phase 6: User Story 4 - 會員註冊與登入 (Priority: P2)

**Goal**: 使用者可以註冊帳號並登入，系統驗證資料格式並檢查 Email 是否重複

**Independent Test**: 註冊新帳號並登入，驗證會員系統運作，訪客購物車合併至會員購物車

### Implementation for User Story 4

- [ ] T062 [P] [US4] Create LoginForm component in frontend/components/user/LoginForm.vue with Vee-Validate
- [ ] T063 [P] [US4] Create RegisterForm component in frontend/components/user/RegisterForm.vue with Zod validation
- [ ] T064 [P] [US4] Create ProfileForm component in frontend/components/user/ProfileForm.vue
- [ ] T065 [US4] Create auth store in frontend/stores/auth.ts with token management
- [ ] T066 [US4] Create useAuth composable in frontend/composables/useAuth.ts
- [ ] T067 [US4] Implement login page in frontend/pages/auth/login.vue
- [ ] T068 [US4] Implement register page in frontend/pages/auth/register.vue
- [ ] T069 [US4] Create auth middleware in frontend/middleware/auth.ts for protected routes
- [ ] T070 [US4] Create guest middleware in frontend/middleware/guest.ts for login/register pages
- [ ] T071 [US4] Create Nuxt server route POST /api/auth/register in frontend/server/api/auth/register.post.ts
- [ ] T072 [US4] Create Nuxt server route POST /api/auth/login in frontend/server/api/auth/login.post.ts
- [ ] T073 [US4] Create Nuxt server route POST /api/auth/logout in frontend/server/api/auth/logout.post.ts
- [ ] T074 [US4] Implement cart merge logic when user logs in (guest → member)
- [ ] T075 [US4] Add password strength indicator in register form
- [ ] T076 [US4] Implement brute-force protection (5 attempts → 15 min lockout)
- [ ] T077 [US4] Add session expiration handling (30 min idle → redirect to login)

**Checkpoint**: User Stories 1-4 完成 - 產品瀏覽、購物車與會員系統完整可用

---

## Phase 7: User Story 5 - 結帳流程 (Priority: P3)

**Goal**: 使用者填寫收件資訊與選擇付款方式後，系統驗證庫存並建立訂單

**Independent Test**: 從購物車點擊「前往結帳」，填寫完整資料送出訂單，驗證訂單成功建立並顯示訂單摘要

### Implementation for User Story 5

- [ ] T078 [P] [US5] Create CheckoutForm component in frontend/components/checkout/CheckoutForm.vue
- [ ] T079 [P] [US5] Create ShippingInfo component in frontend/components/checkout/ShippingInfo.vue
- [ ] T080 [P] [US5] Create PaymentMethod component in frontend/components/checkout/PaymentMethod.vue
- [ ] T081 [US5] Create useOrders composable in frontend/composables/useOrders.ts
- [ ] T082 [US5] Implement checkout page in frontend/pages/checkout/index.vue
- [ ] T083 [US5] Implement order success page in frontend/pages/checkout/success.vue
- [ ] T084 [US5] Create Nuxt server route POST /api/orders in frontend/server/api/orders/index.post.ts
- [ ] T085 [US5] Add shipping info validation with Zod schema
- [ ] T086 [US5] Implement stock validation and locking logic in order creation
- [ ] T087 [US5] Auto-fill default address for logged-in members
- [ ] T088 [US5] Clear cart after successful order creation
- [ ] T089 [US5] Add order confirmation email mock in server route
- [ ] T090 [US5] Handle payment gateway timeout scenarios

**Checkpoint**: User Stories 1-5 完成 - 完整購物流程從瀏覽到結帳可用

---

## Phase 8: User Story 6 - 會員中心與訂單查詢 (Priority: P3)

**Goal**: 會員可以查看個人資料、訂單歷史記錄與管理收件地址

**Independent Test**: 登入會員帳號後進入會員中心，驗證顯示個人資料、訂單列表與地址管理功能

### Implementation for User Story 6

- [ ] T091 [US6] Create account index page in frontend/pages/account/index.vue for profile
- [ ] T092 [US6] Create orders list page in frontend/pages/account/orders/index.vue
- [ ] T093 [US6] Create order detail page in frontend/pages/account/orders/[id].vue
- [ ] T094 [US6] Create addresses management page in frontend/pages/account/addresses.vue
- [ ] T095 [US6] Create Nuxt server route GET /api/orders in frontend/server/api/orders/index.get.ts
- [ ] T096 [US6] Create Nuxt server route GET /api/orders/:id in frontend/server/api/orders/[id].get.ts
- [ ] T097 [US6] Create Nuxt server route GET /api/addresses in frontend/server/api/addresses/index.get.ts
- [ ] T098 [US6] Create Nuxt server route POST /api/addresses in frontend/server/api/addresses/index.post.ts
- [ ] T099 [US6] Create Nuxt server route PUT /api/addresses/:id in frontend/server/api/addresses/[id].put.ts
- [ ] T100 [US6] Create Nuxt server route DELETE /api/addresses/:id in frontend/server/api/addresses/[id].delete.ts
- [ ] T101 [US6] Implement order status badge with color coding
- [ ] T102 [US6] Add pagination for order history
- [ ] T103 [US6] Implement set default address functionality
- [ ] T104 [US6] Add order tracking information display

**Checkpoint**: All user stories complete - 完整的電商購物網站功能已實作

---

## Phase 9: Polish & Cross-Cutting Concerns

**Purpose**: Improvements that affect multiple user stories and final quality assurance

- [ ] T105 [P] Write E2E test for complete shopping flow in frontend/tests/e2e/shopping-flow.spec.ts
- [ ] T106 [P] Write E2E test for checkout process in frontend/tests/e2e/checkout.spec.ts
- [ ] T107 [P] Write E2E test for authentication in frontend/tests/e2e/auth.spec.ts
- [ ] T108 [P] Write unit tests for cart store in frontend/tests/unit/stores/cart.spec.ts
- [ ] T109 [P] Write unit tests for auth store in frontend/tests/unit/stores/auth.spec.ts
- [ ] T110 [P] Write unit tests for useCart composable in frontend/tests/unit/composables/useCart.spec.ts
- [ ] T111 [P] Write unit tests for validation utils in frontend/tests/unit/utils/validation.spec.ts
- [ ] T112 [P] Write unit tests for storage utils in frontend/tests/unit/utils/storage.spec.ts
- [ ] T113 Run ESLint and fix all warnings/errors across codebase
- [ ] T114 Run Prettier to format all files
- [ ] T115 Verify all unit tests achieve ≥90% coverage
- [ ] T116 Verify all E2E tests pass in Playwright
- [ ] T117 Run Lighthouse CI and verify Core Web Vitals meet "Good" thresholds (LCP ≤2.5s, FID ≤100ms, CLS ≤0.1)
- [ ] T118 Test accessibility with axe-core and fix WCAG 2.1 AA violations
- [ ] T119 [P] Create README.md with Traditional Chinese instructions
- [ ] T120 [P] Create quickstart validation script per quickstart.md
- [ ] T121 Test mobile responsiveness at breakpoints (sm:640px, md:768px, lg:1024px, xl:1280px)
- [ ] T122 Test cross-browser compatibility (Chrome, Firefox, Safari, Edge)
- [ ] T123 Verify all UI text is in Traditional Chinese (zh-TW)
- [ ] T124 Run `nuxt generate` and verify static site builds successfully
- [ ] T125 Deploy to staging environment (Netlify/Vercel) and validate

---

## Dependencies & Execution Order

### Phase Dependencies

- **Setup (Phase 1)**: No dependencies - can start immediately
- **Foundational (Phase 2)**: Depends on Setup (Phase 1) - BLOCKS all user stories
- **User Stories (Phase 3-8)**: All depend on Foundational phase completion
  - User stories can proceed in parallel (if staffed)
  - Or sequentially in priority order (P1 → P2 → P3)
- **Polish (Phase 9)**: Depends on all user stories being complete

### User Story Dependencies

- **User Story 1 (P1)**: Can start after Foundational - No dependencies on other stories
- **User Story 2 (P1)**: Can start after Foundational - Integrates with US1 (uses ProductCard) but independently testable
- **User Story 3 (P2)**: Can start after Foundational - Uses US1/US2 components but independently testable
- **User Story 4 (P2)**: Can start after Foundational - Integrates with US3 (cart merge) but independently testable
- **User Story 5 (P3)**: Can start after Foundational - Requires US3 (cart) and US4 (auth) for full experience but core checkout is independent
- **User Story 6 (P3)**: Can start after Foundational - Requires US4 (auth) and US5 (orders) but independently testable

### Within Each User Story

- Components marked [P] can be created in parallel
- Composables depend on stores
- Pages depend on composables and components
- Server routes can be created in parallel with frontend
- Story complete before moving to next priority

### Parallel Opportunities

- **Phase 1 Setup**: All tasks marked [P] (T003-T011) can run simultaneously
- **Phase 2 Foundational**: 
  - All types (T013-T017) can run in parallel
  - All mock data (T019-T020) can run in parallel
  - All utilities (T022-T023) can run in parallel
  - All base components (T025-T027) can run in parallel
  - All layouts (T029) can run in parallel
  - All server routes (T032-T033) can run in parallel
- **Within User Stories**: All [P] tasks can run simultaneously
- **Phase 9 Polish**: All test writing tasks (T105-T112) and documentation tasks (T119-T120) can run in parallel

---

## Parallel Example: User Story 3

```bash
# Launch all components for User Story 3 together:
Task T048: "Create ProductDetail component in frontend/components/product/ProductDetail.vue"
Task T049: "Create CartItem component in frontend/components/cart/CartItem.vue"
Task T050: "Create CartSummary component in frontend/components/cart/CartSummary.vue"
Task T051: "Create CartEmpty component in frontend/components/cart/CartEmpty.vue"

# Then launch dependent tasks:
Task T052: "Create cart store" (uses types from Phase 2)
Task T053: "Create useCart composable" (uses cart store)
Task T054-T055: "Create pages" (use composable and components)
```

---

## Implementation Strategy

### MVP First (User Stories 1-2 Only)

1. Complete Phase 1: Setup
2. Complete Phase 2: Foundational (CRITICAL - blocks all stories)
3. Complete Phase 3: User Story 1 (熱門產品展示)
4. Complete Phase 4: User Story 2 (類別瀏覽)
5. **STOP and VALIDATE**: Test product browsing independently
6. Deploy/demo if ready

**Deliverable**: 完整的產品展示與瀏覽功能，使用者可以查看熱門產品與依類別瀏覽

### Incremental Delivery (Add Shopping Cart)

1. Complete Phases 1-4 (product browsing working)
2. Complete Phase 5: User Story 3 (購物車)
3. **STOP and VALIDATE**: Test cart functionality independently
4. Deploy/demo

**Deliverable**: 產品瀏覽 + 購物車管理，使用者可以加入商品並管理購物車

### Full E-commerce Flow (Add Checkout)

1. Complete Phases 1-5 (browsing + cart working)
2. Complete Phase 6: User Story 4 (會員系統)
3. Complete Phase 7: User Story 5 (結帳流程)
4. Complete Phase 8: User Story 6 (會員中心)
5. **STOP and VALIDATE**: Test complete shopping flow
6. Complete Phase 9: Polish & testing
7. Deploy to production

**Deliverable**: 完整的電商購物網站，從瀏覽、購物車、會員、結帳到訂單查詢

### Parallel Team Strategy

With multiple developers:

1. Team completes Setup + Foundational together
2. Once Foundational is done:
   - Developer A: User Story 1 + 2 (產品瀏覽)
   - Developer B: User Story 3 (購物車)
   - Developer C: User Story 4 (會員系統)
3. After US3 + US4 complete:
   - Developer A: User Story 5 (結帳)
   - Developer B: User Story 6 (會員中心)
   - Developer C: Tests (Phase 9)
4. Stories complete and integrate independently

---

## Task Summary

**Total Tasks**: 125

**Task Breakdown by Phase**:
- Phase 1 (Setup): 11 tasks
- Phase 2 (Foundational): 22 tasks
- Phase 3 (US1 - 熱門產品): 7 tasks
- Phase 4 (US2 - 類別瀏覽): 7 tasks
- Phase 5 (US3 - 購物車): 14 tasks
- Phase 6 (US4 - 會員系統): 16 tasks
- Phase 7 (US5 - 結帳流程): 13 tasks
- Phase 8 (US6 - 會員中心): 14 tasks
- Phase 9 (Polish): 21 tasks

**Parallelizable Tasks**: 58 tasks marked with [P]

**MVP Scope** (Recommended first delivery):
- Phase 1: Setup (11 tasks)
- Phase 2: Foundational (22 tasks)
- Phase 3: User Story 1 (7 tasks)
- Phase 4: User Story 2 (7 tasks)
- **Total MVP: 47 tasks** - delivers complete product browsing experience

**Independent Test Criteria**:
- ✅ US1: 首頁顯示 8-12 個產品卡片，包含圖片、名稱、價格
- ✅ US2: 點擊類別可查看該類別產品，支援排序
- ✅ US3: 可加入購物車、調整數量、移除商品，即時計算總金額
- ✅ US4: 可註冊登入，訪客購物車合併至會員購物車
- ✅ US5: 可填寫收件資訊並建立訂單，顯示訂單編號
- ✅ US6: 可查看訂單歷史與管理收件地址

---

## Notes

- [P] tasks = different files, no dependencies
- [Story] label maps task to specific user story for traceability
- Each user story should be independently completable and testable
- Commit after each task or logical group
- Stop at any checkpoint to validate story independently
- All file paths assume `frontend/` as Nuxt 3 project root
- All UI text and error messages MUST be in Traditional Chinese (zh-TW)
- Target ≥90% unit test coverage (measured in Phase 9)
- Target Core Web Vitals "Good" thresholds (verified in Phase 9)
