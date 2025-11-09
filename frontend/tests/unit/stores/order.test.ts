/**
 * Order Store Tests
 */

import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useOrderStore } from '~/stores/order'
import type { CreateOrderPayload, Order } from '~/types/order'

// Mock $fetch
global.$fetch = vi.fn()

describe('Order Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
  })

  const mockOrder: Order = {
    id: 'ORD123',
    userId: 'user-1',
    items: [
      {
        productId: 'prod-1',
        productName: 'Test Product',
        productImage: 'test.jpg',
        price: 100,
        quantity: 2,
        stock: 10
      }
    ],
    shippingInfo: {
      name: 'Test User',
      phone: '0912345678',
      city: '台北市',
      district: '中正區',
      address: '測試路123號',
      postalCode: '100'
    },
    paymentMethod: 'credit-card',
    subtotal: 200,
    shipping: 0,
    total: 200,
    status: 'pending',
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  }

  describe('createOrder', () => {
    it('should create order successfully', async () => {
      vi.mocked($fetch).mockResolvedValueOnce(mockOrder)

      const orderStore = useOrderStore()
      const payload: CreateOrderPayload = {
        items: mockOrder.items,
        shippingInfo: mockOrder.shippingInfo,
        paymentMethod: mockOrder.paymentMethod,
        subtotal: mockOrder.subtotal,
        shipping: mockOrder.shipping,
        total: mockOrder.total
      }

      const result = await orderStore.createOrder(payload)

      expect(result).toEqual(mockOrder)
      expect(orderStore.currentOrder).toEqual(mockOrder)
      expect(orderStore.orders).toHaveLength(1)
      expect(orderStore.orders[0]).toEqual(mockOrder)
    })

    it('should handle create order errors', async () => {
      vi.mocked($fetch).mockRejectedValueOnce(new Error('Order creation failed'))

      const orderStore = useOrderStore()
      const payload: CreateOrderPayload = {
        items: [],
        shippingInfo: mockOrder.shippingInfo,
        paymentMethod: mockOrder.paymentMethod,
        subtotal: 0,
        shipping: 0,
        total: 0
      }

      await expect(orderStore.createOrder(payload)).rejects.toThrow()
      expect(orderStore.error).toBeTruthy()
    })
  })

  describe('fetchOrders', () => {
    it('should fetch orders successfully', async () => {
      const mockOrders = [mockOrder]
      vi.mocked($fetch).mockResolvedValueOnce({ orders: mockOrders })

      const orderStore = useOrderStore()
      const result = await orderStore.fetchOrders()

      expect(result).toEqual(mockOrders)
      expect(orderStore.orders).toEqual(mockOrders)
    })
  })

  describe('fetchOrderById', () => {
    it('should fetch single order successfully', async () => {
      vi.mocked($fetch).mockResolvedValueOnce(mockOrder)

      const orderStore = useOrderStore()
      const result = await orderStore.fetchOrderById('ORD123')

      expect(result).toEqual(mockOrder)
      expect(orderStore.currentOrder).toEqual(mockOrder)
    })

    it('should update existing order in list', async () => {
      const orderStore = useOrderStore()
      orderStore.orders = [mockOrder]

      const updatedOrder = { ...mockOrder, status: 'shipped' as const }
      vi.mocked($fetch).mockResolvedValueOnce(updatedOrder)

      await orderStore.fetchOrderById('ORD123')

      expect(orderStore.orders[0].status).toBe('shipped')
    })
  })

  describe('getters', () => {
    it('getOrderById should return correct order', () => {
      const orderStore = useOrderStore()
      orderStore.orders = [mockOrder]

      const result = orderStore.getOrderById('ORD123')
      expect(result).toEqual(mockOrder)
    })

    it('hasOrders should return correct value', () => {
      const orderStore = useOrderStore()
      expect(orderStore.hasOrders).toBe(false)

      orderStore.orders = [mockOrder]
      expect(orderStore.hasOrders).toBe(true)
    })
  })

  describe('reset', () => {
    it('should reset all state', () => {
      const orderStore = useOrderStore()
      orderStore.orders = [mockOrder]
      orderStore.currentOrder = mockOrder
      orderStore.loading = true
      orderStore.error = 'Some error'

      orderStore.reset()

      expect(orderStore.orders).toEqual([])
      expect(orderStore.currentOrder).toBeNull()
      expect(orderStore.loading).toBe(false)
      expect(orderStore.error).toBeNull()
    })
  })
})
