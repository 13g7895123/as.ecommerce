# 後端 API 需求規格書

**版本**: 1.0.0
**日期**: 2025-11-30
**狀態**: 草稿

## 1. 概述

本文件定義了電子商務網站後端 API 的需求規格。所有 API 均應遵循 RESTful 風格，並使用 JSON 作為資料交換格式。

### 1.1 通用規範

- **Base URL**: `/api`
- **Content-Type**: `application/json`
- **認證方式**: Bearer Token (Header: `Authorization: Bearer <token>`)
- **時間格式**: ISO 8601 (e.g., `2025-11-30T14:47:40.798Z`)

### 1.2 錯誤回應格式

```json
{
  "statusCode": 400,
  "statusMessage": "錯誤訊息描述",
  "data": null
}
```

---

## 2. 認證模組 (Auth)

### 2.1 會員註冊
- **Endpoint**: `POST /auth/register`
- **描述**: 註冊新會員帳號。
- **Request Body**:
  ```json
  {
    "email": "user@example.com",   // 必填, Email 格式
    "password": "password123",     // 必填, 至少 8 碼
    "name": "王小明",              // 必填
    "phone": "0912345678"          // 必填, 手機格式
  }
  ```
- **Response (200 OK)**:
  ```json
  {
    "user": {
      "id": "user_123",
      "email": "user@example.com",
      "name": "王小明",
      "phone": "0912345678",
      "createdAt": "2025-11-30T10:00:00Z",
      "updatedAt": "2025-11-30T10:00:00Z"
    },
    "token": "eyJhbGciOiJIUzI1Ni..."
  }
  ```

### 2.2 會員登入
- **Endpoint**: `POST /auth/login`
- **描述**: 會員登入並取得存取 Token。
- **Request Body**:
  ```json
  {
    "email": "user@example.com",
    "password": "password123"
  }
  ```
- **Response (200 OK)**:
  ```json
  {
    "user": {
      "id": "user_123",
      "email": "user@example.com",
      "name": "王小明",
      "phone": "0912345678",
      "createdAt": "2025-11-30T10:00:00Z",
      "updatedAt": "2025-11-30T10:00:00Z"
    },
    "token": "eyJhbGciOiJIUzI1Ni..."
  }
  ```

### 2.3 會員登出
- **Endpoint**: `POST /auth/logout`
- **描述**: 登出目前使用者，使 Token 失效（若後端有實作黑名單機制）。
- **Header**: 需要 Authorization
- **Response (200 OK)**:
  ```json
  {
    "message": "登出成功"
  }
  ```

---

## 3. 商品模組 (Products)

### 3.1 取得商品列表
- **Endpoint**: `GET /products`
- **描述**: 取得商品列表，支援分頁、搜尋與篩選。
- **Query Parameters**:
  - `page`: 頁碼 (預設 1)
  - `limit`: 每頁數量 (預設 20)
  - `categoryId`: 分類 ID (選填)
  - `sort`: 排序方式 (`price-asc`, `price-desc`, `newest`, `popular`)
  - `search`: 關鍵字搜尋 (選填)
- **Response (200 OK)**:
  ```json
  {
    "products": [
      {
        "id": "prod_001",
        "name": "無線藍牙耳機",
        "description": "高音質...",
        "shortDescription": "輕巧便攜",
        "price": 1299,
        "originalPrice": 1599,
        "images": ["url1.jpg", "url2.jpg"],
        "thumbnail": "thumb.jpg",
        "categoryId": "cat_elec",
        "stock": 100,
        "sku": "HP-001",
        "tags": ["熱銷", "新品"],
        "featured": true,
        "createdAt": "2025-11-01T00:00:00Z",
        "updatedAt": "2025-11-01T00:00:00Z"
      }
    ],
    "total": 100,
    "page": 1,
    "limit": 20,
    "hasMore": true
  }
  ```

### 3.2 取得商品詳情
- **Endpoint**: `GET /products/:id`
- **描述**: 取得特定商品的詳細資訊。
- **Response (200 OK)**:
  ```json
  {
    "id": "prod_001",
    "name": "無線藍牙耳機",
    // ... (同商品列表物件結構，可能包含更詳細的 specifications)
    "specifications": {
      "藍牙版本": "5.0",
      "續航力": "20小時"
    }
  }
  ```

