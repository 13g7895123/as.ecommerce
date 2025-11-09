/**
 * 類別模擬資料
 */

import type { Category } from '~/types/category'

export const mockCategories: Category[] = [
  {
    id: 'cat-electronics',
    name: '電子產品',
    slug: 'electronics',
    description: '最新的電子產品與配件',
    image: '/images/categories/electronics.jpg',
    order: 1
  },
  {
    id: 'cat-clothing',
    name: '服飾',
    slug: 'clothing',
    description: '時尚服飾與配件',
    image: '/images/categories/clothing.jpg',
    order: 2
  },
  {
    id: 'cat-home',
    name: '居家生活',
    slug: 'home',
    description: '居家用品與生活雜貨',
    image: '/images/categories/home.jpg',
    order: 3
  },
  {
    id: 'cat-sports',
    name: '運動休閒',
    slug: 'sports',
    description: '運動用品與戶外裝備',
    image: '/images/categories/sports.jpg',
    order: 4
  },
  {
    id: 'cat-books',
    name: '書籍文具',
    slug: 'books',
    description: '書籍、文具與辦公用品',
    image: '/images/categories/books.jpg',
    order: 5
  }
]

export function getCategoryById(id: string): Category | undefined {
  return mockCategories.find((c) => c.id === id)
}

export function getCategoryBySlug(slug: string): Category | undefined {
  return mockCategories.find((c) => c.slug === slug)
}
