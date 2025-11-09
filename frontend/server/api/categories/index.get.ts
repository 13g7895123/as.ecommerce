/**
 * GET /api/categories
 * 取得所有產品類別
 */

import { mockCategories } from '../../mock/categories'

export default defineEventHandler(() => {
  return {
    categories: mockCategories
  }
})
