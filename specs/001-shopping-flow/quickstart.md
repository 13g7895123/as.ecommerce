# 快速開始指南：購物網站前端專案

**專案**: 001-shopping-flow  
**最後更新**: 2025-10-25  
**預計開發時間**: 4-6 週

## 概述

本指南協助開發者快速建立 Nuxt 3 電商購物網站開發環境，並提供基本的專案結構與開發流程說明。

---

## 系統需求

### 必要環境
- **Node.js**: v20.x LTS（建議使用 nvm 管理版本）
- **npm**: v10.x 或 **pnpm**: v8.x（推薦 pnpm，速度更快）
- **Git**: v2.x

### 推薦工具
- **VS Code**: 建議安裝以下擴充套件
  - Vue Language Features (Volar)
  - TypeScript Vue Plugin (Volar)
  - ESLint
  - Prettier
  - Tailwind CSS IntelliSense
  - Playwright Test for VS Code
- **瀏覽器擴充**
  - Vue.js devtools
  - React Developer Tools（用於 Pinia devtools）

---

## 專案初始化

### 1. 建立 Nuxt 3 專案

```bash
# 使用 npx 建立專案（選擇 TypeScript）
npx nuxi@latest init frontend

# 或使用 pnpm
pnpm dlx nuxi@latest init frontend

cd frontend
```

### 2. 安裝核心依賴

```bash
# 安裝 Pinia 狀態管理
npm install pinia @pinia/nuxt

# 安裝 Tailwind CSS
npm install -D tailwindcss postcss autoprefixer
npx tailwindcss init

# 安裝 VueUse composables
npm install @vueuse/core @vueuse/nuxt

# 安裝表單驗證
npm install vee-validate zod @vee-validate/zod

# 安裝圖片最佳化
npm install @nuxt/image

# 安裝測試工具
npm install -D vitest @vue/test-utils @vitest/ui
npm install -D @playwright/test
npm install -D @nuxt/test-utils
```

### 3. 安裝開發工具

```bash
# ESLint & Prettier
npm install -D @nuxt/eslint-config eslint prettier eslint-plugin-prettier prettier-plugin-tailwindcss

# TypeScript 工具
npm install -D @types/node
```

---

## 專案配置

### nuxt.config.ts

```typescript
// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  devtools: { enabled: true },
  
  modules: [
    '@pinia/nuxt',
    '@nuxtjs/tailwindcss',
    '@vueuse/nuxt',
    '@nuxt/image',
    '@nuxt/eslint'
  ],
  
  // TypeScript 設定
  typescript: {
    strict: true,
    typeCheck: true
  },
  
  // Pinia 設定
  pinia: {
    autoImports: ['defineStore', 'storeToRefs']
  },
  
  // Tailwind CSS 設定
  tailwindcss: {
    cssPath: '~/assets/css/main.css',
    configPath: 'tailwind.config.ts',
    viewer: true
  },
  
  // Nuxt Image 設定
  image: {
    formats: ['webp', 'avif', 'jpeg'],
    screens: {
      xs: 320,
      sm: 640,
      md: 768,
      lg: 1024,
      xl: 1280,
      '2xl': 1536
    }
  },
  
  // 自動匯入設定
  imports: {
    dirs: ['stores', 'composables', 'utils']
  },
  
  // 實驗性功能
  experimental: {
    componentIslands: true,
    viewTransition: true
  },
  
  // Nitro 設定（mock API）
  nitro: {
    devProxy: {
      '/api': {
        target: 'http://localhost:3000/api',
        changeOrigin: true
      }
    }
  },
  
  // App 設定
  app: {
    head: {
      titleTemplate: '%s - 購物網站',
      htmlAttrs: {
        lang: 'zh-TW'
      },
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: '完整的電商購物體驗' }
      ]
    }
  }
})
```

### tailwind.config.ts

```typescript
import type { Config } from 'tailwindcss'

export default <Config>{
  content: [
    './components/**/*.{vue,js,ts}',
    './layouts/**/*.vue',
    './pages/**/*.vue',
    './composables/**/*.{js,ts}',
    './plugins/**/*.{js,ts}',
    './app.vue'
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#f0f9ff',
          100: '#e0f2fe',
          500: '#0ea5e9',
          600: '#0284c7',
          700: '#0369a1'
        }
      },
      fontFamily: {
        sans: ['Noto Sans TC', 'sans-serif']
      }
    }
  },
  plugins: []
}
```

