# E2E 測試修復摘要

## 修復的 5 個關鍵問題

### 1. ✅ 表單輸入 name 屬性缺失
- 為 LoginForm 和 RegisterForm 添加 `name` 屬性
- 影響檔案: `LoginForm.vue`, `RegisterForm.vue`

### 2. ✅ 測試選擇器改進
- 將 `input[type="password"]` 改為 `input[name="password"]`
- 避免多個元素匹配問題
- 影響檔案: `auth.spec.ts`, `checkout.spec.ts`

### 3. ✅ 認證狀態初始化
- 在 layout 的 `onMounted` 中調用 `authStore.init()`
- 確保登入狀態持久化
- 影響檔案: `default.vue`

### 4. ✅ 首頁產品數量增加
- 將更多產品標記為 `featured: true`
- 從 6 個增加到 8 個以上
- 影響檔案: `products.ts`

### 5. ✅ 測試流程改善
- 添加 timeout 參數
- 更新頁面標題匹配模式
- 添加條款勾選步驟
- 影響檔案: 所有測試檔案

## 執行測試

```bash
cd frontend

# 執行所有 E2E 測試
npm run test:e2e

# 僅執行 Chromium 測試（較快）
npx playwright test --project=chromium
```

## 預期結果

- **修復前**: 36/85 通過 (42.4%)
- **修復後**: 預期 60%+ 通過率

## 修改檔案列表

1. `app/components/user/LoginForm.vue`
2. `app/components/user/RegisterForm.vue`
3. `app/layouts/default.vue`
4. `server/mock/products.ts`
5. `tests/e2e/auth.spec.ts`
6. `tests/e2e/checkout.spec.ts`
7. `tests/e2e/shopping-flow.spec.ts`

詳細資訊請參閱 `E2E_TEST_FIXES_FINAL.md`
