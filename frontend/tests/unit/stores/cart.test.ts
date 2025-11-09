/**
 * Cart Store Tests
 */

import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useCartStore } from '~/stores/cart'
import type { Product } from '~/types/product'

describe('Cart Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
  })

  const mockProduct: Product = {
    id: 'test-1',
    name: 'Test Product',
    price: 100,
    description: 'Test Description',
    image: 'test.jpg',
    category: 'electronics',
    stock: 10,
    rating: 4.5,
    reviews: 10
  }

  describe('addItem', () => {
    it('should add a new item to the cart', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })

      expect(cart.items).toHaveLength(1)
      expect(cart.items[0].productId).toBe(mockProduct.id)
      expect(cart.items[0].quantity).toBe(1)
    })

    it('should increase quantity if item already exists', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })

      expect(cart.items).toHaveLength(1)
      expect(cart.items[0].quantity).toBe(2)
    })

    it('should not exceed stock limit', async () => {
      const cart = useCartStore()
      const limitedProduct = { ...mockProduct, stock: 2 }
      global.$fetch = vi.fn().mockResolvedValue(limitedProduct)
      
      await cart.addItem({ productId: limitedProduct.id, quantity: 2 })
      
      await expect(
        cart.addItem({ productId: limitedProduct.id, quantity: 1 })
      ).rejects.toThrow('超過庫存數量')

      expect(cart.items[0].quantity).toBe(2)
    })
  })

  describe('removeItem', () => {
    it('should remove item from cart', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })
      cart.removeItem(mockProduct.id)

      expect(cart.items).toHaveLength(0)
    })
  })

  describe('updateItemQuantity', () => {
    it('should update item quantity', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })
      cart.updateItemQuantity({ productId: mockProduct.id, quantity: 3 })

      expect(cart.items[0].quantity).toBe(3)
    })

    it('should remove item if quantity is 0', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })
      cart.updateItemQuantity({ productId: mockProduct.id, quantity: 0 })

      expect(cart.items).toHaveLength(0)
    })

    it('should not exceed stock limit', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })
      
      expect(() => {
        cart.updateItemQuantity({ productId: mockProduct.id, quantity: 100 })
      }).toThrow('超過庫存數量')
    })
  })

  describe('cart calculations', () => {
    it('should calculate correct subtotal', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 2 })

      expect(cart.cart.subtotal).toBe(200)
    })

    it('should calculate shipping correctly', async () => {
      const cart = useCartStore()
      const highPriceProduct = { ...mockProduct, price: 200 }
      global.$fetch = vi.fn().mockResolvedValue(highPriceProduct)
      
      await cart.addItem({ productId: highPriceProduct.id, quantity: 1 })

      expect(cart.cart.shipping).toBe(100)

      cart.updateItemQuantity({ productId: highPriceProduct.id, quantity: 6 })
      expect(cart.cart.shipping).toBe(0)
    })

    it('should calculate total correctly', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 2 })

      const expectedTotal = 200 + 100
      expect(cart.cart.total).toBe(expectedTotal)
    })
  })

  describe('clear', () => {
    it('should clear all items', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })
      cart.clear()

      expect(cart.items).toHaveLength(0)
      expect(cart.cart.subtotal).toBe(0)
      expect(cart.cart.shipping).toBe(100)
      expect(cart.cart.total).toBe(100)
    })
  })

  describe('getters', () => {
    it('itemCount should return correct count', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      await cart.addItem({ productId: mockProduct.id, quantity: 2 })

      expect(cart.cart.itemCount).toBe(2)
    })

    it('isEmpty should return correct value', async () => {
      const cart = useCartStore()
      expect(cart.isEmpty).toBe(true)

      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      await cart.addItem({ productId: mockProduct.id, quantity: 1 })

      expect(cart.isEmpty).toBe(false)
    })

    it('hasProduct should work correctly', async () => {
      const cart = useCartStore()
      global.$fetch = vi.fn().mockResolvedValue(mockProduct)
      
      expect(cart.hasProduct(mockProduct.id)).toBe(false)

      await cart.addItem({ productId: mockProduct.id, quantity: 1 })

      expect(cart.hasProduct(mockProduct.id)).toBe(true)
    })
  })
})
