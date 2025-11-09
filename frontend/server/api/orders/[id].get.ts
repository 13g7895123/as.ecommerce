/**
 * GET /api/orders/:id
 * 取得訂單詳情
 */

import type { Order } from '~/types/order'

export default defineEventHandler(async (event): Promise<Order> => {
  const id = getRouterParam(event, 'id')

  if (!id) {
    throw createError({
      statusCode: 400,
      statusMessage: '訂單 ID 為必填'
    })
  }

  // 模擬訂單資料
  // 實際應用中應從資料庫查詢
  const order: Order = {
    id,
    userId: 'user-1',
    items: [],
    shippingInfo: {
      name: '測試使用者',
      phone: '0912345678',
      city: '台北市',
      district: '中正區',
      address: '測試路123號',
      postalCode: '100'
    },
    paymentMethod: 'credit-card',
    subtotal: 1000,
    shipping: 0,
    total: 1000,
    status: 'pending',
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  }

  return order
})
