/**
 * POST /api/orders
 * 建立訂單
 */

import type { CreateOrderPayload, Order } from '~/types/order'

export default defineEventHandler(async (event): Promise<Order> => {
  const body = await readBody<CreateOrderPayload>(event)

  // 驗證必填欄位
  if (!body.items || body.items.length === 0) {
    throw createError({
      statusCode: 400,
      statusMessage: '訂單中沒有商品'
    })
  }

  if (!body.shippingInfo) {
    throw createError({
      statusCode: 400,
      statusMessage: '收件資訊為必填'
    })
  }

  if (!body.paymentMethod) {
    throw createError({
      statusCode: 400,
      statusMessage: '付款方式為必填'
    })
  }

  // 建立訂單
  const order: Order = {
    id: `ORD${Date.now()}`,
    userId: 'user-1', // 模擬使用者 ID
    items: body.items,
    shippingInfo: body.shippingInfo,
    paymentMethod: body.paymentMethod,
    subtotal: body.subtotal,
    shipping: body.shipping,
    total: body.total,
    status: 'pending',
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  }

  return order
})
