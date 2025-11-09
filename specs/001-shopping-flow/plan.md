# Implementation Plan: 購物網站完整流程

**Branch**: `001-shopping-flow` | **Date**: 2025-10-25 | **Spec**: [spec.md](./spec.md)
**Input**: Feature specification from `/specs/001-shopping-flow/spec.md`

**Note**: This template is filled in by the `/speckit.plan` command. See `.specify/templates/commands/plan.md` for the execution workflow.

## Summary

此專案為完整的電商購物網站前端應用，使用 Nuxt 3 框架打造。核心功能包含產品瀏覽（首頁熱門產品展示、階層式類別導航）、購物車管理（支援訪客與會員購物車同步）、會員系統（註冊登入、個人資料與地址管理）、結帳流程（收件資訊填寫、付款方式選擇）以及訂單查詢。採用純前端實作，不包含後端開發，使用 mock API 與 localStorage/IndexedDB 管理資料狀態。

## Technical Context

**Language/Version**: TypeScript 5.x / Node.js 20.x (LTS)
**Primary Dependencies**: Nuxt 3, Vue 3, Pinia (state management), VueUse (composables), Tailwind CSS (styling)
**Storage**: 前端資料儲存使用 localStorage（訪客購物車）與 IndexedDB（會員資料、訂單歷史），模擬 API 回應使用 Nuxt server routes 或 MSW (Mock Service Worker)
**Testing**: Vitest (unit tests), Playwright (E2E tests), Vue Test Utils (component tests)
**Target Platform**: 現代瀏覽器（Chrome, Firefox, Safari, Edge 最新版本），響應式設計支援行動裝置與桌面
**Project Type**: Web 應用（純前端專案）
**Performance Goals**: 首頁載入時間 ≤2 秒（3G 網路），購物車操作回應時間 ≤1 秒，支援 1000+ 並發使用者瀏覽，Core Web Vitals 達 Google "Good" 標準（LCP ≤2.5s, FID ≤100ms, CLS ≤0.1）
**Constraints**: 純前端實作無後端 API，採用 mock 資料與本地儲存模擬完整購物流程，需實作樂觀更新與錯誤處理機制
**Scale/Scope**: 6 個主要使用者故事、約 20-25 個頁面/元件、支援 3-5 個產品類別、100+ 個模擬產品資料

## Constitution Check

*GATE: Must pass before Phase 0 research. Re-check after Phase 1 design.*

**Documentation Language Standards Check (NON-NEGOTIABLE)**:
- [x] All specifications written in Traditional Chinese (zh-TW) ✅
- [x] User stories and acceptance criteria in Traditional Chinese ✅
- [x] UI text and error messages planned in Traditional Chinese ✅

**Code Quality Standards Check**:
- [x] ESLint/Prettier configuration planned (@nuxt/eslint + prettier-plugin-tailwindcss)
- [x] TypeScript strict mode enabled (tsconfig.json strict: true)
- [x] Component reusability considered in architecture (共用元件庫：BaseButton, BaseInput, ProductCard 等)
- [x] JSDoc documentation planned for all public APIs (composables, utils, stores)

**Testing Requirements Check (NON-NEGOTIABLE)**:
- [x] Test-first development approach confirmed (TDD workflow with Vitest)
- [x] Unit test coverage ≥90% target set (覆蓋 composables, stores, utils)
- [x] E2E tests planned for: product browsing, cart management, checkout, authentication ✅
- [x] Performance testing strategy defined (Lighthouse CI, Core Web Vitals monitoring)

**User Experience Consistency Check**:
- [x] Design system components planned (使用 Tailwind UI headless components + 自定義主題)
- [x] Mobile-first responsive design approach (breakpoints: sm-640px, md-768px, lg-1024px, xl-1280px)
- [x] Loading states and error handling planned (Skeleton loaders, Toast notifications, Error boundaries)
- [x] WCAG 2.1 AA accessibility compliance planned (semantic HTML, ARIA labels, keyboard navigation, focus management)

**Performance Requirements Check**:
- [x] Page load time ≤2s target set (Nuxt static generation + CDN)
- [x] Core Web Vitals optimization planned (image optimization, code splitting, preloading)
- [x] Image optimization strategy defined (Nuxt Image module with WebP/AVIF, lazy loading, responsive images)
- [x] Code splitting and lazy loading planned (route-based splitting, dynamic imports for heavy components)

## Project Structure

### Documentation (this feature)

```text
specs/001-shopping-flow/
├── plan.md              # This file (/speckit.plan command output)
├── research.md          # Phase 0 output: Nuxt 3 架構決策與最佳實踐
├── data-model.md        # Phase 1 output: 實體關係與狀態管理設計
├── quickstart.md        # Phase 1 output: 開發環境設定與執行指南
├── contracts/           # Phase 1 output: Mock API 規格定義
│   ├── products.yaml    # 產品相關 API
│   ├── cart.yaml        # 購物車相關 API
│   ├── auth.yaml        # 會員認證相關 API
│   └── orders.yaml      # 訂單相關 API
└── tasks.md             # Phase 2 output (/speckit.tasks command - NOT created by /speckit.plan)
```

### Source Code (repository root)

