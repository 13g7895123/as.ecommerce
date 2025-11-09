# Quick Start Guide - 購物網站測試指南

**專案狀態**: ~75% 完成 | **分支**: 001-shopping-flow | **日期**: 2025-10-25

## 快速啟動 (5 分鐘內運行)

### 1. 確認環境
```bash
node --version  # 需要 v20.x
npm --version   # 需要 v10.x
```

### 2. 安裝依賴 (如尚未安裝)
```bash
cd frontend
npm install
```

### 3. 啟動開發伺服器
```bash
npm run dev
```

開啟瀏覽器訪問: http://localhost:3000

## 功能測試清單

### ✅ 使用者故事 1: 瀏覽熱門產品
1. 訪問首頁 http://localhost:3000
2. 應該看到 8-12 個產品卡片
3. 每個卡片顯示：圖片、名稱、價格、描述
4. 點擊產品卡片應導航至產品詳情頁

**預期結果**: ✅ 可以瀏覽熱門產品列表

### ✅ 使用者故事 2: 依類別瀏覽產品
1. 在首頁點擊頂部類別導航
2. 選擇任一類別（如「電子產品」、「服飾配件」）
3. 應顯示該類別的所有產品
4. 測試排序功能（價格、最新、熱門）

**預期結果**: ✅ 可以按類別篩選和排序產品

### ✅ 使用者故事 3: 加入購物車
1. 點擊任一產品進入詳情頁
2. 選擇數量（使用 +/- 按鈕）
3. 點擊「加入購物車」
4. 右上角購物車圖示應顯示商品數量
5. 點擊購物車圖示查看購物車
6. 測試修改數量、移除商品

**預期結果**: ✅ 購物車功能正常運作

### ✅ 使用者故事 4: 會員註冊與登入
**註冊新帳號:**
1. 點擊右上角「註冊」
2. 填寫表單（姓名、Email、密碼、手機）
3. 點擊「註冊」
4. 應自動登入並返回首頁

**登入已有帳號:**
1. 點擊右上角「登入」
2. 輸入 Email 和密碼
3. 點擊「登入」
4. 右上角應顯示「訂單查詢」和「登出」

**測試帳號:**
- Email: test@example.com
- Password: password123

**預期結果**: ✅ 註冊和登入功能正常

### ✅ 使用者故事 5: 結帳流程
1. 確保購物車有商品
2. 點擊「前往結帳」
3. 填寫收件資訊（姓名、電話、地址）
4. 選擇付款方式
5. 檢查訂單摘要
6. 點擊「確認訂單」
7. 應導航至訂單完成頁面並顯示訂單編號

**預期結果**: ✅ 可以完成結帳流程

### ✅ 使用者故事 6: 訂單查詢
1. 登入後點擊「訂單查詢」
2. 應顯示歷史訂單列表
3. 點擊任一訂單查看詳情
4. 應顯示完整訂單資訊（商品、金額、收件地址）

**預期結果**: ✅ 可以查看訂單歷史

## UI/UX 功能測試

### Header & Navigation
- ✅ Logo 連結返回首頁
- ✅ 類別導航顯示所有分類
- ✅ 購物車圖示顯示商品數量
- ✅ 登入/登出/註冊連結正常
- ✅ 響應式設計 (手機/平板/桌面)

### Toast 通知
- ✅ 加入購物車顯示成功訊息
- ✅ 登入/註冊顯示結果
- ✅ 錯誤情況顯示錯誤訊息
- ✅ 通知可手動關閉
- ✅ 3 秒後自動消失

### Loading States
- ✅ 產品載入時顯示 skeleton loaders
- ✅ 按鈕點擊時顯示 loading spinner
- ✅ 頁面切換時顯示載入狀態

## 已知限制 (待修復)

### Critical Issues
1. ❌ **庫存驗證**: 目前無庫存限制，可加入超過庫存數量
2. ❌ **購物車合併**: 訪客購物車登入後不會合併至會員購物車
3. ❌ **登出功能**: 登出按鈕無實際作用 (需實作 API endpoint)
4. ❌ **訂單後清空購物車**: 完成訂單後購物車未清空
5. ❌ **錯誤處理**: API 錯誤未完整處理

