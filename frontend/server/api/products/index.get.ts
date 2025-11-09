/**
 * GET /api/products
 * 取得產品列表
 */

import { mockProducts, getProductsByCategory } from '../../mock/products'
import type { ProductListQuery, ProductListResponse } from '~/types/product'

export default defineEventHandler((event): ProductListResponse => {
  const query = getQuery(event) as ProductListQuery

  let products = mockProducts

  // 依類別篩選
  if (query.categoryId) {
    products = getProductsByCategory(query.categoryId)
  }

  // 搜尋功能
  if (query.search) {
    const searchTerm = query.search.toLowerCase()
    products = products.filter(
      (p) =>
        p.name.toLowerCase().includes(searchTerm) ||
        p.description.toLowerCase().includes(searchTerm)
    )
  }

  // 排序
  if (query.sort) {
    products = [...products].sort((a, b) => {
      switch (query.sort) {
        case 'price-asc':
          return a.price - b.price
        case 'price-desc':
          return b.price - a.price
        case 'newest':
          return new Date(b.createdAt).getTime() - new Date(a.createdAt).getTime()
        case 'popular':
          // 模擬熱門度排序（使用 featured 與 stock）
          return (b.featured ? 1 : 0) - (a.featured ? 1 : 0)
        default:
          return 0
      }
    })
  }

  // 分頁
  const page = query.page || 1
  const limit = query.limit || 12
  const startIndex = (page - 1) * limit
  const endIndex = startIndex + limit
  const paginatedProducts = products.slice(startIndex, endIndex)

  return {
    products: paginatedProducts,
    total: products.length,
    page,
    limit,
    hasMore: endIndex < products.length
  }
})
