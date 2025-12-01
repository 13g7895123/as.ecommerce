<script setup lang="ts">
import { useProductsStore } from '@/stores/products'
import { useCartStore } from '@/stores/cart'

const route = useRoute()
const productsStore = useProductsStore()
const cartStore = useCartStore()
const { success, error: showError } = useToast()

const productId = route.params.id as string
const product = ref(null)
const loading = ref(true)
const quantity = ref(1)

// 載入產品詳情
onMounted(async () => {
  try {
    const data = await productsStore.fetchProductById(productId)
    product.value = data
  } catch (err) {
    showError('無法載入產品資訊')
    console.error('Failed to load product:', err)
  } finally {
    loading.value = false
  }
})

// 加入購物車
async function addToCart() {
  if (!product.value) return

  try {
    await cartStore.addItem({
      productId: product.value.id,
      quantity: quantity.value,
      specifications: product.value.specifications
    })
    success(`已加入 ${quantity.value} 件商品至購物車`)
    quantity.value = 1
  } catch (err) {
    showError('加入購物車失敗')
    console.error('Failed to add to cart:', err)
  }
}

// 增加數量
function increaseQuantity() {
  if (product.value && quantity.value < product.value.stock) {
    quantity.value++
  }
}

// 減少數量
function decreaseQuantity() {
  if (quantity.value > 1) {
    quantity.value--
  }
}
</script>

<template>
  <div class="container-custom py-8">
    <!-- Loading State -->
    <div v-if="loading" class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <div class="skeleton h-96 rounded-lg"></div>
      <div class="space-y-4">
        <div class="skeleton h-10 w-3/4"></div>
        <div class="skeleton h-8 w-1/4"></div>
        <div class="skeleton h-24 w-full"></div>
        <div class="skeleton h-12 w-full"></div>
      </div>
    </div>

    <!-- Product Detail -->
    <div v-else-if="product" class="grid grid-cols-1 md:grid-cols-2 gap-8">
      <!-- Product Image -->
      <div class="bg-white rounded-lg overflow-hidden shadow">
        <img
          :src="product.images[0]?.url || '/placeholder-product.jpg'"
          :alt="product.name"
          class="w-full h-full object-cover"
        />
      </div>

      <!-- Product Info -->
      <div class="space-y-6">
        <!-- Breadcrumb -->
        <nav class="text-sm text-gray-600">
          <NuxtLink to="/" class="hover:text-primary-600">首頁</NuxtLink>
          <span class="mx-2">/</span>
          <span>{{ product.name }}</span>
        </nav>

        <!-- Product Name -->
        <h1 class="text-3xl font-bold text-gray-900">
          {{ product.name }}
        </h1>

        <!-- Rating -->
        <div v-if="product.rating" class="flex items-center gap-2">
          <div class="flex items-center">
            <svg v-for="i in 5" :key="i" class="w-5 h-5" :class="i <= product.rating ? 'text-yellow-400' : 'text-gray-300'" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <span class="text-sm text-gray-600">({{ product.reviewCount }} 則評價)</span>
        </div>

        <!-- Price -->
        <div class="flex items-baseline gap-3">
          <span class="text-3xl font-bold text-primary-600">
            {{ formatCurrency(product.price) }}
          </span>
          <span v-if="product.originalPrice && product.originalPrice > product.price" class="text-xl text-gray-400 line-through">
            {{ formatCurrency(product.originalPrice) }}
          </span>
        </div>

        <!-- Description -->
        <div class="border-t pt-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-3">商品描述</h2>
          <p class="text-gray-600 leading-relaxed">
            {{ product.description }}
          </p>
        </div>

        <!-- Specs -->
        <div v-if="product.specifications && Object.keys(product.specifications).length > 0" class="border-t pt-6">
          <h2 class="text-lg font-semibold text-gray-900 mb-3">商品規格</h2>
          <dl class="space-y-2">
            <div v-for="(value, key) in product.specifications" :key="key" class="flex">
              <dt class="w-24 text-gray-600">{{ key }}</dt>
              <dd class="text-gray-900">{{ value }}</dd>
            </div>
          </dl>
        </div>

        <!-- Stock -->
        <div class="flex items-center gap-2">
          <span class="text-sm text-gray-600">庫存：</span>
          <span :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'" class="text-sm font-medium">
            {{ product.stock > 0 ? `${product.stock} 件` : '已售完' }}
          </span>
        </div>

        <!-- Quantity Selector -->
        <div class="flex items-center gap-4">
          <span class="text-sm text-gray-600">數量：</span>
          <div class="flex items-center border rounded-lg">
            <button
              type="button"
              class="px-4 py-2 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="quantity <= 1"
              @click="decreaseQuantity"
            >
              -
            </button>
            <input
              v-model.number="quantity"
              type="number"
              min="1"
              :max="product.stock"
              class="w-16 text-center border-x py-2 focus:outline-none"
              readonly
            />
            <button
              type="button"
              class="px-4 py-2 hover:bg-gray-100 disabled:opacity-50 disabled:cursor-not-allowed"
              :disabled="quantity >= product.stock"
              @click="increaseQuantity"
            >
              +
            </button>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex gap-4">
          <BaseButton
            variant="primary"
            size="lg"
            full-width
            :disabled="product.stock === 0"
            @click="addToCart"
          >
            加入購物車
          </BaseButton>
          <BaseButton
            variant="outline"
            size="lg"
            @click="navigateTo('/cart')"
          >
            查看購物車
          </BaseButton>
        </div>
      </div>
    </div>

    <!-- Error State -->
    <div v-else class="text-center py-16">
      <p class="text-gray-600 mb-4">找不到此商品</p>
      <BaseButton @click="navigateTo('/')">返回首頁</BaseButton>
    </div>
  </div>
</template>
