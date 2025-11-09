<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-primary-600 to-primary-800 py-16 text-white">
      <div class="container-custom">
        <div class="text-center">
          <h1 class="mb-4 text-5xl font-bold">購物網站</h1>
          <p class="mb-8 text-xl text-primary-100">
            探索我們精選的熱門產品，享受優質購物體驗
          </p>
          <div class="flex items-center justify-center gap-4">
            <button class="btn bg-white text-primary-600 hover:bg-gray-100">
              立即購物
            </button>
            <button class="btn border-2 border-white bg-transparent hover:bg-white hover:text-primary-600">
              瞭解更多
            </button>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Products Section -->
    <section class="py-16">
      <div class="container-custom">
        <!-- Section Header -->
        <div class="mb-12 text-center">
          <h2 class="mb-4 text-4xl font-bold text-gray-900">熱門商品</h2>
          <p class="text-lg text-gray-600">
            精選最受歡迎的商品，為您帶來最佳選擇
          </p>
        </div>

        <!-- Product Grid -->
        <ProductGrid
          :products="featuredProducts"
          :loading="loading"
          :error="error"
          empty-message="目前沒有熱門商品"
          @product-click="handleProductClick"
          @add-to-cart="handleAddToCart"
          @view-detail="handleViewDetail"
          @retry="loadProducts"
        />
      </div>
    </section>

    <!-- Features Section -->
    <section class="bg-white py-16">
      <div class="container-custom">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
          <div class="text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary-100">
              <span class="text-3xl">🚚</span>
            </div>
            <h3 class="mb-2 text-xl font-semibold">快速配送</h3>
            <p class="text-gray-600">滿千免運，快速送達</p>
          </div>
          <div class="text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary-100">
              <span class="text-3xl">🔒</span>
            </div>
            <h3 class="mb-2 text-xl font-semibold">安全付款</h3>
            <p class="text-gray-600">多種付款方式，安全有保障</p>
          </div>
          <div class="text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-primary-100">
              <span class="text-3xl">💝</span>
            </div>
            <h3 class="mb-2 text-xl font-semibold">品質保證</h3>
            <p class="text-gray-600">嚴選商品，品質有保障</p>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup lang="ts">
import type { Product } from '~/types/product'

// SEO
useHead({
  title: '首頁 - 購物網站',
  meta: [
    {
      name: 'description',
      content: '探索我們精選的熱門產品，享受優質購物體驗。滿千免運，快速配送。'
    }
  ]
})

// 使用 products composable
const { featuredProducts, loading, error, fetchFeaturedProducts, goToProduct } = useProducts()
const { addToCart } = useCart()

// 載入熱門產品
const loadProducts = async () => {
  try {
    await fetchFeaturedProducts(8)
  } catch (err) {
    console.error('Failed to load featured products:', err)
  }
}

// 頁面載入時執行
onMounted(() => {
  loadProducts()
})

// 處理產品點擊
const handleProductClick = (product: Product) => {
  goToProduct(product.id)
}

// 處理查看詳情
const handleViewDetail = (product: Product) => {
  goToProduct(product.id)
}

// 處理加入購物車
const handleAddToCart = async (product: Product) => {
  try {
    await addToCart({
      productId: product.id,
      quantity: 1
    })
    alert(`已將「${product.name}」加入購物車`)
  } catch (error: any) {
    alert(error.message || '加入購物車失敗')
  }
}
</script>

