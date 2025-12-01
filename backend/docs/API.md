# 電子商務 API 文件

**版本**: 1.0.0  
**更新日期**: 2025-12-01  
**Base URL**: `/api`

---

## 目錄

1. [通用規範](#1-通用規範)
2. [認證模組 (Auth)](#2-認證模組-auth)
3. [商品模組 (Products)](#3-商品模組-products)
4. [訂單模組 (Orders)](#4-訂單模組-orders)
5. [使用者模組 (User)](#5-使用者模組-user)
6. [錯誤代碼對照表](#6-錯誤代碼對照表)

---

## 1. 通用規範

### 1.1 請求格式

- **Content-Type**: `application/json`
- **字元編碼**: UTF-8
- **時間格式**: ISO 8601 (e.g., `2025-12-01T14:30:00Z`)

### 1.2 認證方式

需要認證的 API 必須在 Header 中提供 Bearer Token：

```
Authorization: Bearer <token>
```

### 1.3 成功回應格式

成功回應直接返回資料內容：

```json
{
  "id": "user_123",
  "email": "user@example.com",
  ...
}
```

或陣列格式（列表類型）：

```json
{
  "products": [...],
  "total": 100,
  "page": 1,
  "limit": 20,
  "hasMore": true
}
```

### 1.4 錯誤回應格式

```json
{
  "statusCode": 400,
  "statusMessage": "錯誤訊息描述",
  "data": null
}
```

### 1.5 驗證錯誤回應格式

```json
{
  "statusCode": 422,
  "statusMessage": "驗證失敗",
  "data": {
    "email": "請輸入有效的電子郵件格式",
    "password": "密碼至少需要 8 個字元"
  }
}
```

---

## 2. 認證模組 (Auth)

### 2.1 會員註冊

註冊新會員帳號，成功後自動登入並返回 Token。

- **URL**: `POST /api/auth/register`
- **認證**: 不需要

#### Request Body

| 欄位 | 類型 | 必填 | 說明 |
|------|------|------|------|
| email | string | ✅ | 電子郵件，需為有效格式且未被註冊 |
| password | string | ✅ | 密碼，至少 8 個字元 |
| name | string | ✅ | 姓名，1-100 個字元 |
| phone | string | ✅ | 電話號碼，10-20 個字元 |

#### 請求範例

```bash
curl -X POST http://localhost/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123",
    "name": "王小明",
    "phone": "0912345678"
  }'
```

#### 成功回應 (200 OK)

```json
{
  "user": {
    "id": "user_a1b2c3d4e5f6g7h8",
    "email": "user@example.com",
    "name": "王小明",
    "phone": "0912345678",
    "address": null,
    "createdAt": "2025-12-01T14:30:00+00:00",
    "updatedAt": "2025-12-01T14:30:00+00:00"
  },
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

#### 錯誤回應

| 狀態碼 | 說明 |
|--------|------|
| 422 | 驗證失敗（欄位格式錯誤、Email 已被註冊等） |
| 500 | 伺服器錯誤 |

---

### 2.2 會員登入

會員登入並取得存取 Token。

- **URL**: `POST /api/auth/login`
- **認證**: 不需要

#### Request Body

| 欄位 | 類型 | 必填 | 說明 |
|------|------|------|------|
| email | string | ✅ | 電子郵件 |
| password | string | ✅ | 密碼 |

#### 請求範例

```bash
curl -X POST http://localhost/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{
    "email": "user@example.com",
    "password": "password123"
  }'
```

#### 成功回應 (200 OK)

```json
{
  "user": {
    "id": "user_a1b2c3d4e5f6g7h8",
    "email": "user@example.com",
    "name": "王小明",
    "phone": "0912345678",
    "address": null,
    "createdAt": "2025-12-01T14:30:00+00:00",
    "updatedAt": "2025-12-01T14:30:00+00:00"
  },
  "token": "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9..."
}
```

#### 錯誤回應

| 狀態碼 | 說明 |
|--------|------|
| 401 | 電子郵件或密碼錯誤 |
| 422 | 驗證失敗 |

---

### 2.3 會員登出

登出目前使用者，使 Token 失效。

- **URL**: `POST /api/auth/logout`
- **認證**: ✅ 需要

#### 請求範例

```bash
curl -X POST http://localhost/api/auth/logout \
  -H "Authorization: Bearer <token>"
```

#### 成功回應 (200 OK)

```json
{
  "message": "登出成功"
}
```

#### 錯誤回應

| 狀態碼 | 說明 |
|--------|------|
| 401 | 未提供 Token 或 Token 無效 |

---

## 3. 商品模組 (Products)

### 3.1 取得商品列表

取得商品列表，支援分頁、搜尋與篩選。

- **URL**: `GET /api/products`
- **認證**: 不需要

#### Query Parameters

| 參數 | 類型 | 預設值 | 說明 |
|------|------|--------|------|
| page | integer | 1 | 頁碼（最小 1） |
| limit | integer | 20 | 每頁數量（1-100） |
| categoryId | string | - | 分類 ID 篩選 |
| sort | string | newest | 排序方式 |
| search | string | - | 關鍵字搜尋（商品名稱、描述） |

#### 排序方式 (sort)

| 值 | 說明 |
|------|------|
| newest | 最新上架（預設） |
| price-asc | 價格低到高 |
| price-desc | 價格高到低 |
| popular | 熱門商品 |

#### 請求範例

```bash
# 取得第一頁商品
curl -X GET "http://localhost/api/products"

# 搜尋商品
curl -X GET "http://localhost/api/products?search=耳機&sort=price-asc"

# 篩選分類
curl -X GET "http://localhost/api/products?categoryId=cat_elec&page=1&limit=10"
```

#### 成功回應 (200 OK)

```json
{
  "products": [
    {
      "id": "prod_001",
      "name": "無線藍牙耳機",
      "description": "高音質無線藍牙耳機...",
      "shortDescription": "輕巧便攜",
      "price": 1299,
      "originalPrice": 1599,
      "images": [
        "https://example.com/img1.jpg",
        "https://example.com/img2.jpg"
      ],
      "thumbnail": "https://example.com/thumb.jpg",
      "categoryId": "cat_elec",
      "stock": 100,
      "sku": "HP-001",
      "tags": ["熱銷", "新品"],
      "featured": true,
      "createdAt": "2025-11-01T00:00:00+00:00",
      "updatedAt": "2025-11-15T10:30:00+00:00"
    }
  ],
  "total": 100,
  "page": 1,
  "limit": 20,
  "hasMore": true
}
```

---

### 3.2 取得商品詳情

取得特定商品的詳細資訊，包含規格。

- **URL**: `GET /api/products/:id`
- **認證**: 不需要

#### Path Parameters

| 參數 | 類型 | 說明 |
|------|------|------|
| id | string | 商品 ID |

#### 請求範例

```bash
curl -X GET "http://localhost/api/products/prod_001"
```

#### 成功回應 (200 OK)

```json
{
  "id": "prod_001",
  "name": "無線藍牙耳機",
  "description": "高音質無線藍牙耳機，支援主動降噪功能...",
  "shortDescription": "輕巧便攜",
  "price": 1299,
  "originalPrice": 1599,
  "images": [
    "https://example.com/img1.jpg",
    "https://example.com/img2.jpg"
  ],
  "thumbnail": "https://example.com/thumb.jpg",
  "categoryId": "cat_elec",
  "stock": 100,
  "sku": "HP-001",
  "tags": ["熱銷", "新品"],
  "featured": true,
  "specifications": {
    "藍牙版本": "5.0",
    "續航力": "20小時",
    "重量": "250g"
  },
  "createdAt": "2025-11-01T00:00:00+00:00",
  "updatedAt": "2025-11-15T10:30:00+00:00"
}
```

#### 錯誤回應

| 狀態碼 | 說明 |
|--------|------|
| 404 | 商品不存在 |

---

### 3.3 取得分類列表

取得所有商品分類。

- **URL**: `GET /api/categories`
- **認證**: 不需要

#### 請求範例

```bash
curl -X GET "http://localhost/api/categories"
```

#### 成功回應 (200 OK)

```json
[
  {
    "id": "cat_elec",
    "name": "電子產品",
    "slug": "electronics",
    "description": "各類3C周邊",
    "icon": "device-icon"
  },
  {
    "id": "cat_cloth",
    "name": "服飾配件",
    "slug": "clothing",
    "description": "流行服飾",
    "icon": "shirt-icon"
  },
  {
    "id": "cat_home",
    "name": "居家生活",
    "slug": "home",
    "description": "居家用品",
    "icon": "home-icon"
  },
  {
    "id": "cat_food",
    "name": "食品飲料",
    "slug": "food",
    "description": "美食甜點",
    "icon": "food-icon"
  }
]
```

---

## 4. 訂單模組 (Orders)

### 4.1 建立訂單

建立新訂單。系統會自動驗證商品庫存、計算金額。

- **URL**: `POST /api/orders`
- **認證**: ✅ 需要

#### Request Body

| 欄位 | 類型 | 必填 | 說明 |
|------|------|------|------|
| items | array | ✅ | 訂單商品列表 |
| items[].id | string | ✅ | 商品 ID |
| items[].quantity | integer | ✅ | 數量（大於 0） |
| shippingInfo | object | ✅ | 收件人資訊 |
| shippingInfo.recipientName | string | ✅ | 收件人姓名 |
| shippingInfo.recipientPhone | string | ✅ | 收件人電話 |
| shippingInfo.city | string | ✅ | 城市 |
| shippingInfo.district | string | ✅ | 區域 |
| shippingInfo.address | string | ✅ | 詳細地址 |
| shippingInfo.postalCode | string | ✅ | 郵遞區號 |
| paymentMethod | string | ✅ | 付款方式 |

#### 付款方式 (paymentMethod)

| 值 | 說明 |
|------|------|
| credit_card | 信用卡 |
| atm | ATM 轉帳 |
| cod | 貨到付款 |

#### 運費計算規則

- 訂單金額 ≥ 1000 元：免運費
- 訂單金額 < 1000 元：運費 60 元

#### 請求範例

```bash
curl -X POST http://localhost/api/orders \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "items": [
      { "id": "prod_001", "quantity": 2 },
      { "id": "prod_003", "quantity": 1 }
    ],
    "shippingInfo": {
      "recipientName": "王小明",
      "recipientPhone": "0912345678",
      "city": "台北市",
      "district": "信義區",
      "address": "市府路1號",
      "postalCode": "110"
    },
    "paymentMethod": "credit_card"
  }'
```

#### 成功回應 (201 Created)

```json
{
  "id": "ord_20251201a1b2c3d4",
  "userId": "user_a1b2c3d4e5f6g7h8",
  "orderNumber": "ORD-20251201-0001",
  "items": [
    {
      "id": "prod_001",
      "name": "無線藍牙耳機",
      "price": 1299,
      "quantity": 2,
      "thumbnail": "https://example.com/thumb.jpg"
    },
    {
      "id": "prod_003",
      "name": "經典白T恤",
      "price": 399,
      "quantity": 1,
      "thumbnail": "https://example.com/thumb3.jpg"
    }
  ],
  "subtotal": 2997,
  "shipping": 0,
  "discount": 0,
  "total": 2997,
  "status": "pending",
  "paymentMethod": "credit_card",
  "shippingInfo": {
    "recipientName": "王小明",
    "recipientPhone": "0912345678",
    "city": "台北市",
    "district": "信義區",
    "address": "市府路1號",
    "postalCode": "110"
  },
  "trackingNumber": null,
  "createdAt": "2025-12-01T14:50:00+00:00",
  "updatedAt": "2025-12-01T14:50:00+00:00"
}
```

#### 錯誤回應

| 狀態碼 | 說明 |
|--------|------|
| 400 | 商品不存在、庫存不足、商品項目為空 |
| 401 | 未認證 |
| 422 | 驗證失敗 |
| 500 | 訂單建立失敗 |

---

### 4.2 取得訂單列表

取得目前登入使用者的歷史訂單。

- **URL**: `GET /api/orders`
- **認證**: ✅ 需要

#### Query Parameters

| 參數 | 類型 | 預設值 | 說明 |
|------|------|--------|------|
| page | integer | 1 | 頁碼 |
| limit | integer | 10 | 每頁數量（1-100） |
| status | string | - | 訂單狀態篩選 |

#### 訂單狀態 (status)

| 值 | 說明 |
|------|------|
| pending | 待處理 |
| processing | 處理中 |
| shipped | 已出貨 |
| completed | 已完成 |
| cancelled | 已取消 |

#### 請求範例

```bash
# 取得所有訂單
curl -X GET "http://localhost/api/orders" \
  -H "Authorization: Bearer <token>"

# 篩選待處理訂單
curl -X GET "http://localhost/api/orders?status=pending" \
  -H "Authorization: Bearer <token>"
```

#### 成功回應 (200 OK)

```json
{
  "orders": [
    {
      "id": "ord_20251201a1b2c3d4",
      "orderNumber": "ORD-20251201-0001",
      "total": 2997,
      "status": "pending",
      "createdAt": "2025-12-01T14:50:00+00:00"
    },
    {
      "id": "ord_20251130x1y2z3w4",
      "orderNumber": "ORD-20251130-0003",
      "total": 1599,
      "status": "completed",
      "createdAt": "2025-11-30T10:20:00+00:00"
    }
  ],
  "total": 5,
  "page": 1,
  "limit": 10
}
```

---

### 4.3 取得訂單詳情

取得特定訂單的完整資訊。只能查看自己的訂單。

- **URL**: `GET /api/orders/:id`
- **認證**: ✅ 需要

#### Path Parameters

| 參數 | 類型 | 說明 |
|------|------|------|
| id | string | 訂單 ID |

#### 請求範例

```bash
curl -X GET "http://localhost/api/orders/ord_20251201a1b2c3d4" \
  -H "Authorization: Bearer <token>"
```

#### 成功回應 (200 OK)

```json
{
  "id": "ord_20251201a1b2c3d4",
  "userId": "user_a1b2c3d4e5f6g7h8",
  "orderNumber": "ORD-20251201-0001",
  "items": [
    {
      "id": "prod_001",
      "name": "無線藍牙耳機",
      "price": 1299,
      "quantity": 2,
      "thumbnail": "https://example.com/thumb.jpg"
    }
  ],
  "subtotal": 2598,
  "shipping": 0,
  "discount": 0,
  "total": 2598,
  "status": "pending",
  "paymentMethod": "credit_card",
  "shippingInfo": {
    "recipientName": "王小明",
    "recipientPhone": "0912345678",
    "city": "台北市",
    "district": "信義區",
    "address": "市府路1號",
    "postalCode": "110"
  },
  "trackingNumber": null,
  "createdAt": "2025-12-01T14:50:00+00:00",
  "updatedAt": "2025-12-01T14:50:00+00:00"
}
```

#### 錯誤回應

| 狀態碼 | 說明 |
|--------|------|
| 401 | 未認證 |
| 404 | 訂單不存在（或無權限查看） |

---

## 5. 使用者模組 (User)

### 5.1 取得個人資料

取得目前登入使用者的個人資料。

- **URL**: `GET /api/user/profile`
- **認證**: ✅ 需要

#### 請求範例

```bash
curl -X GET "http://localhost/api/user/profile" \
  -H "Authorization: Bearer <token>"
```

#### 成功回應 (200 OK)

```json
{
  "id": "user_a1b2c3d4e5f6g7h8",
  "email": "user@example.com",
  "name": "王小明",
  "phone": "0912345678",
  "address": null,
  "createdAt": "2025-12-01T14:30:00+00:00",
  "updatedAt": "2025-12-01T14:30:00+00:00"
}
```

---

### 5.2 更新個人資料

更新個人資料（姓名、電話、密碼）。

- **URL**: `PUT /api/user/profile`
- **認證**: ✅ 需要

#### Request Body

所有欄位皆為選填，只需提供要更新的欄位。

| 欄位 | 類型 | 說明 |
|------|------|------|
| name | string | 新姓名（1-100 字元） |
| phone | string | 新電話（10-20 字元） |
| currentPassword | string | 目前密碼（更改密碼時必填） |
| newPassword | string | 新密碼（至少 8 字元） |

#### 請求範例

**更新姓名和電話：**

```bash
curl -X PUT http://localhost/api/user/profile \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "name": "王大明",
    "phone": "0987654321"
  }'
```

**更改密碼：**

```bash
curl -X PUT http://localhost/api/user/profile \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer <token>" \
  -d '{
    "currentPassword": "oldpassword123",
    "newPassword": "newpassword456"
  }'
```

#### 成功回應 (200 OK)

```json
{
  "message": "更新成功",
  "user": {
    "id": "user_a1b2c3d4e5f6g7h8",
    "email": "user@example.com",
    "name": "王大明",
    "phone": "0987654321",
    "address": null,
    "createdAt": "2025-12-01T14:30:00+00:00",
    "updatedAt": "2025-12-01T15:00:00+00:00"
  }
}
```

#### 錯誤回應

| 狀態碼 | 說明 |
|--------|------|
| 400 | 更改密碼需要提供目前密碼、目前密碼不正確 |
| 401 | 未認證 |
| 422 | 驗證失敗 |
| 500 | 更新失敗 |

---

## 6. 錯誤代碼對照表

### HTTP 狀態碼

| 狀態碼 | 說明 |
|--------|------|
| 200 | 成功 |
| 201 | 建立成功 |
| 400 | 請求錯誤（參數錯誤、業務邏輯錯誤） |
| 401 | 未認證（Token 無效或過期） |
| 404 | 資源不存在 |
| 422 | 驗證失敗 |
| 500 | 伺服器錯誤 |

### 常見錯誤訊息

| 訊息 | 說明 |
|------|------|
| 未提供認證 Token | 請求需要認證但未提供 Authorization Header |
| Token 無效或已過期 | Token 格式錯誤、已過期或已被登出 |
| 使用者不存在 | Token 對應的使用者已被刪除 |
| 電子郵件或密碼錯誤 | 登入失敗 |
| 此電子郵件已被註冊 | 註冊時 Email 重複 |
| 商品不存在 | 商品 ID 無效 |
| 商品庫存不足 | 購買數量超過庫存 |
| 訂單不存在 | 訂單 ID 無效或無權限查看 |
| 目前密碼不正確 | 更改密碼時舊密碼驗證失敗 |

---

## 附錄

### A. 資料模型

#### User (使用者)

| 欄位 | 類型 | 說明 |
|------|------|------|
| id | string | 使用者 ID (格式: user_xxx) |
| email | string | 電子郵件 |
| name | string | 姓名 |
| phone | string | 電話 |
| address | string | 地址（可為 null） |
| createdAt | string | 建立時間 (ISO 8601) |
| updatedAt | string | 更新時間 (ISO 8601) |

#### Product (商品)

| 欄位 | 類型 | 說明 |
|------|------|------|
| id | string | 商品 ID (格式: prod_xxx) |
| name | string | 商品名稱 |
| description | string | 詳細描述 |
| shortDescription | string | 簡短描述 |
| price | number | 售價 |
| originalPrice | number | 原價（可為 null） |
| images | array | 圖片 URL 陣列 |
| thumbnail | string | 縮圖 URL |
| categoryId | string | 分類 ID |
| stock | integer | 庫存數量 |
| sku | string | 商品編號 |
| tags | array | 標籤陣列 |
| specifications | object | 規格（僅詳情頁） |
| featured | boolean | 是否為精選商品 |
| createdAt | string | 建立時間 |
| updatedAt | string | 更新時間 |

#### Category (分類)

| 欄位 | 類型 | 說明 |
|------|------|------|
| id | string | 分類 ID (格式: cat_xxx) |
| name | string | 分類名稱 |
| slug | string | URL 友善名稱 |
| description | string | 分類描述 |
| icon | string | 圖示名稱 |

#### Order (訂單)

| 欄位 | 類型 | 說明 |
|------|------|------|
| id | string | 訂單 ID (格式: ord_xxx) |
| userId | string | 購買者 ID |
| orderNumber | string | 訂單編號 (格式: ORD-YYYYMMDD-NNNN) |
| items | array | 訂單商品列表 |
| subtotal | number | 商品小計 |
| shipping | number | 運費 |
| discount | number | 折扣金額 |
| total | number | 訂單總金額 |
| status | string | 訂單狀態 |
| paymentMethod | string | 付款方式 |
| shippingInfo | object | 收件人資訊 |
| trackingNumber | string | 物流追蹤碼（可為 null） |
| createdAt | string | 建立時間 |
| updatedAt | string | 更新時間 |

### B. 環境變數設定

在 `.env` 檔案中設定以下變數：

```env
# JWT 設定
JWT_SECRET=your-super-secret-key-change-in-production
JWT_EXPIRATION=86400
```

### C. 資料庫初始化

```bash
# 執行資料庫遷移
php spark migrate

# 填入測試資料
php spark db:seed DatabaseSeeder
```
