/**
 * 貨幣格式化工具函式
 */

/**
 * 格式化金額為新台幣顯示格式
 */
export function formatCurrency(amount: number): string {
  if (amount < 0) {
    return `-NT$${Math.abs(amount).toLocaleString('zh-TW')}`
  }
  return `NT$${amount.toLocaleString('zh-TW')}`
}

/**
 * 格式化金額（不含貨幣符號）
 */
export function formatNumber(amount: number): string {
  return amount.toLocaleString('zh-TW')
}

/**
 * 計算折扣百分比
 */
export function calculateDiscountPercentage(originalPrice: number, salePrice: number): number {
  if (originalPrice <= 0) return 0
  return Math.round(((originalPrice - salePrice) / originalPrice) * 100)
}

/**
 * 格式化折扣百分比
 */
export function formatDiscount(originalPrice: number, salePrice: number): string {
  const discount = calculateDiscountPercentage(originalPrice, salePrice)
  return `${discount}% OFF`
}

/**
 * 計算購物車運費
 * 訂單滿 NT$1000 免運費，未滿則收取固定運費 NT$100
 */
export function calculateShipping(subtotal: number): number {
  const FREE_SHIPPING_THRESHOLD = 1000
  const SHIPPING_FEE = 100

  return subtotal >= FREE_SHIPPING_THRESHOLD ? 0 : SHIPPING_FEE
}

/**
 * 計算還差多少金額可免運
 */
export function calculateFreeShippingRemaining(subtotal: number): number {
  const FREE_SHIPPING_THRESHOLD = 1000
  const remaining = FREE_SHIPPING_THRESHOLD - subtotal

  return remaining > 0 ? remaining : 0
}
