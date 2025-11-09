/**
 * 購物車相關型別定義
 */

export interface CartItem {
  productId: string
  productName: string
  productImage: string
  price: number
  quantity: number
  stock: number
  specifications?: Record<string, string>
}

export interface Cart {
  items: CartItem[]
  subtotal: number
  shipping: number
  total: number
  itemCount: number
}

export interface AddToCartPayload {
  productId: string
  quantity: number
  specifications?: Record<string, string>
}

export interface UpdateCartItemPayload {
  productId: string
  quantity: number
}
