# 資料模型設計：購物網站完整流程

**日期**: 2025-10-25  
**專案**: 001-shopping-flow  
**版本**: 1.0.0

## 概述

本文件定義電商購物網站的核心實體關係、狀態管理架構與資料流設計。所有資料模型使用 TypeScript 定義，確保型別安全。前端使用 Pinia stores 管理全域狀態，localStorage/IndexedDB 實作資料持久化。

---

## 核心實體定義

### 1. Product（產品）

代表網站販售的商品，包含基本資訊、庫存與分類。

```typescript
// types/product.ts

export interface Product {
  id: string                    // 產品唯一識別碼，格式：prod-{number}
  name: string                  // 產品名稱（繁體中文）
  slug: string                  // URL 友善識別碼，如：wireless-earbuds
  description: string           // 產品詳細描述（支援 Markdown）
  shortDescription: string      // 首頁卡片用簡短描述（≤100 字元）
  price: number                 // 價格（新台幣，整數）
  originalPrice?: number        // 原價（用於顯示折扣）
  images: ProductImage[]        // 產品圖片陣列
  categoryId: string            // 所屬類別 ID
  category?: Category           // 類別物件（populated）
  stock: number                 // 庫存數量
  isFeatured: boolean           // 是否為熱門產品（首頁顯示）
  specs: ProductSpec[]          // 產品規格
  tags: string[]                // 標籤（如：新品、熱銷）
  rating: number                // 平均評分（1-5）
  reviewCount: number           // 評論數量
  createdAt: string             // ISO 8601 日期時間
  updatedAt: string
}

export interface ProductImage {
  url: string                   // 圖片 URL
  alt: string                   // 替代文字（無障礙）
  isPrimary: boolean            // 是否為主圖
}

export interface ProductSpec {
  label: string                 // 規格名稱，如：顏色、尺寸
  value: string                 // 規格值
}

// 產品列表查詢參數
export interface ProductQuery {
  categoryId?: string
  search?: string
  minPrice?: number
  maxPrice?: number
  sort?: 'price-asc' | 'price-desc' | 'newest' | 'popular'
  page?: number
  limit?: number
}

// 產品列表回應
export interface ProductListResponse {
  data: Product[]
  meta: {
    total: number
    page: number
    limit: number
    hasMore: boolean
  }
}
```

**驗證規則**:
- `name`: 必填，2-100 字元
- `price`: 必填，≥0
- `stock`: 必填，≥0
- `images`: 至少一張圖片
- `categoryId`: 必須存在於 categories 中

---

### 2. Category（產品類別）

採階層式分類結構，支援主類別與子類別。

```typescript
// types/category.ts

export interface Category {
  id: string                    // 類別唯一識別碼，格式：cat-{slug}
  name: string                  // 類別名稱（繁體中文）
  slug: string                  // URL 識別碼，如：electronics
  description?: string          // 類別描述
  image?: string                // 類別圖示
  parentId: string | null       // 父類別 ID，null 表示主類別
  children?: Category[]         // 子類別陣列（populated）
  productCount: number          // 該類別下的產品數量
  order: number                 // 顯示順序
  isActive: boolean             // 是否啟用
}

// 類別樹狀結構（用於導航）
export interface CategoryTree {
  main: Category[]              // 主類別陣列（parentId === null）
  childrenMap: Record<string, Category[]> // 子類別對應表
}
```

**範例階層**:
```
電子產品 (cat-electronics)
├── 耳機 (cat-headphones)
├── 手機 (cat-phones)
└── 平板 (cat-tablets)

服飾配件 (cat-fashion)
├── 男裝 (cat-men-clothing)
└── 女裝 (cat-women-clothing)

居家生活 (cat-home)
```

---

### 3. Cart（購物車）

購物車分為訪客購物車（localStorage）與會員購物車（server sync）。

