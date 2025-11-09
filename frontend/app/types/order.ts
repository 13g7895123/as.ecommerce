/**
 * 訂單相關型別定義
 */

import type { CartItem } from './cart'

export type OrderStatus = 'pending' | 'processing' | 'shipped' | 'delivering' | 'completed' | 'cancelled'

export type PaymentMethod = 'credit_card' | 'atm' | 'cod'

export interface OrderItem extends CartItem {
  orderId: string
}

export interface ShippingInfo {
  recipientName: string
  recipientPhone: string
  city: string
  district: string
  address: string
  postalCode: string
}

export interface Order {
  id: string
  userId: string
  orderNumber: string
  items: OrderItem[]
  subtotal: number
  shipping: number
  discount: number
  total: number
  status: OrderStatus
  paymentMethod: PaymentMethod
  shippingInfo: ShippingInfo
  trackingNumber?: string
  estimatedDelivery?: string
  createdAt: string
  updatedAt: string
}

export interface CreateOrderPayload {
  items: CartItem[]
  shippingInfo: ShippingInfo
  paymentMethod: PaymentMethod
}

export interface OrderListQuery {
  page?: number
  limit?: number
  status?: OrderStatus
}

export interface OrderListResponse {
  orders: Order[]
  total: number
  page: number
  limit: number
}
