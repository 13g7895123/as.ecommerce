<template>
  <div class="space-y-6">
    <!-- 載入狀態 -->
    <div v-if="loading" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
      <ProductCardSkeleton v-for="i in skeletonCount" :key="i" />
    </div>

    <!-- 錯誤狀態 -->
    <div v-else-if="error" class="rounded-lg bg-red-50 p-6 text-center">
      <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-red-100">
        <span class="text-3xl">⚠️</span>
      </div>
      <h3 class="mb-2 text-lg font-semibold text-red-900">載入失敗</h3>
      <p class="mb-4 text-sm text-red-700">{{ error }}</p>
      <button class="btn btn-primary" @click="$emit('retry')">
        重新載入
      </button>
    </div>

    <!-- 空狀態 -->
    <div v-else-if="products.length === 0" class="rounded-lg bg-gray-50 p-12 text-center">
      <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full bg-gray-100">
        <span class="text-4xl">📦</span>
      </div>
      <h3 class="mb-2 text-lg font-semibold text-gray-900">沒有找到產品</h3>
      <p class="text-sm text-gray-600">{{ emptyMessage }}</p>
    </div>

    <!-- 產品網格 -->
    <div
      v-else
      class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4"
    >
      <ProductCard
        v-for="product in products"
        :key="product.id"
        :product="product"
        @click="$emit('product-click', product)"
        @add-to-cart="$emit('add-to-cart', product)"
        @view-detail="$emit('view-detail', product)"
      />
    </div>

    <!-- 載入更多按鈕 -->
    <div v-if="hasMore && !loading" class="text-center">
      <button class="btn btn-secondary" @click="$emit('load-more')">
        載入更多產品
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Product } from '~/types/product'

interface Props {
  products: Product[]
  loading?: boolean
  error?: string | null
  hasMore?: boolean
  emptyMessage?: string
  skeletonCount?: number
}

withDefaults(defineProps<Props>(), {
  loading: false,
  error: null,
  hasMore: false,
  emptyMessage: '目前沒有可用的產品',
  skeletonCount: 8
})

defineEmits<{
  'product-click': [product: Product]
  'add-to-cart': [product: Product]
  'view-detail': [product: Product]
  'load-more': []
  retry: []
}>()
</script>