```typescript
// types/cart.ts

export interface Cart {
  id?: string                   // 會員購物車 ID（訪客為 undefined）
  userId?: string               // 會員 ID（訪客為 undefined）
  items: CartItem[]             // 購物車項目
  subtotal: number              // 商品小計（自動計算）
  shipping: number              // 運費（滿 $1000 免運）
  discount: number              // 折扣金額
  total: number                 // 總計（自動計算）
  updatedAt: string             // 最後更新時間
}

export interface CartItem {
  id: string                    // 項目 ID（唯一）
  productId: string             // 產品 ID
  product?: Product             // 產品物件（populated）
  quantity: number              // 數量
  price: number                 // 加入購物車時的價格（快照）
  subtotal: number              // 小計 = price * quantity
  addedAt: string               // 加入時間
}

// 加入購物車 DTO
export interface AddToCartDto {
  productId: string
  quantity: number
}

// 更新購物車項目 DTO
export interface UpdateCartItemDto {
  quantity: number              // 新數量（0 表示移除）
}
```

**商業邏輯**:
```typescript
// 運費計算
function calculateShipping(subtotal: number): number {
  return subtotal >= 1000 ? 0 : 100
}

// 總計算
function calculateTotal(cart: Cart): number {
  const subtotal = cart.items.reduce((sum, item) => sum + item.subtotal, 0)
  const shipping = calculateShipping(subtotal)
  return subtotal + shipping - cart.discount
}

// 購物車合併邏輯（訪客→會員）
function mergeCartItems(guestItems: CartItem[], memberItems: CartItem[]): CartItem[] {
  const merged = [...memberItems]
  
  for (const guestItem of guestItems) {
    const existingIndex = merged.findIndex(
      item => item.productId === guestItem.productId
    )
    
    if (existingIndex >= 0) {
      // 相同商品：數量相加（不超過庫存）
      merged[existingIndex].quantity += guestItem.quantity
    } else {
      // 新商品：直接加入
      merged.push(guestItem)
    }
  }
  
  return merged
}
```

---

### 4. User（會員）

會員帳號資訊，包含認證與個人資料。

```typescript
// types/user.ts

export interface User {
  id: string                    // 會員唯一識別碼
  email: string                 // Email（唯一，用於登入）
  name: string                  // 姓名
  phone: string                 // 手機號碼
  avatar?: string               // 大頭照 URL
  addresses: Address[]          // 收件地址陣列
  defaultAddressId?: string     // 預設地址 ID
  createdAt: string
  updatedAt: string
}

// 註冊 DTO
export interface RegisterDto {
  email: string                 // 驗證：Email 格式
  password: string              // 驗證：≥8 字元，包含英文與數字
  name: string                  // 驗證：≥2 字元
  phone: string                 // 驗證：09xxxxxxxx 格式
}

// 登入 DTO
export interface LoginDto {
  email: string
  password: string
}

// 登入回應
export interface AuthResponse {
  user: User
  token: string                 // JWT token（前端 mock 模擬）
  expiresAt: string             // Token 過期時間
}

// 會員資料更新 DTO
export interface UpdateUserDto {
  name?: string
  phone?: string
  avatar?: string
}
```

**密碼驗證規則**（前端檢查）:
```typescript
const passwordSchema = z.string()
  .min(8, '密碼至少 8 個字元')
  .regex(/[A-Za-z]/, '密碼須包含英文字母')
  .regex(/\d/, '密碼須包含數字')
```

---

### 5. Address（收件地址）

會員的收件地址管理。

```typescript
// types/address.ts

export interface Address {
  id: string                    // 地址唯一識別碼
  userId: string                // 所屬會員 ID
  recipientName: string         // 收件人姓名
  phone: string                 // 收件人電話
  city: string                  // 城市（如：台北市）
  district: string              // 地區（如：信義區）
  address: string               // 詳細地址
  postalCode: string            // 郵遞區號
  isDefault: boolean            // 是否為預設地址
  createdAt: string
  updatedAt: string
}

// 新增/更新地址 DTO
export interface AddressDto {
  recipientName: string         // 驗證：≥2 字元
  phone: string                 // 驗證：09xxxxxxxx
  city: string                  // 驗證：必填
  district: string              // 驗證：必填
  address: string               // 驗證：≥5 字元
  postalCode: string            // 驗證：5 碼數字
  isDefault?: boolean
}
```

