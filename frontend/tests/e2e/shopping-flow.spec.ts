import { test, expect } from '@playwright/test'

test.describe('購物流程 E2E 測試', () => {
  test.beforeEach(async ({ page }) => {
    await page.goto('http://localhost:3000')
  })

  test('應該顯示首頁與產品列表', async ({ page }) => {
    // 驗證首頁載入
    await expect(page).toHaveTitle(/購物網站|ecommerce/)
    
    // 驗證產品卡片顯示
    const productCards = page.locator('[data-testid="product-card"]')
    await expect(productCards.first()).toBeVisible({ timeout: 10000 })
    
    // 驗證至少有 8 個產品
    const count = await productCards.count()
    expect(count).toBeGreaterThanOrEqual(8)
  })

  test('應該能夠瀏覽產品類別', async ({ page }) => {
    // 等待類別導航載入
    await page.waitForSelector('nav', { timeout: 5000 })
    
    // 點擊第一個類別（假設類別名稱包含"電子"）
    const categoryLink = page.locator('text=電子產品').first()
    if (await categoryLink.isVisible()) {
      await categoryLink.click()
      
      // 驗證 URL 變更
      await expect(page).toHaveURL(/\/products\/category\//)
      
      // 驗證產品仍然顯示
      const productCards = page.locator('[data-testid="product-card"]')
      await expect(productCards.first()).toBeVisible()
    }
  })

  test('應該能夠加入購物車', async ({ page }) => {
    // 等待產品載入
    const firstProduct = page.locator('[data-testid="product-card"]').first()
    await firstProduct.waitFor({ state: 'visible' })
    
    // 點擊加入購物車按鈕
    const addToCartButton = firstProduct.locator('button:has-text("加入購物車")')
    if (await addToCartButton.isVisible()) {
      await addToCartButton.click()
      
      // 驗證購物車數量更新
      const cartBadge = page.locator('[data-testid="cart-badge"]')
      await expect(cartBadge).toBeVisible()
      await expect(cartBadge).toHaveText('1')
    }
  })

  test('應該能夠查看購物車', async ({ page }) => {
    // 點擊購物車圖示
    const cartLink = page.locator('a[href="/cart"]')
    await cartLink.click()
    
    // 驗證導航到購物車頁面
    await expect(page).toHaveURL(/\/cart/)
    
    // 驗證頁面標題
    await expect(page.locator('h1')).toContainText('購物車')
  })

  test('完整購物流程：瀏覽 → 加入購物車 → 結帳', async ({ page }) => {
    // 1. 瀏覽產品
    await page.waitForSelector('[data-testid="product-card"]')
    
    // 2. 加入產品到購物車
    const firstProduct = page.locator('[data-testid="product-card"]').first()
    const addButton = firstProduct.locator('button:has-text("加入購物車")')
    if (await addButton.isVisible()) {
      await addButton.click()
      
      // 等待 toast 通知
      await page.waitForTimeout(500)
      
      // 3. 前往購物車
      await page.click('a[href="/cart"]')
      await expect(page).toHaveURL(/\/cart/)
      
      // 4. 驗證購物車有商品
      const cartItems = page.locator('[data-testid="cart-item"]')
      await expect(cartItems.first()).toBeVisible()
      
      // 5. 點擊結帳按鈕
      const checkoutButton = page.locator('button:has-text("前往結帳")')
      if (await checkoutButton.isVisible()) {
        await checkoutButton.click()
        
        // 驗證導航到結帳頁面或登入頁面
        await page.waitForURL(/\/(checkout|login)/)
      }
    }
  })
})
