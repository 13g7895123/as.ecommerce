<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container-custom">
      <!-- Breadcrumb -->
      <nav class="mb-6 flex items-center gap-2 text-sm text-gray-600">
        <NuxtLink to="/" class="hover:text-primary-600">首頁</NuxtLink>
        <span>/</span>
        <span class="font-medium text-gray-900">{{ category?.name || '類別' }}</span>
      </nav>

      <!-- Category Header -->
      <div class="mb-8">
        <h1 class="mb-2 text-4xl font-bold text-gray-900">
          {{ category?.name || '產品類別' }}
        </h1>
        <p v-if="category?.description" class="text-lg text-gray-600">
          {{ category.description }}
        </p>
      </div>

      <!-- Filters and Sort -->
      <div class="mb-6 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <!-- Product Count -->
        <div class="text-sm text-gray-600">
          <span v-if="!loading">共 {{ totalProducts }} 件商品</span>
        </div>

        <!-- Sort Selector -->
        <div class="flex items-center gap-2">
          <label for="sort" class="text-sm font-medium text-gray-700">排序：</label>
          <select
            id="sort"
            v-model="sortBy"
            class="input w-48"
            @change="handleSortChange"
          >
            <option value="">預設排序</option>
            <option value="price-asc">價格：低到高</option>
            <option value="price-desc">價格：高到低</option>
            <option value="newest">最新上架</option>
            <option value="popular">最受歡迎</option>
          </select>
        </div>
      </div>

      <!-- Product Grid -->
      <ProductGrid
        :products="products"
        :loading="loading"
        :error="error"
        :has-more="hasMore"
        empty-message="此類別目前沒有商品"
        @product-click="handleProductClick"
        @add-to-cart="handleAddToCart"
        @view-detail="handleViewDetail"
        @load-more="loadMore"
        @retry="loadProducts"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Product } from '~/types/product'
import type { Category } from '~/types/category'
import type { ProductListQuery } from '~/types/product'

const route = useRoute()
const router = useRouter()

// Get category slug from route
const slug = computed(() => route.params.slug as string)

// Composables
const { getCategoryBySlug } = useCategories()
const { products, loading, error, fetchProducts, goToProduct } = useProducts()
const { addToCart } = useCart()

// Local state
const category = ref<Category | null>(null)
const totalProducts = ref(0)
const hasMore = ref(false)
const currentPage = ref(1)
const sortBy = ref<string>('')

// SEO
useHead(() => ({
  title: `${category.value?.name || '類別'} - 購物網站`,
  meta: [
    {
      name: 'description',
      content: category.value?.description || '瀏覽我們精選的商品分類'
    }
  ]
}))

// Load products
const loadProducts = async (reset = true) => {
  if (reset) {
    currentPage.value = 1
  }

  const categoryData = getCategoryBySlug(slug.value)
  category.value = categoryData || null

  if (!categoryData) {
    error.value = '找不到此類別'
    return
  }

  try {
    const query: ProductListQuery = {
      categoryId: categoryData.id,
      page: currentPage.value,
      limit: 12
    }

    if (sortBy.value) {
      query.sort = sortBy.value as any
    }

    const response = await fetchProducts(query)
    totalProducts.value = response.total
    hasMore.value = response.hasMore
  } catch (err) {
    console.error('Failed to load products:', err)
  }
}

// Load more products
const loadMore = async () => {
  currentPage.value++
  await loadProducts(false)
}

// Handle sort change
const handleSortChange = () => {
  loadProducts(true)
}

// Handle product click
const handleProductClick = (product: Product) => {
  goToProduct(product.id)
}

// Handle view detail
const handleViewDetail = (product: Product) => {
  goToProduct(product.id)
}

// Handle add to cart
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

// Watch for slug changes
watch(slug, () => {
  loadProducts(true)
})

// Load products on mount
onMounted(() => {
  loadProducts(true)
})
</script>
