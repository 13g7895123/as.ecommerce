/**
 * Order Store - Pinia
 * 管理訂單狀態
 */

import { defineStore } from 'pinia'
import type { Order, CreateOrderPayload, OrderListQuery, OrderListResponse } from '~/types/order'

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
      const api = useApi()

      // 轉換 payload 格式以符合 API 需求
      const apiPayload = {
        items: payload.items.map(item => ({
          id: item.productId,
          quantity: item.quantity
        })),
        shippingInfo: payload.shippingInfo,
        paymentMethod: payload.paymentMethod
      }

      try {
        const order = await api<Order>('/orders', {
          method: 'POST',
          body: apiPayload
        })

        this.currentOrder = order
        this.orders.unshift(order)

        return order
      } catch (err: any) {
        this.error = err.data?.statusMessage || err.message || '建立訂單失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 取得訂單列表
     */
    async fetchOrders(query?: OrderListQuery): Promise<OrderListResponse> {
      this.loading = true
      this.error = null
      const api = useApi()

      try {
        const response = await api<OrderListResponse>('/orders', {
          query: query as Record<string, any>
        })
        this.orders = response.orders
        return response
      } catch (err: any) {
        this.error = err.data?.statusMessage || err.message || '載入訂單失敗'
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
      const api = useApi()

      try {
        const order = await api<Order>(`/orders/${id}`)
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
        this.error = err.data?.statusMessage || err.message || '載入訂單失敗'
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
