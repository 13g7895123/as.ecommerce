/**
 * useCart Composable
 * 購物車相關邏輯封裝
 */

import type { AddToCartPayload, UpdateCartItemPayload } from '~/types/cart'

export function useCart() {
  const store = useCartStore()
  const router = useRouter()

  // 初始化購物車
  onMounted(() => {
    store.init()
  })

  /**
   * 加入商品到購物車
   */
  const addToCart = async (payload: AddToCartPayload) => {
    try {
      await store.addItem(payload)
      return true
    } catch (error: any) {
      console.error('Failed to add to cart:', error)
      throw error
    }
  }

  /**
   * 更新購物車項目數量
   */
  const updateQuantity = (payload: UpdateCartItemPayload) => {
    try {
      store.updateItemQuantity(payload)
      return true
    } catch (error: any) {
      console.error('Failed to update quantity:', error)
      throw error
    }
  }

  /**
   * 從購物車移除商品
   */
  const removeFromCart = (productId: string) => {
    try {
      store.removeItem(productId)
      return true
    } catch (error: any) {
      console.error('Failed to remove from cart:', error)
      throw error
    }
  }

  /**
   * 清空購物車
   */
  const clearCart = () => {
    store.clear()
  }

  /**
   * 前往購物車頁面
   */
  const goToCart = () => {
    router.push('/cart')
  }

  /**
   * 前往結帳頁面
   */
  const goToCheckout = () => {
    if (store.isEmpty) {
      console.warn('Cart is empty')
      return
    }
    router.push('/checkout')
  }

  /**
   * 檢查產品是否在購物車中
   */
  const isInCart = (productId: string): boolean => {
    return store.hasProduct(productId)
  }

  /**
   * 取得產品在購物車中的數量
   */
  const getProductQuantity = (productId: string): number => {
    const item = store.getItemByProductId(productId)
    return item?.quantity || 0
  }

  return {
    // State
    cart: computed(() => store.cart),
    items: computed(() => store.items),
    isEmpty: computed(() => store.isEmpty),
    loading: computed(() => store.loading),
    error: computed(() => store.error),
    itemCount: computed(() => store.cart.itemCount),

    // Actions
    addToCart,
    updateQuantity,
    removeFromCart,
    clearCart,
    goToCart,
    goToCheckout,
    isInCart,
    getProductQuantity
  }
}
