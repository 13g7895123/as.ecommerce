# 研究報告：Nuxt 3 電商前端架構

**日期**: 2025-10-25  
**專案**: 購物網站完整流程  
**目的**: 為純前端電商應用建立技術決策基礎，解決架構選型與最佳實踐問題

## 技術決策總覽

本研究報告涵蓋 Nuxt 3 電商專案的關鍵技術決策，包含狀態管理、資料持久化、Mock API 策略、效能最佳化與測試架構。所有決策皆以前端憲法要求為基準（效能、測試覆蓋率、使用者體驗、繁體中文支援）。

---

## 1. 狀態管理方案

### Decision: Pinia + Composables 混合策略

**選擇理由**:
- **Pinia** 作為全域狀態管理（購物車、會員認證、產品快取）
- **Composables** 處理元件層級邏輯與可重用功能
- Pinia 為 Vue 3 官方推薦，提供 TypeScript 完整支援與 DevTools 整合
- Composables 符合 Vue 3 Composition API 設計哲學，降低學習曲線

**替代方案考量**:
- ❌ **Vuex**: 已被 Pinia 取代，Nuxt 3 官方不再推薦
- ❌ **純 Composables**: 缺乏跨元件狀態持久化機制，難以實作全域購物車
- ❌ **Zustand/Jotai**: 非 Vue 生態系工具，TypeScript 類型推斷較弱

**實作細節**:
```typescript
// stores/cart.ts - 購物車全域狀態
export const useCartStore = defineStore('cart', () => {
  const items = ref<CartItem[]>([])
  const isLoading = ref(false)
  
  // 支援 localStorage 持久化
  const { data } = useLocalStorage('guest-cart', items)
  
  return { items, isLoading, addItem, removeItem, syncWithServer }
})

// composables/useProducts.ts - 產品查詢邏輯
export const useProducts = () => {
  const productsStore = useProductsStore()
  
  const fetchProducts = async (categoryId?: string) => {
    // 實作快取策略與錯誤處理
  }
  
  return { products, isLoading, error, fetchProducts }
}
```

---

## 2. 前端資料持久化策略

### Decision: localStorage (訪客) + IndexedDB (會員) 分層架構

**選擇理由**:
- **localStorage**: 用於訪客購物車，簡單輕量，滿足 7 天保留需求
- **IndexedDB**: 用於會員訂單歷史與複雜查詢，支援 5MB+ 資料量
- 符合規格要求：訪客與會員資料分離處理
- 登入時觸發購物車合併邏輯（localStorage → server sync）

**替代方案考量**:
- ❌ **純 Cookie**: 容量限制 4KB，無法儲存完整購物車
- ❌ **sessionStorage**: 關閉瀏覽器即清除，不符合保留需求
- ❌ **純記憶體狀態**: 重新整理頁面即遺失資料

**實作細節**:
```typescript
// utils/storage.ts
export const guestCartStorage = {
  save: (cart: CartItem[]) => {
    localStorage.setItem('guest-cart', JSON.stringify(cart))
    localStorage.setItem('cart-expires', Date.now() + 7 * 24 * 60 * 60 * 1000)
  },
  load: (): CartItem[] | null => {
    const expires = localStorage.getItem('cart-expires')
    if (expires && Date.now() > Number(expires)) {
      localStorage.removeItem('guest-cart')
      return null
    }
    return JSON.parse(localStorage.getItem('guest-cart') || 'null')
  }
}

// composables/useAuth.ts - 登入時合併購物車
const mergeGuestCart = async () => {
  const guestCart = guestCartStorage.load()
  if (guestCart) {
    await cartStore.mergeItems(guestCart) // 相同商品數量相加
    guestCartStorage.clear()
  }
}
```

---

## 3. Mock API 實作策略

### Decision: Nuxt Server Routes + MSW (開發環境備援)

**選擇理由**:
- **Nuxt Server Routes** (`/server/api/*.ts`): 內建支援，零配置，支援 hot reload
- **MSW (Mock Service Worker)**: 可選備援方案，用於獨立測試或無 Nuxt server 場景
- Server routes 提供真實 HTTP 請求體驗，方便模擬延遲與錯誤
- 支援 TypeScript 型別共用，減少 contract 不一致問題

**替代方案考量**:
- ❌ **純前端 mock data**: 無法模擬非同步與錯誤情境
- ❌ **json-server**: 需額外進程，配置複雜，不支援自訂邏輯
- ❌ **MirageJS**: 學習曲線高，與 Nuxt 整合度低

