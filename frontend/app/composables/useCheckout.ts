/**
 * useCheckout Composable
 * 結帳流程相關邏輯封裝
 */

import type { CreateOrderPayload } from '~/types/order'
import type { ShippingInfo } from '~/types/address'

export function useCheckout() {
  const orderStore = useOrderStore()
  const cartStore = useCartStore()
  const authStore = useAuthStore()
  const router = useRouter()

  /**
   * 建立訂單
   */
  const createOrder = async (shippingInfo: ShippingInfo, paymentMethod: string) => {
    try {
      // 準備訂單資料
      const payload: CreateOrderPayload = {
        items: cartStore.items,
        shippingInfo,
        paymentMethod,
        subtotal: cartStore.cart.subtotal,
        shipping: cartStore.cart.shipping,
        total: cartStore.cart.total
      }

      // 建立訂單
      const order = await orderStore.createOrder(payload)

      // 清空購物車
      cartStore.clear()

      return order
    } catch (error: any) {
      console.error('Failed to create order:', error)
      throw error
    }
  }

  /**
   * 驗證結帳條件
   */
  const validateCheckout = (): { valid: boolean; message?: string } => {
    // 檢查購物車
    if (cartStore.isEmpty) {
      return { valid: false, message: '購物車是空的' }
    }

    // 檢查登入狀態
    if (!authStore.isAuthenticated) {
      return { valid: false, message: '請先登入' }
    }

    return { valid: true }
  }

  /**
   * 前往訂單詳情頁
   */
  const goToOrder = (orderId: string) => {
    router.push(`/orders/${orderId}`)
  }

  /**
   * 前往訂單列表頁
   */
  const goToOrders = () => {
    router.push('/orders')
  }

  return {
    // State
    currentOrder: computed(() => orderStore.currentOrder),
    loading: computed(() => orderStore.loading),
    error: computed(() => orderStore.error),

    // Actions
    createOrder,
    validateCheckout,
    goToOrder,
    goToOrders
  }
}
