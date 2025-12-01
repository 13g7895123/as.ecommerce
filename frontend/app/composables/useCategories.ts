/**
 * useCategories Composable
 * 類別查詢相關邏輯封裝
 */

import type { Category } from '~/types/category'

export function useCategories() {
  const categories = ref<Category[]>([])
  const loading = ref(false)
  const error = ref<string | null>(null)
  const router = useRouter()

  /**
   * 取得所有類別
   */
  const fetchCategories = async () => {
    loading.value = true
    error.value = null

    try {
      const api = useApi()
      const response = await api<Category[]>('/categories')
      categories.value = response
      return response
    } catch (err: any) {
      error.value = err.message || '載入類別失敗'
      console.error('Failed to fetch categories:', err)
      throw err
    } finally {
      loading.value = false
    }
  }

  /**
   * 根據 ID 取得類別
   */
  const getCategoryById = (id: string): Category | undefined => {
    return categories.value.find((c) => c.id === id)
  }

  /**
   * 根據 slug 取得類別
   */
  const getCategoryBySlug = (slug: string): Category | undefined => {
    return categories.value.find((c) => c.slug === slug)
  }

  /**
   * 前往分類頁面
   */
  const goToCategory = (slug: string) => {
    router.push(`/products/category/${slug}`)
  }

  /**
   * 返回首頁
   */
  const goToHome = () => {
    router.push('/')
  }

  return {
    // State
    categories: readonly(categories),
    loading: readonly(loading),
    error: readonly(error),

    // Actions
    fetchCategories,
    getCategoryById,
    getCategoryBySlug,
    goToCategory,
    goToHome
  }
}