**實作細節**:
```typescript
// server/api/products/index.get.ts
import { products } from '~/server/mock/products'

export default defineEventHandler(async (event) => {
  const query = getQuery(event)
  const categoryId = query.category as string | undefined
  
  // 模擬網路延遲
  await new Promise(resolve => setTimeout(resolve, 300))
  
  let filtered = products
  if (categoryId) {
    filtered = products.filter(p => p.categoryId === categoryId)
  }
  
  return {
    data: filtered,
    meta: { total: filtered.length }
  }
})

// server/mock/products.ts
export const products: Product[] = [
  {
    id: 'prod-001',
    name: '無線藍牙耳機',
    price: 2990,
    stock: 50,
    categoryId: 'cat-electronics',
    images: ['/images/product-001.jpg'],
    description: '高音質主動降噪，續航力 30 小時'
  },
  // ... 100+ 產品資料
]
```

---

## 4. 效能最佳化策略

### Decision: 靜態生成 + 動態島嶼 (Islands Architecture)

**選擇理由**:
- **SSG (Static Site Generation)**: 首頁與產品頁面預先生成 HTML，首次載入 ≤1 秒
- **Islands Architecture**: 購物車、會員狀態等互動元件採用客戶端渲染
- 符合 Core Web Vitals 要求：LCP ≤2.5s, FID ≤100ms
- Nuxt 3 內建支援，使用 `nuxt generate` 指令

**替代方案考量**:
- ❌ **純 SPA**: 首次載入慢，SEO 不佳，不符合 ≤2 秒要求
- ❌ **SSR**: 需要 Node.js server，違反純前端要求
- ❌ **純靜態 HTML**: 缺乏互動性，無法實作購物車

**實作細節**:
```typescript
// nuxt.config.ts
export default defineNuxtConfig({
  ssr: true, // 開啟 SSR 用於 generate
  
  nitro: {
    prerender: {
      crawlLinks: true,
      routes: [
        '/',
        '/products',
        '/cart',
        '/auth/login',
        // 動態產品頁面透過 crawler 自動發現
      ]
    }
  },
  
  experimental: {
    componentIslands: true // 啟用 Islands Architecture
  },
  
  // 圖片最佳化
  image: {
    formats: ['webp', 'avif', 'jpeg'],
    screens: {
      xs: 320,
      sm: 640,
      md: 768,
      lg: 1024,
      xl: 1280
    }
  }
})

// pages/index.vue - 首頁靜態生成
<script setup lang="ts">
// 使用 useAsyncData 在 generate 時抓取資料
const { data: products } = await useAsyncData('home-products', () => 
  $fetch('/api/products?featured=true')
)
</script>
```

---

## 5. 圖片最佳化策略

### Decision: Nuxt Image + 響應式圖片 + 延遲載入

**選擇理由**:
- **@nuxt/image** 自動產生多種尺寸與格式（WebP/AVIF）
- 響應式圖片減少行動裝置頻寬消耗
- 延遲載入首屏外圖片，提升 LCP 指標
- 符合效能要求：3G 網路 2 秒內載入

**替代方案考量**:
- ❌ **原生 `<img>`**: 無自動最佳化，需手動處理多格式
- ❌ **Cloudinary**: 第三方服務，增加依賴，非純前端
- ❌ **純 CSS background**: 無法實作 lazy loading

**實作細節**:
```vue
<!-- components/product/ProductCard.vue -->
<template>
  <NuxtImg
    :src="product.image"
    :alt="product.name"
    width="300"
    height="300"
    format="webp"
    loading="lazy"
    placeholder
    sizes="sm:100vw md:50vw lg:33vw"
  />
</template>

<style>
/* Skeleton placeholder */
img[loading] {
  background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
  background-size: 200% 100%;
  animation: loading 1.5s infinite;
}
</style>
```

---

## 6. 表單驗證策略

### Decision: Vee-Validate + Zod Schema

**選擇理由**:
- **Vee-Validate**: Vue 3 官方推薦表單驗證庫，支援 Composition API
- **Zod**: TypeScript-first schema 定義，型別安全與執行期驗證合一
- 即時驗證與錯誤訊息符合 UX 要求
- 統一的驗證邏輯可重用於前後端（mock API 也驗證）