### tsconfig.json

```json
{
  "extends": "./.nuxt/tsconfig.json",
  "compilerOptions": {
    "strict": true,
    "strictNullChecks": true,
    "noUnusedLocals": true,
    "noUnusedParameters": true,
    "noImplicitReturns": true
  }
}
```

### .eslintrc.js

```javascript
module.exports = {
  root: true,
  extends: ['@nuxt/eslint-config', 'plugin:prettier/recommended'],
  rules: {
    'vue/multi-word-component-names': 'off',
    'vue/no-v-html': 'warn'
  }
}
```

### .prettierrc

```json
{
  "semi": false,
  "singleQuote": true,
  "trailingComma": "none",
  "arrowParens": "avoid",
  "printWidth": 100,
  "plugins": ["prettier-plugin-tailwindcss"]
}
```

---

## 目錄結構建立

```bash
# 建立基本目錄結構
mkdir -p frontend/{components/{base,cart,product,checkout,user},composables,stores,utils,types,server/{api/{products,cart,auth,orders},mock},tests/{e2e,unit/{composables,stores,utils},fixtures}}

# 建立必要檔案
touch frontend/assets/css/main.css
touch frontend/types/{product,cart,user,order,address}.ts
```

### assets/css/main.css

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

@layer base {
  /* 全域樣式 */
  body {
    @apply font-sans text-gray-900 antialiased;
  }
  
  /* 無障礙：僅供螢幕閱讀器 */
  .sr-only {
    @apply absolute w-px h-px p-0 -m-px overflow-hidden whitespace-nowrap border-0;
    clip: rect(0, 0, 0, 0);
  }
}

@layer components {
  /* 載入動畫 */
  @keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }
  
  .skeleton {
    @apply bg-gradient-to-r from-gray-200 via-gray-300 to-gray-200 animate-pulse;
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
  }
}
```

---

## 開發流程

### 啟動開發伺服器

```bash
# 開發模式
npm run dev

# 瀏覽器開啟 http://localhost:3000
```

### 執行測試

```bash
# 單元測試
npm run test:unit

# 單元測試（watch 模式）
npm run test:unit:watch

# E2E 測試
npm run test:e2e

# E2E 測試（UI 模式）
npm run test:e2e:ui

# 測試覆蓋率
npm run test:coverage
```

### Linting 與格式化

```bash
# 檢查程式碼風格
npm run lint

# 自動修正
npm run lint:fix

# Prettier 格式化
npm run format
```

### 建置與部署

```bash
# 建置 SSG（靜態站點生成）
npm run generate

# 預覽建置結果
npm run preview

# 建置 SSR（需要 Node.js server）
npm run build
```

---

## 開發工作流程

### TDD 流程（測試驅動開發）

1. **撰寫測試**
   ```bash
   # 建立測試檔案
   touch tests/unit/composables/useCart.spec.ts
   ```

2. **撰寫最小實作**
   ```bash
   # 建立 composable
   touch composables/useCart.ts
   ```

3. **執行測試**
   ```bash
   npm run test:unit -- useCart
   ```

4. **重構與優化**

### Git Workflow

```bash
# 從 develop 建立 feature branch
git checkout -b 001-shopping-flow develop

# 提交變更（遵循 Conventional Commits）
git commit -m "feat(cart): 實作加入購物車功能"

# 推送至遠端
git push origin 001-shopping-flow

# 建立 Pull Request
```

### Commit Message 規範

```
feat: 新功能
fix: 修正錯誤
docs: 文件更新
style: 程式碼格式（不影響功能）
refactor: 重構
test: 測試相關
chore: 建置工具或輔助工具
```

---

## Mock API 開發

### 建立 Mock 資料

```typescript
// server/mock/products.ts
import type { Product } from '~/types/product'

export const mockProducts: Product[] = [
  {
    id: 'prod-001',
    name: '無線藍牙耳機',
    slug: 'wireless-earbuds',
    description: '高音質主動降噪，續航力 30 小時',
    shortDescription: '高音質主動降噪',
    price: 2990,
    originalPrice: 3990,
    images: [
      {
        url: '/images/products/prod-001-1.jpg',
        alt: '無線藍牙耳機',
        isPrimary: true
      }
    ],
    categoryId: 'cat-headphones',
    stock: 50,
    isFeatured: true,
    specs: [
      { label: '顏色', value: '黑色' },
      { label: '藍牙版本', value: '5.3' }
    ],
    tags: ['新品', '熱銷'],
    rating: 4.5,
    reviewCount: 128,
    createdAt: '2025-10-01T00:00:00Z',
    updatedAt: '2025-10-25T00:00:00Z'
  }
  // ... 更多產品
]
```

### 建立 API Endpoint

```typescript
// server/api/products/index.get.ts
import { mockProducts } from '~/server/mock/products'