```text
frontend/
├── .nuxt/                      # Nuxt build output (auto-generated)
├── .output/                    # Production build output
├── assets/                     # Static assets
│   ├── css/
│   │   └── main.css           # Tailwind CSS entry
│   └── images/                # Optimized images
├── components/                 # Vue components
│   ├── base/                  # 基礎共用元件
│   │   ├── BaseButton.vue
│   │   ├── BaseInput.vue
│   │   ├── BaseModal.vue
│   │   └── BaseToast.vue
│   ├── cart/                  # 購物車相關元件
│   │   ├── CartItem.vue
│   │   ├── CartSummary.vue
│   │   └── CartEmpty.vue
│   ├── product/               # 產品相關元件
│   │   ├── ProductCard.vue
│   │   ├── ProductGrid.vue
│   │   ├── ProductDetail.vue
│   │   └── CategoryNav.vue
│   ├── checkout/              # 結帳相關元件
│   │   ├── CheckoutForm.vue
│   │   ├── ShippingInfo.vue
│   │   └── PaymentMethod.vue
│   └── user/                  # 會員相關元件
│       ├── LoginForm.vue
│       ├── RegisterForm.vue
│       └── ProfileForm.vue
├── composables/               # Vue 3 composables
│   ├── useCart.ts            # 購物車邏輯
│   ├── useAuth.ts            # 會員認證邏輯
│   ├── useProducts.ts        # 產品查詢邏輯
│   ├── useOrders.ts          # 訂單管理邏輯
│   └── useToast.ts           # Toast 通知邏輯
├── layouts/                   # Nuxt layouts
│   ├── default.vue           # 預設版面（含 header/footer）
│   └── auth.vue              # 登入註冊版面
├── middleware/                # Nuxt middleware
│   ├── auth.ts               # 會員認證中介層
│   └── guest.ts              # 訪客限定中介層
├── pages/                     # Nuxt pages (auto-routing)
│   ├── index.vue             # 首頁（熱門產品）
│   ├── products/
│   │   ├── [id].vue          # 產品詳情頁
│   │   └── category/
│   │       └── [slug].vue    # 類別頁面
│   ├── cart.vue              # 購物車頁面
│   ├── checkout/
│   │   ├── index.vue         # 結帳頁面
│   │   └── success.vue       # 訂單完成頁面
│   ├── auth/
│   │   ├── login.vue         # 登入頁面
│   │   └── register.vue      # 註冊頁面
│   └── account/              # 會員中心
│       ├── index.vue         # 個人資料
│       ├── orders/
│       │   ├── index.vue     # 訂單列表
│       │   └── [id].vue      # 訂單詳情
│       └── addresses.vue     # 地址管理
├── plugins/                   # Nuxt plugins
│   └── mock-api.ts           # Mock API interceptor (開發環境)
├── public/                    # Public static files
│   └── favicon.ico
├── server/                    # Nuxt server routes (mock API)
│   ├── api/
│   │   ├── products/
│   │   │   ├── index.get.ts
│   │   │   └── [id].get.ts
│   │   ├── cart/
│   │   │   ├── index.get.ts
│   │   │   └── items.post.ts
│   │   ├── auth/
│   │   │   ├── login.post.ts
│   │   │   └── register.post.ts
│   │   └── orders/
│   │       ├── index.get.ts
│   │       └── index.post.ts
│   └── mock/                  # Mock data
│       ├── products.ts
│       ├── categories.ts
│       └── users.ts
├── stores/                    # Pinia stores
│   ├── cart.ts               # 購物車狀態
│   ├── auth.ts               # 會員狀態
│   ├── products.ts           # 產品資料快取
│   └── ui.ts                 # UI 狀態（loading, modals）
├── types/                     # TypeScript types
│   ├── product.ts
│   ├── cart.ts
│   ├── user.ts
│   └── order.ts
├── utils/                     # Utility functions
│   ├── currency.ts           # 金額格式化
│   ├── validation.ts         # 表單驗證
│   ├── storage.ts            # localStorage/IndexedDB helper
│   └── mock.ts               # Mock data generator
├── tests/                     # Tests
│   ├── e2e/                  # Playwright E2E tests
│   │   ├── shopping-flow.spec.ts
│   │   ├── checkout.spec.ts
│   │   └── auth.spec.ts
│   ├── unit/                 # Vitest unit tests
│   │   ├── composables/
│   │   ├── stores/
│   │   └── utils/
│   └── fixtures/             # Test fixtures & mock data
├── .eslintrc.js              # ESLint config
├── .prettierrc               # Prettier config
├── nuxt.config.ts            # Nuxt configuration
├── package.json
├── playwright.config.ts      # Playwright config
├── tailwind.config.ts        # Tailwind CSS config
├── tsconfig.json             # TypeScript config
└── vitest.config.ts          # Vitest config
```

**Structure Decision**: 採用 Nuxt 3 標準專案結構，純前端實作。使用 `pages/` 目錄啟用檔案系統路由，`server/` 目錄提供 mock API endpoints，`stores/` 使用 Pinia 管理全域狀態。元件採用功能模組分類（cart, product, checkout, user），composables 封裝可重用的業務邏輯。測試分為 E2E（Playwright）與 unit tests（Vitest），確保 ≥90% 覆蓋率。

## Complexity Tracking

> **Fill ONLY if Constitution Check has violations that must be justified**

無憲法違規項目。所有檢查項目均已通過。
