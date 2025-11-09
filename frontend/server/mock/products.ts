/**
 * 產品模擬資料
 */

import type { Product } from '~/types/product'

export const mockProducts: Product[] = [
  // 電子產品類別
  {
    id: 'prod-001',
    name: '無線藍牙耳機 Pro',
    description: '高品質音質，主動降噪功能，長達30小時續航力。採用最新藍牙5.3技術，提供穩定連接。符合人體工學設計，佩戴舒適不易掉落。',
    shortDescription: '高品質音質，主動降噪，30小時續航',
    price: 2990,
    originalPrice: 3990,
    images: ['/images/products/earbuds-1.jpg', '/images/products/earbuds-2.jpg'],
    thumbnail: '/images/products/earbuds-thumb.jpg',
    categoryId: 'cat-electronics',
    stock: 50,
    sku: 'EBP-001',
    featured: true,
    createdAt: '2025-10-01T00:00:00Z',
    updatedAt: '2025-10-20T00:00:00Z'
  },
  {
    id: 'prod-002',
    name: '智慧手錶 X1',
    description: '全天候健康監測，支援50+運動模式。1.4吋AMOLED螢幕，7天續航。防水等級IP68，游泳可戴。',
    shortDescription: '健康監測，50+運動模式，7天續航',
    price: 5990,
    originalPrice: 7990,
    images: ['/images/products/watch-1.jpg', '/images/products/watch-2.jpg'],
    thumbnail: '/images/products/watch-thumb.jpg',
    categoryId: 'cat-electronics',
    stock: 30,
    sku: 'SW-X1-001',
    featured: true,
    createdAt: '2025-09-15T00:00:00Z',
    updatedAt: '2025-10-18T00:00:00Z'
  },
  {
    id: 'prod-003',
    name: '無線充電板',
    description: '15W快速無線充電，支援多裝置同時充電。智慧溫控，安全防護。相容iPhone、Android等主流手機。',
    shortDescription: '15W快充，多裝置充電，智慧溫控',
    price: 890,
    images: ['/images/products/charger-1.jpg'],
    thumbnail: '/images/products/charger-thumb.jpg',
    categoryId: 'cat-electronics',
    stock: 100,
    sku: 'WC-15W-001',
    featured: false,
    createdAt: '2025-10-10T00:00:00Z',
    updatedAt: '2025-10-20T00:00:00Z'
  },
  // 服飾類別
  {
    id: 'prod-004',
    name: '經典牛仔外套',
    description: '100%純棉丹寧布料，耐穿舒適。經典設計，四季皆宜。多個口袋設計，實用性佳。',
    shortDescription: '純棉丹寧，經典設計，四季皆宜',
    price: 1590,
    originalPrice: 2290,
    images: ['/images/products/jacket-1.jpg', '/images/products/jacket-2.jpg'],
    thumbnail: '/images/products/jacket-thumb.jpg',
    categoryId: 'cat-clothing',
    stock: 45,
    sku: 'CJ-001-M',
    featured: true,
    createdAt: '2025-09-20T00:00:00Z',
    updatedAt: '2025-10-15T00:00:00Z'
  },
  {
    id: 'prod-005',
    name: '舒適棉質T恤',
    description: '精梳棉材質，透氣吸汗。簡約設計，多色可選。日常百搭款式。',
    shortDescription: '精梳棉，透氣舒適，多色可選',
    price: 490,
    images: ['/images/products/tshirt-1.jpg'],
    thumbnail: '/images/products/tshirt-thumb.jpg',
    categoryId: 'cat-clothing',
    stock: 200,
    sku: 'TS-001-L',
    featured: false,
    createdAt: '2025-10-05T00:00:00Z',
    updatedAt: '2025-10-20T00:00:00Z'
  },
  // 居家生活類別
  {
    id: 'prod-006',
    name: '記憶枕',
    description: '慢回彈記憶棉，完美貼合頸部曲線。透氣網面設計，保持乾爽。附贈可拆洗枕套。',
    shortDescription: '記憶棉，貼合頸部，透氣舒適',
    price: 1290,
    images: ['/images/products/pillow-1.jpg'],
    thumbnail: '/images/products/pillow-thumb.jpg',
    categoryId: 'cat-home',
    stock: 60,
    sku: 'MP-001',
    featured: true,
    createdAt: '2025-09-25T00:00:00Z',
    updatedAt: '2025-10-19T00:00:00Z'
  },
  {
    id: 'prod-007',
    name: '香氛蠟燭組',
    description: '天然大豆蠟，植物精油調香。燃燒時間長達40小時。包含三種香氛：薰衣草、檸檬、玫瑰。',
    shortDescription: '天然大豆蠟，三種香氛，40小時',
    price: 690,
    images: ['/images/products/candle-1.jpg'],
    thumbnail: '/images/products/candle-thumb.jpg',
    categoryId: 'cat-home',
    stock: 80,
    sku: 'SC-SET-001',
    featured: false,
    createdAt: '2025-10-08T00:00:00Z',
    updatedAt: '2025-10-20T00:00:00Z'
  },
  {
    id: 'prod-008',
    name: '智能溫控電熱毯',
    description: '多段溫度調節，定時功能。柔軟法蘭絨材質，可水洗。過熱自動斷電保護。',
    shortDescription: '智能溫控，定時功能，可水洗',
    price: 1890,
    images: ['/images/products/blanket-1.jpg'],
    thumbnail: '/images/products/blanket-thumb.jpg',
    categoryId: 'cat-home',
    stock: 35,
    sku: 'EB-001',
    featured: true,
    createdAt: '2025-09-30T00:00:00Z',
    updatedAt: '2025-10-18T00:00:00Z'
  },
  // 更多電子產品
  {
    id: 'prod-009',
    name: '行動電源 20000mAh',
    description: '大容量20000mAh，可充手機4-5次。支援PD快充，雙USB輸出。LED電量顯示。',
    shortDescription: '20000mAh大容量，PD快充',
    price: 1290,
    images: ['/images/products/powerbank-1.jpg'],
    thumbnail: '/images/products/powerbank-thumb.jpg',
    categoryId: 'cat-electronics',
    stock: 70,
    sku: 'PB-20K-001',
    featured: true,
    createdAt: '2025-10-12T00:00:00Z',
    updatedAt: '2025-10-20T00:00:00Z'
  },
  {
    id: 'prod-010',
    name: '機械式鍵盤',
    description: 'Cherry MX軸，RGB背光。鋁合金外殼，耐用穩固。可自訂按鍵功能。',
    shortDescription: 'Cherry軸，RGB背光，鋁合金',
    price: 3290,
    originalPrice: 3990,
    images: ['/images/products/keyboard-1.jpg', '/images/products/keyboard-2.jpg'],
    thumbnail: '/images/products/keyboard-thumb.jpg',
    categoryId: 'cat-electronics',
    stock: 25,
    sku: 'KB-MEC-001',
    featured: true,
    createdAt: '2025-09-18T00:00:00Z',
    updatedAt: '2025-10-17T00:00:00Z'
  },
  {
    id: 'prod-011',
    name: 'USB-C 多功能擴充座',
    description: '7合1擴充，含HDMI、USB 3.0、SD卡槽等。支援4K@60Hz影像輸出。鋁合金材質散熱佳。',
    shortDescription: '7合1擴充，4K輸出，鋁合金',
    price: 1590,
    images: ['/images/products/hub-1.jpg'],
    thumbnail: '/images/products/hub-thumb.jpg',
    categoryId: 'cat-electronics',
    stock: 55,
    sku: 'HUB-7IN1-001',
    featured: true,
    createdAt: '2025-10-14T00:00:00Z',
    updatedAt: '2025-10-20T00:00:00Z'
  },
  {
    id: 'prod-012',
    name: '網路攝影機 HD',
    description: '1080P高清畫質，自動對焦。內建雙麥克風降噪。支援多平台視訊軟體。',
    shortDescription: '1080P高清，自動對焦，降噪',
    price: 1790,
    images: ['/images/products/webcam-1.jpg'],
    thumbnail: '/images/products/webcam-thumb.jpg',
    categoryId: 'cat-electronics',
    stock: 40,
    sku: 'WC-HD-001',
    featured: true,
    createdAt: '2025-10-16T00:00:00Z',
    updatedAt: '2025-10-20T00:00:00Z'
  }
]

export function getProductById(id: string): Product | undefined {
  return mockProducts.find((p) => p.id === id)
}

export function getProductsByCategory(categoryId: string): Product[] {
  return mockProducts.filter((p) => p.categoryId === categoryId)
}

export function getFeaturedProducts(limit = 8): Product[] {
  return mockProducts.filter((p) => p.featured).slice(0, limit)
}

export function searchProducts(query: string): Product[] {
  const lowerQuery = query.toLowerCase()
  return mockProducts.filter(
    (p) =>
      p.name.toLowerCase().includes(lowerQuery) ||
      p.description.toLowerCase().includes(lowerQuery)
  )
}