export default defineEventHandler(async (event) => {
  const query = getQuery(event)
  
  // 模擬延遲
  await new Promise(resolve => setTimeout(resolve, 300))
  
  let filtered = mockProducts
  
  // 類別篩選
  if (query.categoryId) {
    filtered = filtered.filter(p => p.categoryId === query.categoryId)
  }
  
  // 搜尋
  if (query.search) {
    const searchTerm = String(query.search).toLowerCase()
    filtered = filtered.filter(p => 
      p.name.toLowerCase().includes(searchTerm) ||
      p.description.toLowerCase().includes(searchTerm)
    )
  }
  
  // 排序
  if (query.sort === 'price-asc') {
    filtered.sort((a, b) => a.price - b.price)
  } else if (query.sort === 'price-desc') {
    filtered.sort((a, b) => b.price - a.price)
  }
  
  // 分頁
  const page = Number(query.page) || 1
  const limit = Number(query.limit) || 20
  const start = (page - 1) * limit
  const end = start + limit
  const paginated = filtered.slice(start, end)
  
  return {
    data: paginated,
    meta: {
      total: filtered.length,
      page,
      limit,
      hasMore: end < filtered.length
    }
  }
})
```

---

## 除錯技巧

### Vue DevTools

1. 安裝瀏覽器擴充
2. 開啟開發者工具 → Vue 面板
3. 檢查元件狀態、Pinia stores、路由

### Nuxt DevTools

```bash
# 已在 nuxt.config.ts 啟用
# 開啟 http://localhost:3000 後按下 Shift + Alt + D
```

### Playwright Debug Mode

```bash
# 啟用 UI 模式
npx playwright test --ui

# 啟用 debug 模式
npx playwright test --debug
```

---

## 常見問題

### Q1: 如何處理圖片？

```vue
<template>
  <!-- 使用 NuxtImg 自動最佳化 -->
  <NuxtImg
    :src="product.image"
    :alt="product.name"
    width="300"
    height="300"
    format="webp"
    loading="lazy"
  />
</template>
```

### Q2: 如何實作頁面載入狀態？

```vue
<script setup lang="ts">
const { data: products, pending, error } = await useLazyAsyncData(
  'products',
  () => $fetch('/api/products')
)
</script>

<template>
  <div v-if="pending">載入中...</div>
  <div v-else-if="error">發生錯誤</div>
  <div v-else>{{ products }}</div>
</template>
```

### Q3: 如何實作會員路由守衛？

```typescript
// middleware/auth.ts
export default defineNuxtRouteMiddleware((to, from) => {
  const auth = useAuthStore()
  
  if (!auth.isAuthenticated) {
    return navigateTo('/auth/login')
  }
})

// pages/account/index.vue
<script setup lang="ts">
definePageMeta({
  middleware: 'auth'
})
</script>
```

---

## 效能檢查

### Lighthouse CI

```bash
# 安裝 Lighthouse CI
npm install -D @lhci/cli

# 執行檢查
npm run build
npx lhci autorun
```

### Bundle 分析

```bash
# 安裝分析工具
npm install -D nuxt-build-analyzer

# 分析建置結果
npm run build
npm run analyze
```

---

## 下一步

1. ✅ 閱讀 [data-model.md](./data-model.md) 了解資料結構
2. ✅ 閱讀 [contracts/](./contracts/) 了解 API 規格
3. 📝 執行 `/speckit.tasks` 產生詳細任務列表
4. 🚀 開始實作第一個 User Story（瀏覽熱門產品）

---

## 相關資源

- [Nuxt 3 官方文件](https://nuxt.com)
- [Vue 3 官方文件](https://vuejs.org)
- [Pinia 官方文件](https://pinia.vuejs.org)
- [Tailwind CSS 官方文件](https://tailwindcss.com)
- [Playwright 官方文件](https://playwright.dev)
- [Vitest 官方文件](https://vitest.dev)