---

### 6. Order（訂單）

完成交易後建立的訂單記錄。

```typescript
// types/order.ts

export interface Order {
  id: string                    // 訂單編號，格式：ORD-{timestamp}
  userId: string                // 會員 ID
  user?: User                   // 會員物件（populated）
  items: OrderItem[]            // 訂單項目
  status: OrderStatus           // 訂單狀態
  paymentMethod: PaymentMethod  // 付款方式
  paymentStatus: PaymentStatus  // 付款狀態
  
  // 金額
  subtotal: number              // 商品小計
  shipping: number              // 運費
  discount: number              // 折扣
  total: number                 // 總計
  
  // 收件資訊（快照，獨立於 Address）
  shippingInfo: ShippingInfo
  
  // 物流
  trackingNumber?: string       // 物流追蹤號碼
  shippedAt?: string            // 出貨時間
  deliveredAt?: string          // 送達時間
  
  // 備註
  note?: string                 // 訂單備註
  
  createdAt: string             // 訂單建立時間
  updatedAt: string
}

export interface OrderItem {
  id: string
  orderId: string
  productId: string
  productName: string           // 產品名稱快照
  productImage: string          // 產品圖片快照
  price: number                 // 購買時價格快照
  quantity: number
  subtotal: number
}

export enum OrderStatus {
  PENDING = 'pending',          // 待付款
  PROCESSING = 'processing',    // 處理中
  SHIPPED = 'shipped',          // 已出貨
  DELIVERING = 'delivering',    // 配送中
  DELIVERED = 'delivered',      // 已送達
  COMPLETED = 'completed',      // 已完成
  CANCELLED = 'cancelled'       // 已取消
}

export enum PaymentMethod {
  CREDIT_CARD = 'credit-card',  // 信用卡
  ATM = 'atm',                  // ATM 轉帳
  COD = 'cod'                   // 貨到付款
}

export enum PaymentStatus {
  PENDING = 'pending',          // 待付款
  PAID = 'paid',                // 已付款
  FAILED = 'failed',            // 付款失敗
  REFUNDED = 'refunded'         // 已退款
}

export interface ShippingInfo {
  recipientName: string
  phone: string
  city: string
  district: string
  address: string
  postalCode: string
}

// 建立訂單 DTO
export interface CreateOrderDto {
  items: Array<{
    productId: string
    quantity: number
  }>
  paymentMethod: PaymentMethod
  shippingInfo: ShippingInfo
  note?: string
}

// 訂單查詢參數
export interface OrderQuery {
  status?: OrderStatus
  startDate?: string
  endDate?: string
  page?: number
  limit?: number
}
```

**訂單狀態流轉**:
```
pending → processing → shipped → delivering → delivered → completed
              ↓
          cancelled
```

---

## 狀態管理架構（Pinia Stores）

### 1. Cart Store