**替代方案考量**:
- ❌ **VueUse useForm**: 功能較少，不支援複雜驗證
- ❌ **手寫驗證**: 難以維護，容易產生不一致
- ❌ **Yup**: Zod 提供更好的 TypeScript 推斷

**實作細節**:
```typescript
// utils/validation.ts
import { z } from 'zod'

export const loginSchema = z.object({
  email: z.string().email('請輸入有效的 Email'),
  password: z.string().min(8, '密碼至少 8 個字元')
})

export const registerSchema = loginSchema.extend({
  name: z.string().min(2, '姓名至少 2 個字元'),
  phone: z.string().regex(/^09\d{8}$/, '請輸入有效的手機號碼'),
  password: z.string()
    .min(8, '密碼至少 8 個字元')
    .regex(/[A-Za-z]/, '密碼須包含英文字母')
    .regex(/\d/, '密碼須包含數字')
})

// components/user/LoginForm.vue
<script setup lang="ts">
import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'

const { handleSubmit, errors } = useForm({
  validationSchema: toTypedSchema(loginSchema)
})

const onSubmit = handleSubmit(async (values) => {
  await authStore.login(values)
})
</script>
```

---

## 7. 測試架構

### Decision: Vitest (Unit) + Playwright (E2E) + Testing Library (Component)

**選擇理由**:
- **Vitest**: Vite 原生整合，速度快，與 Jest API 相容，支援 TypeScript
- **Playwright**: 跨瀏覽器測試，錄製功能強大，CI/CD 友善
- **Vue Testing Library**: 注重使用者行為測試，避免實作細節耦合
- 組合滿足 ≥90% 覆蓋率與 TDD workflow 要求

**替代方案考量**:
- ❌ **Jest**: 需額外配置 ESM，Vitest 開箱即用
- ❌ **Cypress**: 較重，啟動慢，Playwright 效能更好
- ❌ **Enzyme**: 僅支援 Vue 2，不支援 Composition API

**實作細節**:
```typescript
// tests/unit/composables/useCart.spec.ts
import { describe, it, expect, beforeEach } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useCart } from '~/composables/useCart'

describe('useCart', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
  })
  
  it('should add item to cart', async () => {
    const { addToCart, cartItems } = useCart()
    
    await addToCart({ productId: 'prod-001', quantity: 2 })
    
    expect(cartItems.value).toHaveLength(1)
    expect(cartItems.value[0].quantity).toBe(2)
  })
  
  it('should merge duplicate items', async () => {
    const { addToCart, cartItems } = useCart()
    
    await addToCart({ productId: 'prod-001', quantity: 2 })
    await addToCart({ productId: 'prod-001', quantity: 3 })
    
    expect(cartItems.value).toHaveLength(1)
    expect(cartItems.value[0].quantity).toBe(5)
  })
})

// tests/e2e/shopping-flow.spec.ts
import { test, expect } from '@playwright/test'

test.describe('購物流程', () => {
  test('使用者可以完成從瀏覽到結帳的完整流程', async ({ page }) => {
    // 1. 訪問首頁
    await page.goto('/')
    await expect(page.locator('h1')).toContainText('熱門產品')
    
    // 2. 點擊產品
    await page.click('[data-testid="product-card"]:first-child')
    await expect(page).toHaveURL(/\/products\//)
    
    // 3. 加入購物車
    await page.click('[data-testid="add-to-cart"]')
    await expect(page.locator('[data-testid="cart-badge"]')).toContainText('1')
    
    // 4. 前往結帳
    await page.click('[data-testid="cart-link"]')
    await expect(page).toHaveURL('/cart')
    await page.click('[data-testid="checkout-button"]')
    
    // 5. 填寫資料並完成訂單
    await page.fill('[name="name"]', '測試使用者')
    await page.fill('[name="phone"]', '0912345678')
    await page.fill('[name="address"]', '台北市信義區市府路1號')
    await page.click('[data-testid="submit-order"]')
    
    await expect(page).toHaveURL('/checkout/success')
    await expect(page.locator('h1')).toContainText('訂單完成')
  })
})
```

---

## 8. 無障礙設計實作

### Decision: 語意化 HTML + ARIA labels + Focus Management

**選擇理由**:
- 符合 WCAG 2.1 AA 標準要求
- 使用 `<button>`, `<nav>`, `<main>` 等語意標籤
- 所有互動元件提供 ARIA labels
- Modal 與 Toast 實作 focus trap

