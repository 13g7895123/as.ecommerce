/**
 * Products Store - Pinia
 * 管理產品資料狀態與快取
 */

import { defineStore } from 'pinia'
import type { Product, ProductListQuery, ProductListResponse } from '~/types/product'

interface ProductsState {
  products: Product[]
  featuredProducts: Product[]
  selectedProduct: Product | null
  loading: boolean
  error: string | null
  cache: Map<string, { data: ProductListResponse; timestamp: number }>
}

const CACHE_DURATION = 5 * 60 * 1000 // 5 分鐘快取

export const useProductsStore = defineStore('products', {
  state: (): ProductsState => ({
    products: [],
    featuredProducts: [],
    selectedProduct: null,
    loading: false,
    error: null,
    cache: new Map()
  }),

  getters: {
    /**
     * 取得指定 ID 的產品
     */
    getProductById: (state) => {
      return (id: string): Product | undefined => {
        return state.products.find((p) => p.id === id) || undefined
      }
    },

    /**
     * 檢查是否有產品資料
     */
    hasProducts: (state): boolean => {
      return state.products.length > 0
    },

    /**
     * 檢查是否有熱門產品
     */
    hasFeaturedProducts: (state): boolean => {
      return state.featuredProducts.length > 0
    }
  },

  actions: {
    /**
     * 取得產品列表
     */
    async fetchProducts(query?: ProductListQuery): Promise<ProductListResponse> {
      const cacheKey = JSON.stringify(query || {})
      const cached = this.cache.get(cacheKey)

      // 檢查快取
      if (cached && Date.now() - cached.timestamp < CACHE_DURATION) {
        this.products = cached.data.products
        return cached.data
      }

      this.loading = true
      this.error = null

      try {
        const response = await $fetch<ProductListResponse>('/api/products', {
          query: query as Record<string, any>
        })

        this.products = response.products

        // 儲存快取
        this.cache.set(cacheKey, {
          data: response,
          timestamp: Date.now()
        })

        return response
      } catch (err: any) {
        this.error = err.message || '載入產品失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 取得熱門產品
     */
    async fetchFeaturedProducts(limit = 8): Promise<Product[]> {
      this.loading = true
      this.error = null

      try {
        // 請求足夠多的產品以確保有足夠的精選產品
        const response = await this.fetchProducts({ limit: 50 })
        this.featuredProducts = response.products.filter((p) => p.featured).slice(0, limit)
        return this.featuredProducts
      } catch (err: any) {
        this.error = err.message || '載入熱門產品失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 取得單一產品詳情
     */
    async fetchProductById(id: string): Promise<Product> {
      // 先檢查 store 中是否已有資料
      const existing = this.getProductById(id)
      if (existing) {
        this.selectedProduct = existing
        return existing
      }

      this.loading = true
      this.error = null

      try {
        const product = await $fetch<Product>(`/api/products/${id}`)
        this.selectedProduct = product

        // 加入到產品列表中
        const index = this.products.findIndex((p) => p.id === id)
        if (index >= 0) {
          this.products[index] = product
        } else {
          this.products.push(product)
        }

        return product
      } catch (err: any) {
        this.error = err.message || '載入產品失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 清除快取
     */
    clearCache(): void {
      this.cache.clear()
    },

    /**
     * 重設狀態
     */
    reset(): void {
      this.products = []
      this.featuredProducts = []
      this.selectedProduct = null
      this.loading = false
      this.error = null
      this.cache.clear()
    }
  }
})