```typescript
// stores/cart.ts
import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', () => {
  // State
  const items = ref<CartItem[]>([])
  const isLoading = ref(false)
  const error = ref<string | null>(null)
  
  // Getters
  const itemCount = computed(() => 
    items.value.reduce((sum, item) => sum + item.quantity, 0)
  )
  
  const subtotal = computed(() =>
    items.value.reduce((sum, item) => sum + item.subtotal, 0)
  )
  
  const shipping = computed(() =>
    subtotal.value >= 1000 ? 0 : 100
  )
  
  const total = computed(() =>
    subtotal.value + shipping.value
  )
  
  const cart = computed<Cart>(() => ({
    items: items.value,
    subtotal: subtotal.value,
    shipping: shipping.value,
    discount: 0,
    total: total.value,
    updatedAt: new Date().toISOString()
  }))
  
  // Actions
  async function addItem(dto: AddToCartDto) {
    isLoading.value = true
    error.value = null
    
    try {
      // 檢查產品存在與庫存
      const product = await $fetch<Product>(`/api/products/${dto.productId}`)
      
      if (product.stock < dto.quantity) {
        throw new Error('庫存不足')
      }
      
      // 檢查是否已在購物車中
      const existingIndex = items.value.findIndex(
        item => item.productId === dto.productId
      )
      
      if (existingIndex >= 0) {
        // 更新數量
        const newQuantity = items.value[existingIndex].quantity + dto.quantity
        
        if (newQuantity > product.stock) {
          throw new Error('超過庫存數量')
        }
        
        items.value[existingIndex].quantity = newQuantity
        items.value[existingIndex].subtotal = newQuantity * items.value[existingIndex].price
      } else {
        // 新增項目
        items.value.push({
          id: `item-${Date.now()}`,
          productId: dto.productId,
          product,
          quantity: dto.quantity,
          price: product.price,
          subtotal: product.price * dto.quantity,
          addedAt: new Date().toISOString()
        })
      }
      
      // 儲存至 localStorage（訪客）或 sync server（會員）
      await persistCart()
      
      useToast().success('已加入購物車')
    } catch (e) {
      error.value = (e as Error).message
      useToast().error(error.value)
      throw e
    } finally {
      isLoading.value = false
    }
  }
  
  async function updateItem(itemId: string, quantity: number) {
    const index = items.value.findIndex(item => item.id === itemId)
    
    if (index < 0) {
      throw new Error('項目不存在')
    }
    
    if (quantity === 0) {
      await removeItem(itemId)
      return
    }
    
    // 檢查庫存
    const product = items.value[index].product!
    if (quantity > product.stock) {
      throw new Error('超過庫存數量')
    }
    
    items.value[index].quantity = quantity
    items.value[index].subtotal = quantity * items.value[index].price
    
    await persistCart()
  }
  
  async function removeItem(itemId: string) {
    items.value = items.value.filter(item => item.id !== itemId)
    await persistCart()
    useToast().info('已移除商品')
  }
  
  async function clearCart() {
    items.value = []
    await persistCart()
  }
  
  async function mergeGuestCart(guestItems: CartItem[]) {
    // 合併訪客購物車至會員購物車
    items.value = mergeCartItems(guestItems, items.value)
    await persistCart()
  }
  
  async function persistCart() {
    const authStore = useAuthStore()
    
    if (authStore.isAuthenticated) {
      // 會員：同步至 server
      await $fetch('/api/cart', {
        method: 'PUT',
        body: { items: items.value }
      })
    } else {
      // 訪客：儲存至 localStorage
      guestCartStorage.save(items.value)
    }
  }
  
  async function loadCart() {
    const authStore = useAuthStore()
    
    if (authStore.isAuthenticated) {
      // 會員：從 server 載入
      const response = await $fetch<Cart>('/api/cart')
      items.value = response.items
    } else {
      // 訪客：從 localStorage 載入
      items.value = guestCartStorage.load() || []
    }
  }
  
  return {
    // State
    items,
    isLoading,
    error,
    // Getters
    itemCount,
    subtotal,
    shipping,
    total,
    cart,
    // Actions
    addItem,
    updateItem,
    removeItem,
    clearCart,
    mergeGuestCart,
    loadCart
  }
})
```

### 2. Auth Store

```typescript
// stores/auth.ts
export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = ref<string | null>(null)
  const isLoading = ref(false)
  
  const isAuthenticated = computed(() => !!user.value && !!token.value)
  
  async function register(dto: RegisterDto) {
    isLoading.value = true
    
    try {
      const response = await $fetch<AuthResponse>('/api/auth/register', {
        method: 'POST',
        body: dto
      })
      
      user.value = response.user
      token.value = response.token
      
      // 儲存 token
      localStorage.setItem('auth-token', response.token)
      
      useToast().success('註冊成功')
      
      // 合併訪客購物車
      await useCartStore().mergeGuestCart(guestCartStorage.load() || [])
      
      navigateTo('/')
    } catch (e) {
      useToast().error((e as Error).message)
      throw e
    } finally {
      isLoading.value = false
    }
  }
  
  async function login(dto: LoginDto) {
    // 類似 register 邏輯
  }
  
  async function logout() {
    user.value = null
    token.value = null
    localStorage.removeItem('auth-token')
    useCartStore().clearCart()
    navigateTo('/auth/login')
  }
  
  return {
    user,
    token,
    isAuthenticated,
    isLoading,
    register,
    login,
    logout
  }
})
```