**實作細節**:
```vue
<!-- components/base/BaseButton.vue -->
<template>
  <button
    :type="type"
    :aria-label="ariaLabel"
    :aria-disabled="disabled"
    :disabled="disabled"
    @click="handleClick"
  >
    <span v-if="loading" aria-hidden="true" class="spinner" />
    <span :class="{ 'sr-only': loading }">
      <slot />
    </span>
  </button>
</template>

<!-- components/base/BaseModal.vue -->
<script setup lang="ts">
import { useFocusTrap } from '@vueuse/integrations/useFocusTrap'

const modalRef = ref<HTMLElement>()
const { activate, deactivate } = useFocusTrap(modalRef)

onMounted(() => activate())
onUnmounted(() => deactivate())
</script>
```

---

## 9. 錯誤處理與 Loading 狀態

### Decision: 統一錯誤邊界 + Toast 通知 + Skeleton Loaders

**選擇理由**:
- 統一的錯誤處理避免重複程式碼
- Toast 提供非侵入式的即時反饋
- Skeleton loaders 提升感知效能（避免空白畫面）
- 符合 UX consistency 要求

**實作細節**:
```typescript
// composables/useToast.ts
export const useToast = () => {
  const toasts = ref<Toast[]>([])
  
  const showToast = (message: string, type: 'success' | 'error' | 'info') => {
    const id = Date.now()
    toasts.value.push({ id, message, type })
    setTimeout(() => {
      toasts.value = toasts.value.filter(t => t.id !== id)
    }, 5000)
  }
  
  return { toasts, showToast, success, error, info }
}

// composables/useAsyncState.ts
export const useAsyncState = <T>(
  fetcher: () => Promise<T>,
  initialValue: T
) => {
  const data = ref<T>(initialValue)
  const error = ref<Error | null>(null)
  const isLoading = ref(false)
  
  const execute = async () => {
    isLoading.value = true
    error.value = null
    
    try {
      data.value = await fetcher()
    } catch (e) {
      error.value = e as Error
      useToast().error('載入失敗，請稍後再試')
    } finally {
      isLoading.value = false
    }
  }
  
  return { data, error, isLoading, execute }
}
```

---

## 10. CI/CD 與部署策略

### Decision: GitHub Actions + Netlify/Vercel

**選擇理由**:
- GitHub Actions 執行測試與 linting
- Netlify/Vercel 自動部署靜態站點
- Preview deployments 用於 PR review
- 支援環境變數管理

**實作細節**:
```yaml
# .github/workflows/ci.yml
name: CI

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
        with:
          node-version: 20
          cache: 'npm'
      
      - run: npm ci
      - run: npm run lint
      - run: npm run test:unit
      - run: npm run test:e2e
      
      - name: Upload coverage
        uses: codecov/codecov-action@v3
        with:
          files: ./coverage/coverage-final.json

  build:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      - uses: actions/setup-node@v4
      - run: npm ci
      - run: npm run generate
      
      - name: Lighthouse CI
        uses: treosh/lighthouse-ci-action@v10
        with:
          urls: |
            http://localhost:3000/
            http://localhost:3000/products
          uploadArtifacts: true
```

---

## 總結與下一步

### 已解決的技術問題
✅ 狀態管理架構（Pinia + Composables）  
✅ 資料持久化策略（localStorage + IndexedDB）  
✅ Mock API 實作方案（Nuxt Server Routes）  
✅ 效能最佳化策略（SSG + Islands）  
✅ 測試架構（Vitest + Playwright）  
✅ 無障礙設計實作（WCAG 2.1 AA）  
✅ 錯誤處理與 Loading UX

### 技術堆疊最終確認
- **Framework**: Nuxt 3.x (latest)
- **State**: Pinia 2.x + VueUse composables
- **Styling**: Tailwind CSS 3.x + Headless UI
- **Testing**: Vitest + Playwright + Testing Library
- **Validation**: Vee-Validate + Zod
- **Build**: Vite 5.x
- **Deployment**: Netlify/Vercel (靜態站點)

### Phase 1 產出預覽
接下來的 Phase 1 將產出：
1. **data-model.md**: 實體關係定義與狀態管理設計
2. **contracts/**: Mock API 規格（OpenAPI/TypeScript types）
3. **quickstart.md**: 開發環境設定與執行指南
