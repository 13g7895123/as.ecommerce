/**
 * 產品相關型別定義
 */

export interface Product {
  id: string
  name: string
  description: string
  shortDescription: string
  price: number
  originalPrice?: number
  images: string[]
  thumbnail: string
  categoryId: string
  stock: number
  sku: string
  specifications?: Record<string, string>
  tags?: string[]
  featured: boolean
  createdAt: string
  updatedAt: string
}

export interface ProductListQuery {
  categoryId?: string
  sort?: 'price-asc' | 'price-desc' | 'newest' | 'popular'
  page?: number
  limit?: number
  search?: string
}

export interface ProductListResponse {
  products: Product[]
  total: number
  page: number
  limit: number
  hasMore: boolean
}
