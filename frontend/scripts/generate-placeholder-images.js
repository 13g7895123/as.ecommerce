#!/usr/bin/env node
/**
 * 生成產品佔位圖片的腳本
 */

import fs from 'fs'
import path from 'path'
import { fileURLToPath } from 'url'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)

// SVG 模板
function createSVG(filename, isThumb = false) {
  const width = isThumb ? 300 : 800
  const height = isThumb ? 300 : 600
  const productName = filename
    .replace(/-thumb\.jpg$/, '')
    .replace(/-\d+\.jpg$/, '')
    .replace(/-/g, ' ')
    .toUpperCase()

  return `<?xml version="1.0" encoding="UTF-8"?>
<svg width="${width}" height="${height}" xmlns="http://www.w3.org/2000/svg">
  <rect width="100%" height="100%" fill="#e5e7eb"/>
  <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="24" fill="#9ca3af" text-anchor="middle" dominant-baseline="middle">
    ${productName}
  </text>
  <text x="50%" y="60%" font-family="Arial, sans-serif" font-size="14" fill="#6b7280" text-anchor="middle" dominant-baseline="middle">
    ${width} × ${height}
  </text>
</svg>`
}

// 需要生成的圖片列表（從 products.ts 提取）
const images = [
  // 耳機
  'earbuds-thumb.jpg',
  'earbuds-1.jpg',
  'earbuds-2.jpg',
  // 手錶
  'watch-thumb.jpg',
  'watch-1.jpg',
  'watch-2.jpg',
  // 充電器
  'charger-thumb.jpg',
  'charger-1.jpg',
  // 外套
  'jacket-thumb.jpg',
  'jacket-1.jpg',
  'jacket-2.jpg',
  // T恤
  'tshirt-thumb.jpg',
  'tshirt-1.jpg',
  // 枕頭
  'pillow-thumb.jpg',
  'pillow-1.jpg',
  // 蠟燭
  'candle-thumb.jpg',
  'candle-1.jpg',
  // 毛毯
  'blanket-thumb.jpg',
  'blanket-1.jpg',
  // 行動電源
  'powerbank-thumb.jpg',
  'powerbank-1.jpg',
  // 鍵盤
  'keyboard-thumb.jpg',
  'keyboard-1.jpg',
  'keyboard-2.jpg',
  // Hub
  'hub-thumb.jpg',
  'hub-1.jpg',
  // 網路攝影機
  'webcam-thumb.jpg',
  'webcam-1.jpg'
]

// 輸出目錄
const outputDir = path.join(__dirname, '..', 'public', 'images', 'products')

// 確保目錄存在
if (!fs.existsSync(outputDir)) {
  fs.mkdirSync(outputDir, { recursive: true })
}

// 生成圖片
let count = 0
images.forEach((filename) => {
  const isThumb = filename.includes('thumb')
  const svgContent = createSVG(filename, isThumb)
  const outputPath = path.join(outputDir, filename)

  fs.writeFileSync(outputPath, svgContent)
  count++
  console.log(`✓ 已生成: ${filename}`)
})

console.log(`\n✅ 成功生成 ${count} 個佔位圖片`)