### Minor Issues
- ⚠️ 無分頁功能 (產品列表全部載入)
- ⚠️ 無會員個人資料頁
- ⚠️ 無地址管理功能
- ⚠️ 訂單狀態無標籤顯示
- ⚠️ 無密碼強度指示器

## 效能測試

### 建置測試
```bash
npm run build
npm run preview
```
**預期**: ✅ 成功建置，無錯誤

### Linting (需修復)
```bash
npm run lint
```
**狀態**: ⚠️ ESLint 需要正確配置

### 測試 (未實作)
```bash
npm run test        # 單元測試
npm run test:e2e    # E2E 測試
```
**狀態**: ❌ 測試尚未撰寫

## 瀏覽器相容性

**已測試:**
- ✅ Chrome (latest)
- ✅ Firefox (latest)
- ⏳ Safari (需測試)
- ⏳ Edge (需測試)

**響應式斷點:**
- Mobile: < 640px
- Tablet: 640px - 1024px
- Desktop: > 1024px

## Mock 資料

### 產品資料
- 位置: `frontend/app/server/mock/products.ts`
- 數量: 100+ 產品
- 類別: 電子產品、服飾配件、家居生活等

### 測試用戶
位置: `frontend/app/server/mock/users.ts`

```javascript
// 預設測試帳號
{
  email: 'test@example.com',
  password: 'password123',
  name: '測試用戶'
}
```

## 技術細節

### 架構
- **框架**: Nuxt 3 (Vue 3)
- **狀態管理**: Pinia
- **樣式**: Tailwind CSS
- **語言**: TypeScript
- **路由**: Nuxt File-based Routing

### 關鍵檔案
```
frontend/app/
├── layouts/default.vue    # 主版面 (Header + Footer)
├── layouts/auth.vue       # 登入/註冊版面
├── stores/cart.ts         # 購物車狀態
├── stores/auth.ts         # 認證狀態
├── composables/useToast.ts  # Toast 通知
└── middleware/auth.ts     # 路由守衛
```

## 開發命令

```bash
# 開發
npm run dev              # 啟動開發伺服器 (http://localhost:3000)

# 建置
npm run build            # 生產環境建置
npm run preview          # 預覽建置結果
npm run generate         # 靜態站點生成

# 程式碼品質
npm run lint             # 檢查程式碼風格
npm run lint:fix         # 自動修正問題
npm run format           # Prettier 格式化

# 測試 (尚未實作)
npm run test             # 單元測試
npm run test:e2e         # E2E 測試
npm run test:coverage    # 測試覆蓋率
```

## 下一步行動

### 立即修復 (MVP 必需)
1. 實作 logout API endpoint
2. 加入庫存驗證邏輯
3. 實作購物車合併功能
4. 訂單完成後清空購物車
5. 改善錯誤處理

### 短期改進 (1-2 週)
6. 撰寫 E2E 測試
7. 加入產品分頁
8. 建立會員個人資料頁
9. 實作地址管理
10. 加入訂單狀態標籤

### 長期優化 (1 個月+)
11. 完整單元測試 (目標 ≥90%)
12. 無障礙稽核 (WCAG 2.1 AA)
13. 效能優化 (Lighthouse CI)
14. SEO 優化
15. 多語言支援

## 問題回報

如發現問題，請記錄：
1. 重現步驟
2. 預期行為
3. 實際行為
4. 瀏覽器版本
5. 錯誤訊息 (如有)

## 總結

✅ **核心功能運作正常**
- 產品瀏覽、購物車、結帳、會員系統、訂單查詢

⚠️ **需要修復的項目**
- 5 個 Critical Issues (估計 4-8 小時)

🎯 **MVP 就緒度**: 75%
- 修復 Critical Issues 後可達 90%+
- 可進行 Alpha 測試

📝 **詳細文件**
- 完整實作狀態: `IMPLEMENTATION_SUMMARY.md`
- 驗證報告: `frontend/VALIDATION_REPORT.md`
- 任務清單: `specs/001-shopping-flow/tasks.md`
