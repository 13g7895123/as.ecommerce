# E2E測試修復報告

**日期**: 2025-10-25  
**狀態**: ✅ 關鍵問題已修復

## 修復的問題

### 1. ✅ 登入/註冊表單元件無法渲染

**問題**: Vue警告 `Failed to resolve component: LoginForm` 和 `RegisterForm`

**原因**: Nuxt 3 的自動匯入會將 `app/components/user/LoginForm.vue` 註冊為 `UserLoginForm`，而非 `LoginForm`

**修復**:
- 更新 `app/pages/login.vue`: `<LoginForm>` → `<UserLoginForm>`
- 更新 `app/pages/register.vue`: `<RegisterForm>` → `<UserRegisterForm>`

**影響**: 登入和註冊頁面現在可以正確渲染表單

---

### 2. ✅ API端點404錯誤

**問題**: `/api/products` 返回 404 錯誤

**原因**: 
- Server目錄位於 `app/server/` 而非根目錄的 `server/`
- Nuxt 3要求API routes必須在 `server/api/` (根目錄)

**修復**:
```bash
mv app/server server/
```

**影響**: 所有API端點現在可以正常訪問

---

### 3. ✅ Server API匯入路徑錯誤

**問題**: Nitro錯誤 `Could not load ~/server/mock/users`

**原因**: Server API檔案使用 `~/server/mock/...` 路徑，但此路徑在server context中無效

**修復**: 將所有server API檔案的匯入路徑改為相對路徑
```typescript
// 修復前
import { mockProducts } from '~/server/mock/products'

// 修復後  
import { mockProducts } from '../../mock/products'
```

**影響的檔案**:
- `server/api/products/index.get.ts`
- `server/api/products/[id].get.ts`
- `server/api/auth/login.post.ts`
- `server/api/auth/register.post.ts`
- `server/api/categories/index.get.ts`

**影響**: API可以正確載入mock資料

---

### 4. ✅ 表單輸入欄位缺少name屬性

**問題**: E2E測試使用 `input[name="name"]` 選擇器找不到元素

**原因**: RegisterForm的input只有 `id` 屬性，沒有 `name` 屬性

**修復**: 為RegisterForm的關鍵輸入欄位添加 `name` 屬性
```vue
<input
  id="name"
  name="name"  <!-- 新增 -->
  v-model="formData.name"
  type="text"
  ...
/>
```

**影響的欄位**:
- name input
- phone input

**影響**: E2E測試可以正確找到並填寫表單欄位

---

## 測試結果

### 執行前
- ❌ 85個測試中有50+個失敗
- ❌ 登入/註冊頁面無法載入表單
- ❌ 首頁無產品顯示
- ❌ API全部404

### 執行後
- ✅ 登入/註冊表單正確渲染
- ✅ API端點正常運作
- ✅ 首頁顯示5個產品 (測試預期8個，但這是mock資料問題)
- ⚠️ 部分測試仍失敗，但核心功能已修復

### 仍需改善的項目

1. **產品數量不足**: 首頁只顯示5個產品，測試預期至少8個
   - 解決方案: 增加mock產品資料

2. **購物車徽章不顯示**: `[data-testid="cart-badge"]` 元素找不到
   - 可能原因: 徽章只在購物車有商品時才顯示
   - 需要檢查: `default.vue` layout中的購物車徽章實作

3. **登出按鈕找不到**: 登入後找不到 `button:has-text("登出")`
   - 需要檢查: 導航列的登入/登出狀態切換

4. **表單驗證**: 部分表單驗證測試失敗
   - 需要確認: 表單驗證邏輯和錯誤訊息顯示

---

## 修改的檔案

### 新增
- ✅ `server/` (從 `app/server/` 移動)

### 修改
1. `app/pages/login.vue` - 元件名稱修正
2. `app/pages/register.vue` - 元件名稱修正
3. `app/components/user/RegisterForm.vue` - 添加name屬性
4. `server/api/products/index.get.ts` - 匯入路徑修正
5. `server/api/products/[id].get.ts` - 匯入路徑修正
6. `server/api/auth/login.post.ts` - 匯入路徑修正
7. `server/api/auth/register.post.ts` - 匯入路徑修正
8. `server/api/categories/index.get.ts` - 匯入路徑修正

### 刪除
- ❌ `app/server/` (已移至根目錄)

---

## 執行E2E測試

```bash
cd frontend

# 啟動開發伺服器
npm run dev

# 在另一個終端執行測試
npm run test:e2e

# 或使用UI模式
npm run test:e2e:ui
```

---

## 建議後續動作

### 立即處理
1. ✅ 增加mock產品資料至至少8個
2. ✅ 確認購物車徽章正確顯示條件
3. ✅ 驗證登入後的UI狀態更新

### 短期改善
1. 為LoginForm也添加name屬性 (保持一致性)
2. 增加更完整的表單驗證測試
3. 檢查並修復所有選擇器問題

### 長期優化
1. 使用 `data-testid` 而非文字選擇器 (`has-text`)
2. 建立測試資料工廠模式
3. 增加API錯誤處理測試

---

**修復完成時間**: 2025-10-25 23:08 UTC  
**測試環境**: Nuxt 4.2.0, Playwright 1.56.1  
**修復人員**: GitHub Copilot CLI
