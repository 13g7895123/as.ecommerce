/**
 * useProducts Composable
 * 產品查詢相關邏輯封裝
 */

import type { Product, ProductListQuery } from '~/types/product'

export function useProducts() {
  const store = useProductsStore()
  const router = useRouter()

  /**
   * 取得熱門產品
   */
  const fetchFeaturedProducts = async (limit = 8) => {
    try {
      return await store.fetchFeaturedProducts(limit)
    } catch (error) {
      console.error('Failed to fetch featured products:', error)
      return []
    }
  }

  /**
   * 取得產品列表
   */
  const fetchProducts = async (query?: ProductListQuery) => {
    try {
      return await store.fetchProducts(query)
    } catch (error) {
      console.error('Failed to fetch products:', error)
      throw error
    }
  }

  /**
   * 取得單一產品
   */
  const fetchProduct = async (id: string) => {
    try {
      return await store.fetchProductById(id)
    } catch (error) {
      console.error('Failed to fetch product:', error)
      throw error
    }
  }

  /**
   * 前往產品詳情頁
   */
  const goToProduct = (id: string) => {
    router.push(`/products/${id}`)
  }

  /**
   * 前往分類頁面
   */
  const goToCategory = (categoryId: string) => {
    router.push(`/products/category/${categoryId}`)
  }

  /**
   * 搜尋產品
   */
  const searchProducts = async (searchTerm: string) => {
    try {
      return await store.fetchProducts({ search: searchTerm })
    } catch (error) {
      console.error('Failed to search products:', error)
      throw error
    }
  }

  return {
    // State
    products: computed(() => store.products),
    featuredProducts: computed(() => store.featuredProducts),
    selectedProduct: computed(() => store.selectedProduct),
    loading: computed(() => store.loading),
    error: computed(() => store.error),

    // Actions
    fetchFeaturedProducts,
    fetchProducts,
    fetchProduct,
    goToProduct,
    goToCategory,
    searchProducts,

    // Utilities
    clearCache: store.clearCache,
    reset: store.reset
  }
}
