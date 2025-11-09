import { test, expect } from '@playwright/test'

test.describe('會員系統 E2E 測試', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('http://localhost:3000')
  })

  test('應該能夠訪問登入頁面', async ({ page }) => {
    // 點擊登入連結
    await page.click('a[href="/login"]')
    
    // 驗證導航到登入頁面
    await expect(page).toHaveURL(/\/login/)
    
    // 驗證登入表單存在
    await expect(page.locator('input[name="email"]')).toBeVisible()
    await expect(page.locator('input[name="password"]')).toBeVisible()
    await expect(page.locator('button[type="submit"]')).toBeVisible()
  })

  test('應該能夠訪問註冊頁面', async ({ page }) => {
    // 點擊註冊連結
    await page.click('a[href="/register"]')
    
    // 驗證導航到註冊頁面
    await expect(page).toHaveURL(/\/register/)
    
    // 驗證註冊表單存在
    await expect(page.locator('input[name="name"]')).toBeVisible()
    await expect(page.locator('input[name="email"]')).toBeVisible()
    await expect(page.locator('input[name="password"]')).toBeVisible()
    await expect(page.locator('input[name="confirmPassword"]')).toBeVisible()
  })

  test('應該驗證登入表單必填欄位', async ({ page }) => {
    await page.goto('http://localhost:3000/login')
    
    // 嘗試不填寫直接提交
    await page.click('button[type="submit"]')
    
    // 驗證錯誤訊息顯示（HTML5 驗證或自訂驗證）
    const emailInput = page.locator('input[name="email"]')
    await expect(emailInput).toBeFocused().catch(() => {
      // 如果不是 HTML5 驗證，檢查自訂錯誤訊息
      return expect(page.locator('text=請輸入')).toBeVisible()
    })
  })

  test('應該能夠使用測試帳號登入', async ({ page }) => {
    await page.goto('http://localhost:3000/login')
    
    // 填寫測試帳號
    await page.fill('input[name="email"]', 'test@example.com')
    await page.fill('input[name="password"]', 'password123')
    
    // 提交表單
    await page.click('button[type="submit"]')
    
    // 等待導航（可能跳轉到首頁或會員中心）
    await page.waitForURL(/\/(|orders|profile)/, { timeout: 5000 })
    
    // 驗證登入成功（檢查是否出現登出按鈕）
    const logoutButton = page.locator('button:has-text("登出")')
    await expect(logoutButton).toBeVisible({ timeout: 5000 })
  })

  test('應該能夠註冊新帳號', async ({ page }) => {
    await page.goto('http://localhost:3000/register')
    
    // 產生唯一的測試帳號
    const timestamp = Date.now()
    const testEmail = `test${timestamp}@example.com`
    
    // 填寫註冊表單
    await page.fill('input[name="name"]', '測試使用者')
    await page.fill('input[name="email"]', testEmail)
    await page.fill('input[name="phone"]', '0912345678')
    await page.fill('input[name="password"]', 'Password123!')
    await page.fill('input[name="confirmPassword"]', 'Password123!')
    
    // 勾選條款
    await page.check('input[type="checkbox"]#terms')
    
    // 提交表單
    await page.click('button[type="submit"]')
    
    // 驗證註冊成功（可能跳轉到首頁或自動登入）
    await page.waitForURL(/\/(|login)/, { timeout: 5000 })
  })

  test('應該能夠登出', async ({ page }) => {
    // 先登入
    await page.goto('http://localhost:3000/login')
    await page.fill('input[name="email"]', 'test@example.com')
    await page.fill('input[name="password"]', 'password123')
    await page.click('button[type="submit"]')
    
    // 等待登入完成
    await page.waitForURL(/\//, { timeout: 5000 })
    
    // 點擊登出
    const logoutButton = page.locator('button:has-text("登出")')
    await logoutButton.click()
    
    // 處理可能的確認對話框
    page.on('dialog', dialog => dialog.accept())
    
    // 驗證登出成功（登入按鈕再次出現）
    await expect(page.locator('a[href="/login"]')).toBeVisible({ timeout: 5000 })
  })
})
