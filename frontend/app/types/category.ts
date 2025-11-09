/**
 * 產品類別相關型別定義
 */

export interface Category {
  id: string
  name: string
  slug: string
  description?: string
  parentId?: string
  image?: string
  order: number
  children?: Category[]
}

export interface CategoryTree extends Category {
  children: CategoryTree[]
}
