/**
 * Order Store - Pinia
 * 管理訂單狀態
 */

import { defineStore } from 'pinia'
import type { Order, CreateOrderPayload } from '~/types/order'

interface OrderState {
  orders: Order[]
  currentOrder: Order | null
  loading: boolean
  error: string | null
}

export const useOrderStore = defineStore('order', {
  state: (): OrderState => ({
    orders: [],
    currentOrder: null,
    loading: false,
    error: null
  }),

  getters: {
    /**
     * 取得指定訂單
     */
    getOrderById: (state) => {
      return (id: string): Order | undefined => {
        return state.orders.find((o) => o.id === id)
      }
    },

    /**
     * 檢查是否有訂單
     */
    hasOrders: (state): boolean => {
      return state.orders.length > 0
    }
  },

  actions: {
    /**
     * 建立訂單
     */
    async createOrder(payload: CreateOrderPayload): Promise<Order> {
      this.loading = true
      this.error = null

      try {
        const order = await $fetch<Order>('/api/orders', {
          method: 'POST',
          body: payload
        })

        this.currentOrder = order
        this.orders.unshift(order)

        return order
      } catch (err: any) {
        this.error = err.data?.message || err.message || '建立訂單失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 取得訂單列表
     */
    async fetchOrders(): Promise<Order[]> {
      this.loading = true
      this.error = null

      try {
        const response = await $fetch<{ orders: Order[] }>('/api/orders')
        this.orders = response.orders
        return response.orders
      } catch (err: any) {
        this.error = err.data?.message || err.message || '載入訂單失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 取得單一訂單
     */
    async fetchOrderById(id: string): Promise<Order> {
      this.loading = true
      this.error = null

      try {
        const order = await $fetch<Order>(`/api/orders/${id}`)
        this.currentOrder = order

        // 更新列表中的訂單
        const index = this.orders.findIndex((o) => o.id === id)
        if (index >= 0) {
          this.orders[index] = order
        } else {
          this.orders.push(order)
        }

        return order
      } catch (err: any) {
        this.error = err.data?.message || err.message || '載入訂單失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 重設狀態
     */
    reset(): void {
      this.orders = []
      this.currentOrder = null
      this.loading = false
      this.error = null
    }
  }
})