### 3. Products Store

```typescript
// stores/products.ts
export const useProductsStore = defineStore('products', () => {
  const cache = ref<Map<string, Product>>(new Map())
  const categories = ref<Category[]>([])
  
  async function fetchProduct(id: string): Promise<Product> {
    // 檢查快取
    if (cache.value.has(id)) {
      return cache.value.get(id)!
    }
    
    // 從 API 取得
    const product = await $fetch<Product>(`/api/products/${id}`)
    cache.value.set(id, product)
    
    return product
  }
  
  async function fetchProducts(query: ProductQuery = {}) {
    return await $fetch<ProductListResponse>('/api/products', { query })
  }
  
  async function fetchCategories() {
    if (categories.value.length > 0) {
      return categories.value
    }
    
    categories.value = await $fetch<Category[]>('/api/categories')
    return categories.value
  }
  
  return {
    cache,
    categories,
    fetchProduct,
    fetchProducts,
    fetchCategories
  }
})
```

---

## 資料流設計

### 1. 購物流程資料流

```
使用者操作 → Component → Composable → Store → API → Mock Data
                                         ↓
                                    localStorage/IndexedDB
```

**範例：加入購物車**
```typescript
// pages/products/[id].vue
const { addToCart } = useCart()

const handleAddToCart = async () => {
  await addToCart({
    productId: product.value.id,
    quantity: quantity.value
  })
}

// ↓ composables/useCart.ts
export const useCart = () => {
  const store = useCartStore()
  
  const addToCart = async (dto: AddToCartDto) => {
    await store.addItem(dto)
  }
  
  return { addToCart, ... }
}

// ↓ stores/cart.ts
async function addItem(dto: AddToCartDto) {
  // 1. 驗證庫存
  // 2. 更新 state
  // 3. 持久化（localStorage 或 API）
  // 4. Toast 通知
}
```

### 2. 會員登入資料流

```
登入表單 → Auth Store → Mock API → 驗證成功
                         ↓
               儲存 token + user 至 localStorage
                         ↓
               觸發購物車合併（guest → member）
                         ↓
               導航至首頁
```

---

## 資料驗證策略

### 前端驗證（即時反饋）
使用 Zod schema 定義於 `utils/validation.ts`，由 Vee-Validate 整合。

### Mock API 驗證（模擬後端）
Mock API endpoints 也執行相同驗證，確保 contract 一致性。

```typescript
// server/api/auth/register.post.ts
export default defineEventHandler(async (event) => {
  const body = await readBody(event)
  
  // 驗證
  const result = registerSchema.safeParse(body)
  if (!result.success) {
    throw createError({
      statusCode: 400,
      message: result.error.issues[0].message
    })
  }
  
  // 檢查 Email 是否已註冊
  const users = await getUsers()
  if (users.some(u => u.email === body.email)) {
    throw createError({
      statusCode: 409,
      message: '此 Email 已被註冊'
    })
  }
  
  // 建立使用者
  const user = createUser(result.data)
  const token = generateToken(user.id)
  
  return { user, token, expiresAt: ... }
})
```

---

## 效能最佳化

### 1. 產品資料快取
Products Store 維護 Map-based 快取，避免重複請求。

### 2. 購物車樂觀更新
UI 立即更新，背景持久化，失敗時 rollback。

### 3. 訂單列表分頁
避免一次載入所有訂單，使用 cursor-based pagination。

---

## 下一步：Contracts 定義

接下來將產出 `contracts/` 目錄，包含：
- `products.yaml`: 產品相關 API OpenAPI 規格
- `cart.yaml`: 購物車相關 API
- `auth.yaml`: 會員認證相關 API
- `orders.yaml`: 訂單相關 API

所有 TypeScript types 已在本文件定義完成，可直接用於實作。