### 3.3 取得分類列表
- **Endpoint**: `GET /categories`
- **描述**: 取得所有商品分類。
- **Response (200 OK)**:
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
    }
  ]
  ```

---

## 4. 訂單模組 (Orders)

### 4.1 建立訂單
- **Endpoint**: `POST /orders`
- **描述**: 建立新訂單。
- **Header**: 需要 Authorization
- **Request Body**:
  ```json
  {
    "items": [
      {
        "id": "prod_001",  // 商品 ID
        "quantity": 2
      }
    ],
    "shippingInfo": {
      "recipientName": "王小明",
      "recipientPhone": "0912345678",
      "city": "台北市",
      "district": "信義區",
      "address": "市府路1號",
      "postalCode": "110"
    },
    "paymentMethod": "credit_card" // credit_card, atm, cod
  }
  ```
- **Response (201 Created)**:
  ```json
  {
    "id": "ord_20251130001",
    "userId": "user_123",
    "orderNumber": "ORD-20251130-0001",
    "items": [...],
    "subtotal": 2598,
    "shipping": 60,
    "discount": 0,
    "total": 2658,
    "status": "pending",
    "paymentMethod": "credit_card",
    "shippingInfo": {...},
    "createdAt": "2025-11-30T14:50:00Z",
    "updatedAt": "2025-11-30T14:50:00Z"
  }
  ```

### 4.2 取得訂單列表
- **Endpoint**: `GET /orders`
- **描述**: 取得目前登入使用者的歷史訂單。
- **Header**: 需要 Authorization
- **Query Parameters**:
  - `page`: 頁碼
  - `limit`: 每頁數量
  - `status`: 訂單狀態篩選 (pending, processing, shipped, completed, cancelled)
- **Response (200 OK)**:
  ```json
  {
    "orders": [
      {
        "id": "ord_20251130001",
        "orderNumber": "ORD-20251130-0001",
        "total": 2658,
        "status": "pending",
        "createdAt": "2025-11-30T14:50:00Z",
        // ... 簡略資訊
      }
    ],
    "total": 5,
    "page": 1,
    "limit": 10
  }
  ```

### 4.3 取得訂單詳情
- **Endpoint**: `GET /orders/:id`
- **描述**: 取得特定訂單的完整資訊。
- **Header**: 需要 Authorization
- **Response (200 OK)**:
  ```json
  {
    "id": "ord_20251130001",
    "orderNumber": "ORD-20251130-0001",
    "items": [
      {
        "id": "prod_001",
        "name": "無線藍牙耳機",
        "price": 1299,
        "quantity": 2,
        "thumbnail": "thumb.jpg"
      }
    ],
    "shippingInfo": {...},
    "status": "pending",
    "trackingNumber": null,
    // ... 完整資訊
  }
  ```

---

## 5. 使用者模組 (User) - *建議新增*

### 5.1 取得個人資料
- **Endpoint**: `GET /user/profile`
- **描述**: 取得目前登入使用者的個人資料。
- **Header**: 需要 Authorization
- **Response (200 OK)**:
  ```json
  {
    "id": "user_123",
    "email": "user@example.com",
    "name": "王小明",
    "phone": "0912345678",
    "address": "台北市信義區..." // 若有儲存預設地址
  }
  ```

### 5.2 更新個人資料
- **Endpoint**: `PUT /user/profile`
- **描述**: 更新個人資料（姓名、電話、密碼等）。
- **Header**: 需要 Authorization
- **Request Body**:
  ```json
  {
    "name": "王大明",
    "phone": "0987654321",
    "currentPassword": "oldpassword", // 若要改密碼需驗證舊密碼
    "newPassword": "newpassword"      // 選填
  }
  ```
- **Response (200 OK)**:
  ```json
  {
    "message": "更新成功",
    "user": {...}
  }
  ```

---

## 6. 資料模型定義 (Data Models)

### Product (商品)
| 欄位 | 型別 | 描述 |
| --- | --- | --- |
| id | string | 商品唯一識別碼 |
| name | string | 商品名稱 |
| description | string | 商品詳細描述 (HTML/Markdown) |
| price | number | 售價 |
| originalPrice | number | 原價 (選填) |
| stock | number | 庫存數量 |
| categoryId | string | 分類 ID |
| images | string[] | 圖片 URL 陣列 |
| featured | boolean | 是否為精選商品 |

### Order (訂單)
| 欄位 | 型別 | 描述 |
| --- | --- | --- |
| id | string | 訂單唯一識別碼 |
| userId | string | 購買者 ID |
| items | OrderItem[] | 訂單商品列表 |
| total | number | 訂單總金額 |
| status | enum | pending, processing, shipped, completed, cancelled |
| paymentMethod | enum | credit_card, atm, cod |
| shippingInfo | object | 收件人資訊 (姓名, 電話, 地址) |

### User (使用者)
| 欄位 | 型別 | 描述 |
| --- | --- | --- |
| id | string | 使用者 ID |
| email | string | 電子郵件 (帳號) |
| passwordHash | string | 密碼雜湊 (不回傳) |
| name | string | 姓名 |
| phone | string | 電話 |
