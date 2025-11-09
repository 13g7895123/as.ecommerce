/**
 * GET /api/products/:id
 * 取得單一產品詳情
 */

import { getProductById } from '../../mock/products'

export default defineEventHandler((event) => {
  const id = getRouterParam(event, 'id')

  if (!id) {
    throw createError({
      statusCode: 400,
      statusMessage: '缺少產品 ID'
    })
  }

  const product = getProductById(id)

  if (!product) {
    throw createError({
      statusCode: 404,
      statusMessage: '找不到該產品'
    })
  }

  return product
})
