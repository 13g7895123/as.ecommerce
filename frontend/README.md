# Frontend - Nuxt 3 Monorepo

使用 Nuxt 3 + Bun 建立的多品牌電商前端應用。

## 🏗️ 架構設計

### Monorepo 結構
```
frontend/
├── apps/                    # 各品牌獨立應用
│   ├── fashion/            # S.Collection 時尚網站
│   ├── home/               # H.Collection 家居網站
│   └── general/            # G.Collection 雜貨網站
│
├── layers/                  # Nuxt Layers（共用層）
│   ├── base/               # 基礎層
│   │   ├── components/     # 共用元件
│   │   │   ├── layout/     # AppHeader, AppFooter
│   │   │   ├── product/    # ProductCard, ProductGrid
│   │   │   └── ui/         # GeneralCarousel, Button
│   │   ├── composables/    # 共用組合式函數
│   │   │   ├── useCart.ts
│   │   │   ├── useAuth.ts
│   │   │   └── useCurrency.ts
│   │   ├── types/          # TypeScript 類型定義
│   │   ├── utils/          # 工具函數
│   │   └── assets/         # 共用樣式
│   └── auth/               # 認證層（規劃中）
│
└── packages/                # 共用套件（規劃中）
    ├── shared-types/       # 共用類型
    └── api-client/         # API 客戶端
```

### 技術棧
- **Framework**: Nuxt 3.21.1
- **Runtime**: Bun 1.3.2 (超快的 JavaScript 運行環境)
- **Monorepo**: Turborepo 2.8.6
- **State**: Pinia (Vue 官方狀態管理)
- **Styling**: Tailwind CSS + 自定義 CSS
- **TypeScript**: 5.9.3 (嚴格模式)
- **Type Checking**: vue-tsc
- **Utils**: VueUse (Vue 組合式工具集)
- **Icons**: Font Awesome 6.4.0

## 🚀 快速開始

### 安裝依賴
```bash
# 確保已安裝 Bun
curl -fsSL https://bun.sh/install | bash

# 安裝專案依賴
bun install
```

### 開發模式

```bash
# 同時運行所有應用
bun run dev

# 運行單一應用
bun run dev:fashion    # http://localhost:3000
bun run dev:home       # http://localhost:3001
bun run dev:general    # http://localhost:3002
```

### 建置生產版本

```bash
# 建置所有應用
bun run build

# 建置單一應用
turbo run build --filter fashion
turbo run build --filter home
turbo run build --filter general
```

### 生成靜態網站 (SSG)

```bash
# 生成所有應用
bun run generate

# 生成單一應用
cd apps/fashion && bun run generate
```

### 預覽生產版本

```bash
cd apps/fashion && bun run preview
```

## 📦 應用說明

### Fashion - S.Collection
**端口**: 3000  
**主題**: 奢華黑金  
**配色**: #333 + #c49b63  
**商品**: 服飾、配件、鞋包

### Home - H.Collection
**端口**: 3001  
**主題**: 溫暖家居  
**配色**: #8B4513 + #F5F5DC  
**商品**: 傢俱、裝飾、寢具

### General - G.Collection
**端口**: 3002 ✅ (已完成)  
**主題**: 自然清新  
**配色**: #556B2F + #D4A574  
**商品**: 廚房、文具、收納

## 🧩 共用元件

### Layout 元件
- **AppHeader**: 導航列，支援自定義品牌名稱和連結
- **AppFooter**: 頁尾，包含聯絡資訊和社群連結

### Product 元件
- **ProductCard**: 商品卡片，顯示圖片、價格、折扣
- **ProductGrid**: 商品網格，響應式佈局

### UI 元件
- **GeneralCarousel**: 輪播圖，支援自動播放、拖曳、無限循環

## 🔧 Composables

### useCart
購物車狀態管理
```typescript
const cart = useCart();

cart.addItem(product, quantity);
cart.removeItem(productId);
cart.updateQuantity(productId, quantity);
cart.clearCart();

// 響應式數據
cart.items          // 購物車商品列表
cart.totalItems     // 總數量
cart.totalPrice     // 總金額
```

### useAuth
認證狀態管理
```typescript
const auth = useAuth();

await auth.login(email, password);
await auth.register(name, email, password);
await auth.updateProfile(userData);
auth.logout();

// 響應式數據
auth.user               // 當前用戶
auth.isAuthenticated    // 是否已登入
auth.loading            // 載入狀態
```

### useCurrency
金額格式化工具
```typescript
const { formatPrice, formatNumber, calculateDiscount } = useCurrency();

formatPrice(1280);                    // "NT$ 1,280"
calculateDiscount(1000, 20);          // 800
```

## 🎨 品牌客製化

每個應用可以透過 `nuxt.config.ts` 設定品牌特色：

```typescript
export default defineNuxtConfig({
  extends: ['../../layers/base'],
  
  runtimeConfig: {
    public: {
      siteType: 'general',
      brandName: 'G.Collection',
      brandSlogan: '優質生活，從細節開始',
      primaryColor: '#556B2F',
      accentColor: '#D4A574',
    },
  },
  
  css: ['~/assets/css/general.css'],
});
```

## 🔄 Turborepo 指令

```bash
# 查看建置圖
turbo run build --graph

# 清理所有快取
turbo clean

# 強制重新建置
turbo run build --force

# 只建置特定應用
turbo run build --filter=general

# 平行執行任務
turbo run dev --parallel
```

## 📱 響應式設計

所有應用都支援：
- 桌面 (1200px+)
- 平板 (768px - 1199px)
- 手機 (< 768px)

使用 Tailwind CSS 斷點：
- `sm:` 640px
- `md:` 768px
- `lg:` 1024px
- `xl:` 1280px
- `2xl:` 1536px

## 🧪 開發建議

### VS Code 擴充
- Vue - Official (Volar)
- Tailwind CSS IntelliSense
- TypeScript Vue Plugin
- ESLint
- Prettier

### 開發流程
1. 在 `layers/base/components/` 建立共用元件
2. 在各應用的 `pages/` 建立頁面
3. 使用 `composables/` 管理狀態和邏輯
4. 在 `assets/css/` 自定義品牌樣式

### 最佳實踐
- ✅ 使用 TypeScript 類型定義
- ✅ 元件使用 `<script setup lang="ts">`
- ✅ 使用組合式 API (Composition API)
- ✅ 共用邏輯抽取到 composables
- ✅ 使用 Tailwind 優先，必要時自定義 CSS

## 🚦 效能優化

### Bun 優勢
- 安裝速度提升 2-10 倍
- 啟動速度更快
- 更低的記憶體佔用

### Nuxt 3 優化
- 自動程式碼分割
- 元件懶載入
- 圖片優化 (@nuxt/image)
- SSR/SSG 支援

### Turborepo 快取
- 本地任務快取
- 遠端快取支援（可配置）
- 智慧型依賴追蹤

## 🔜 待辦事項

- [ ] 完成 Fashion 應用
- [ ] 完成 Home 應用
- [ ] 實作 Auth Layer
- [ ] 建立 API Client
- [ ] 加入單元測試
- [ ] 加入 E2E 測試
- [ ] 設定 CI/CD
- [ ] 效能監控

## 📚 相關資源

- [Nuxt 3 文件](https://nuxt.com/)
- [Bun 文件](https://bun.sh/)
- [Turborepo 文件](https://turbo.build/repo)
- [Pinia 文件](https://pinia.vuejs.org/)
- [Tailwind CSS](https://tailwindcss.com/)
- [VueUse](https://vueuse.org/)
