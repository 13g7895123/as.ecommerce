/**
 * GET /api/orders
 * 取得訂單列表
 */

import type { Order } from '~/types/order'

export default defineEventHandler(async (event): Promise<{ orders: Order[] }> => {
  // 模擬訂單列表
  // 實際應用中應從資料庫查詢並根據使用者 ID 過濾
  const orders: Order[] = [
    {
      id: 'ORD1729876543210',
      userId: 'user-1',
      items: [
        {
          productId: 'prod-1',
          productName: '無線藍牙耳機',
          productImage: 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=400',
          price: 1299,
          quantity: 1,
          stock: 50
        }
      ],
      shippingInfo: {
        name: '測試使用者',
        phone: '0912345678',
        city: '台北市',
        district: '中正區',
        address: '測試路123號',
        postalCode: '100'
      },
      paymentMethod: 'credit-card',
      subtotal: 1299,
      shipping: 0,
      total: 1299,
      status: 'delivered',
      createdAt: new Date('2024-10-20').toISOString(),
      updatedAt: new Date('2024-10-23').toISOString()
    },
    {
      id: 'ORD1729776543210',
      userId: 'user-1',
      items: [
        {
          productId: 'prod-2',
          productName: '智慧手環',
          productImage: 'https://images.unsplash.com/photo-1575311373937-040b8e1fd5b6?w=400',
          price: 899,
          quantity: 2,
          stock: 100
        }
      ],
      shippingInfo: {
        name: '測試使用者',
        phone: '0912345678',
        city: '台北市',
        district: '中正區',
        address: '測試路123號',
        postalCode: '100'
      },
      paymentMethod: 'atm',
      subtotal: 1798,
      shipping: 0,
      total: 1798,
      status: 'shipped',
      createdAt: new Date('2024-10-18').toISOString(),
      updatedAt: new Date('2024-10-19').toISOString()
    },
    {
      id: 'ORD1729676543210',
      userId: 'user-1',
      items: [
        {
          productId: 'prod-3',
          productName: '運動水壺',
          productImage: 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=400',
          price: 299,
          quantity: 1,
          stock: 200
        }
      ],
      shippingInfo: {
        name: '測試使用者',
        phone: '0912345678',
        city: '台北市',
        district: '中正區',
        address: '測試路123號',
        postalCode: '100'
      },
      paymentMethod: 'cod',
      subtotal: 299,
      shipping: 60,
      total: 359,
      status: 'processing',
      createdAt: new Date('2024-10-15').toISOString(),
      updatedAt: new Date('2024-10-15').toISOString()
    }
  ]

  return { orders }
})
