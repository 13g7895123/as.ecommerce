import { test, expect } from '@playwright/test'

test.describe('結帳流程 E2E 測試', () => {
  test.beforeEach(async ({ page, context }) => {
    // 清除 cookies 和 storage
    await context.clearCookies()
    await page.goto('http://localhost:3000')
  })

  test('應該能夠從購物車前往結帳', async ({ page }) => {
    // 1. 加入產品到購物車
    await page.waitForSelector('[data-testid="product-card"]')
    const addButton = page.locator('[data-testid="product-card"]').first().locator('button:has-text("加入購物車")')
    
    if (await addButton.isVisible()) {
      await addButton.click()
      await page.waitForTimeout(500)
      
      // 2. 前往購物車
      await page.click('a[href="/cart"]')
      await expect(page).toHaveURL(/\/cart/)
      
      // 3. 點擊結帳按鈕
      const checkoutButton = page.locator('button:has-text("前往結帳")')
      if (await checkoutButton.isVisible()) {
        await checkoutButton.click()
        
        // 驗證導航到結帳或登入頁面
        await page.waitForURL(/\/(checkout|login)/)
      }
    }
  })

  test('未登入使用者應該被導向登入頁面', async ({ page }) => {
    // 直接訪問結帳頁面
    await page.goto('http://localhost:3000/checkout')
    
    // 應該被重新導向到登入頁面
    await expect(page).toHaveURL(/\/login/, { timeout: 5000 })
  })

  test('已登入使用者應該能夠訪問結帳頁面', async ({ page }) => {
    // 1. 先登入
    await page.goto('http://localhost:3000/login')
    await page.fill('input[name="email"]', 'test@example.com')
    await page.fill('input[name="password"]', 'password123')
    await page.click('button[type="submit"]')
    await page.waitForURL(/\//)
    
    // 2. 加入產品
    await page.waitForSelector('[data-testid="product-card"]', { timeout: 10000 })
    const addButton = page.locator('[data-testid="product-card"]').first().locator('button:has-text("加入購物車")')
    if (await addButton.isVisible()) {
      await addButton.click()
      await page.waitForTimeout(500)
    }
    
    // 3. 前往結帳
    await page.goto('http://localhost:3000/checkout')
    
    // 驗證在結帳頁面
    await expect(page).toHaveURL(/\/checkout/)
    
    // 驗證結帳表單元素存在
    await expect(page.locator('input[name="name"]')).toBeVisible()
  })

  test('應該驗證收件資訊必填欄位', async ({ page }) => {
    // 登入並前往結帳
    await page.goto('http://localhost:3000/login')
    await page.fill('input[name="email"]', 'test@example.com')
    await page.fill('input[name="password"]', 'password123')
    await page.click('button[type="submit"]')
    await page.waitForURL(/\//)
    
    // 加入產品
    await page.waitForSelector('[data-testid="product-card"]', { timeout: 10000 })
    const addButton = page.locator('[data-testid="product-card"]').first().locator('button:has-text("加入購物車")')
    if (await addButton.isVisible()) {
      await addButton.click()
      await page.waitForTimeout(500)
    }
    
    await page.goto('http://localhost:3000/checkout')
    
    // 嘗試不填寫直接提交
    const submitButton = page.locator('button[type="submit"]:has-text("送出訂單")')
    if (await submitButton.isVisible()) {
      await submitButton.click()
      
      // 驗證錯誤訊息或焦點在必填欄位
      await expect(page.locator('text=/請輸入|必填/')).toBeVisible().catch(() => {
        return expect(page.locator('input[required]').first()).toBeFocused()
      })
    }
  })

  test('應該能夠完成訂單', async ({ page }) => {
    // 1. 登入
    await page.goto('http://localhost:3000/login')
    await page.fill('input[name="email"]', 'test@example.com')
    await page.fill('input[name="password"]', 'password123')
    await page.click('button[type="submit"]')
    await page.waitForURL(/\//)
    
    // 2. 加入產品
    await page.waitForSelector('[data-testid="product-card"]', { timeout: 10000 })
    const addButton = page.locator('[data-testid="product-card"]').first().locator('button:has-text("加入購物車")')
    if (await addButton.isVisible()) {
      await addButton.click()
      await page.waitForTimeout(500)
    }
    
    // 3. 前往結帳
    await page.goto('http://localhost:3000/checkout')
    
    // 4. 填寫收件資訊
    await page.fill('input[name="name"]', '測試收件人')
    await page.fill('input[name="phone"]', '0912345678')
    await page.fill('input[name="address"]', '台北市信義區信義路五段7號')
    
    // 5. 選擇付款方式（如果有）
    const paymentRadio = page.locator('input[type="radio"][value="credit_card"]')
    if (await paymentRadio.isVisible()) {
      await paymentRadio.check()
    }
    
    // 6. 送出訂單
    const submitButton = page.locator('button[type="submit"]:has-text("送出訂單")')
    if (await submitButton.isVisible()) {
      await submitButton.click()
      
      // 驗證導航到訂單完成頁面
      await expect(page).toHaveURL(/\/(checkout\/success|orders)/, { timeout: 10000 })
      
      // 驗證訂單編號或成功訊息
      await expect(page.locator('text=/訂單編號|訂單成功/')).toBeVisible()
    }
  })

  test('應該顯示訂單摘要與總金額', async ({ page }) => {
    // 登入
    await page.goto('http://localhost:3000/login')
    await page.fill('input[name="email"]', 'test@example.com')
    await page.fill('input[name="password"]', 'password123')
    await page.click('button[type="submit"]')
    await page.waitForURL(/\//)
    
    // 加入產品
    await page.waitForSelector('[data-testid="product-card"]', { timeout: 10000 })
    const addButton = page.locator('[data-testid="product-card"]').first().locator('button:has-text("加入購物車")')
    if (await addButton.isVisible()) {
      await addButton.click()
      await page.waitForTimeout(500)
    }
    
    // 前往結帳
    await page.goto('http://localhost:3000/checkout')
    
    // 驗證訂單摘要元素
    await expect(page.locator('text=小計')).toBeVisible()
    await expect(page.locator('text=運費')).toBeVisible()
    await expect(page.locator('text=總計')).toBeVisible()
    
    // 驗證金額格式（NT$）
    const totalAmount = page.locator('text=/NT\\$\\s*\\d{1,3}(,\\d{3})*/').first()
    await expect(totalAmount).toBeVisible()
  })
})
