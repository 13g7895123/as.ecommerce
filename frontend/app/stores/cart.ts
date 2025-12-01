/**
 * Cart Store - Pinia
 * 管理購物車狀態
 */

import { defineStore } from 'pinia'
import type { Cart, CartItem, AddToCartPayload, UpdateCartItemPayload } from '~/types/cart'
import { saveGuestCart, loadGuestCart } from '~/utils/storage'
import { calculateShipping } from '~/utils/currency'

interface CartState {
  items: CartItem[]
  loading: boolean
  error: string | null
}

export const useCartStore = defineStore('cart', {
  state: (): CartState => ({
    items: [],
    loading: false,
    error: null
  }),

  getters: {
    /**
     * 取得購物車
     */
    cart: (state): Cart => {
      const subtotal = state.items.reduce((sum, item) => sum + item.price * item.quantity, 0)
      const shipping = calculateShipping(subtotal)
      const total = subtotal + shipping

      return {
        items: state.items,
        subtotal,
        shipping,
        total,
        itemCount: state.items.reduce((sum, item) => sum + item.quantity, 0)
      }
    },

    /**
     * 檢查購物車是否為空
     */
    isEmpty: (state): boolean => {
      return state.items.length === 0
    },

    /**
     * 取得指定產品的購物車項目
     */
    getItemByProductId: (state) => {
      return (productId: string): CartItem | undefined => {
        return state.items.find((item) => item.productId === productId)
      }
    },

    /**
     * 檢查產品是否在購物車中
     */
    hasProduct: (state) => {
      return (productId: string): boolean => {
        return state.items.some((item) => item.productId === productId)
      }
    }
  },

  actions: {
    /**
     * 初始化購物車（從 localStorage 載入）
     */
    init(): void {
      const savedCart = loadGuestCart()
      if (savedCart && savedCart.items) {
        this.items = savedCart.items
      }
    },

    /**
     * 加入商品到購物車
     */
    async addItem(payload: AddToCartPayload): Promise<void> {
      this.loading = true
      this.error = null
      const productsStore = useProductsStore()

      try {
        // 取得產品詳情
        const product = await productsStore.fetchProductById(payload.productId)

        // 檢查庫存
        if (product.stock < payload.quantity) {
          throw new Error('庫存不足')
        }

        // 檢查是否已存在
        const existingItem = this.items.find((item) => item.productId === payload.productId)

        if (existingItem) {
          // 更新數量
          const newQuantity = existingItem.quantity + payload.quantity
          if (newQuantity > product.stock) {
            throw new Error('超過庫存數量')
          }
          existingItem.quantity = newQuantity
        } else {
          // 新增項目
          const cartItem: CartItem = {
            productId: product.id,
            productName: product.name,
            productImage: product.thumbnail,
            price: product.price,
            quantity: payload.quantity,
            stock: product.stock,
            specifications: payload.specifications
          }
          this.items.push(cartItem)
        }

        // 儲存到 localStorage
        this.saveToStorage()
      } catch (err: any) {
        this.error = err.message || '加入購物車失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 更新購物車項目數量
     */
    updateItemQuantity(payload: UpdateCartItemPayload): void {
      const item = this.items.find((i) => i.productId === payload.productId)
      if (!item) {
        throw new Error('找不到該商品')
      }

      if (payload.quantity <= 0) {
        this.removeItem(payload.productId)
        return
      }

      if (payload.quantity > item.stock) {
        throw new Error('超過庫存數量')
      }

      item.quantity = payload.quantity
      this.saveToStorage()
    },

    /**
     * 移除購物車項目
     */
    removeItem(productId: string): void {
      const index = this.items.findIndex((item) => item.productId === productId)
      if (index !== -1) {
        this.items.splice(index, 1)
        this.saveToStorage()
      }
    },

    /**
     * 清空購物車
     */
    clear(): void {
      this.items = []
      this.saveToStorage()
    },

    /**
     * 儲存到 localStorage
     */
    saveToStorage(): void {
      saveGuestCart({ items: this.items })
    },

    /**
     * 重設狀態
     */
    reset(): void {
      this.items = []
      this.loading = false
      this.error = null
    }
  }
})
