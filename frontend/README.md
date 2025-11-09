# 購物網站前端專案

完整的電商購物網站前端應用，使用 Nuxt 3 框架打造。

## 技術棧

- **框架**: Nuxt 3
- **語言**: TypeScript 5.x
- **UI 框架**: Tailwind CSS
- **狀態管理**: Pinia
- **表單驗證**: Vee-Validate + Zod
- **測試**: Vitest (Unit) + Playwright (E2E)
- **工具**: VueUse, ESLint, Prettier

## 功能特色

- ✅ 產品瀏覽（首頁熱門產品展示、階層式類別導航）
- ✅ 購物車管理（支援訪客與會員購物車同步）
- ✅ 會員系統（註冊登入、個人資料與地址管理）
- ✅ 結帳流程（收件資訊填寫、付款方式選擇）
- ✅ 訂單查詢

## 開發環境設定

### 前置需求

- Node.js 20.x (LTS)
- npm 10.x

### 安裝依賴

```bash
npm install
```

### 開發模式

```bash
npm run dev
```

開啟瀏覽器訪問 http://localhost:3000

### 建置專案

```bash
npm run build
```

### 執行測試

```bash
# 單元測試
npm run test

# 單元測試（UI 模式）
npm run test:ui

# 測試覆蓋率
npm run test:coverage

# E2E 測試
npm run test:e2e

# E2E 測試（UI 模式）
npm run test:e2e:ui
```

### 程式碼品質

```bash
# 執行 ESLint
npm run lint

# 自動修正 ESLint 問題
npm run lint:fix

# 執行 Prettier 格式化
npm run format
```

## 專案結構

```
frontend/
├── assets/          # 靜態資源（CSS、圖片）
├── components/      # Vue 元件
│   ├── base/       # 基礎共用元件
│   ├── cart/       # 購物車相關元件
│   ├── product/    # 產品相關元件
│   ├── checkout/   # 結帳相關元件
│   └── user/       # 會員相關元件
├── composables/     # Vue composables
├── layouts/         # 版面配置
├── middleware/      # 路由中介層
├── pages/           # 頁面（自動路由）
├── server/          # Nuxt server routes (mock API)
│   ├── api/        # API endpoints
│   └── mock/       # 模擬資料
├── stores/          # Pinia stores
├── types/           # TypeScript 型別定義
├── utils/           # 工具函式
└── tests/           # 測試檔案
    ├── e2e/        # E2E 測試
    └── unit/       # 單元測試
```

## 憲法原則遵循

本專案嚴格遵循專案憲法規範：

- ✅ **程式碼品質**: ESLint/Prettier 強制執行、TypeScript 嚴格模式、JSDoc 文件
- ✅ **測試需求**: TDD 開發、≥90% 單元測試覆蓋率、E2E 測試涵蓋所有關鍵流程
- ✅ **使用者體驗**: 行動優先響應式設計、WCAG 2.1 AA 無障礙標準
- ✅ **效能需求**: ≤2s 頁面載入、Core Web Vitals "Good" 標準
- ✅ **文件語言**: 所有規格文件與 UI 文字使用繁體中文（zh-TW）

## 瀏覽器支援

- Chrome (最新版)
- Firefox (最新版)
- Safari (最新版)
- Edge (最新版)

## 授權

本專案僅供學習與展示用途。
