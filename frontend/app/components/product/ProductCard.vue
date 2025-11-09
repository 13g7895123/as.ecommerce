<template>
  <article
    data-testid="product-card"
    class="card group cursor-pointer transition-all duration-300 hover:shadow-lg"
    @click="$emit('click', product)"
  >
    <!-- 產品圖片 -->
    <div class="relative mb-4 overflow-hidden rounded-lg bg-gray-100">
      <div class="aspect-square">
        <img
          v-if="product.thumbnail"
          :src="product.thumbnail"
          :alt="product.name"
          class="h-full w-full object-cover transition-transform duration-300 group-hover:scale-105"
          loading="lazy"
        />
        <div
          v-else
          class="flex h-full w-full items-center justify-center bg-gray-200 text-gray-400"
        >
          <span class="text-4xl">📦</span>
        </div>
      </div>

      <!-- 缺貨標籤 -->
      <div
        v-if="product.stock === 0"
        class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50"
      >
        <span class="rounded-lg bg-white px-4 py-2 font-semibold text-gray-900">
          已售完
        </span>
      </div>

      <!-- 特價標籤 -->
      <div
        v-else-if="product.originalPrice && product.originalPrice > product.price"
        class="absolute right-2 top-2 rounded-lg bg-red-500 px-3 py-1 text-sm font-bold text-white shadow-lg"
      >
        {{ discountText }}
      </div>

      <!-- 熱門標籤 -->
      <div
        v-else-if="product.featured"
        class="absolute left-2 top-2 rounded-lg bg-primary-500 px-3 py-1 text-sm font-bold text-white shadow-lg"
      >
        熱門
      </div>
    </div>

    <!-- 產品資訊 -->
    <div class="space-y-2">
      <!-- 產品名稱 -->
      <h3 class="line-clamp-2 text-lg font-semibold text-gray-900 group-hover:text-primary-600">
        {{ product.name }}
      </h3>

      <!-- 簡短描述 -->
      <p class="line-clamp-2 text-sm text-gray-600">
        {{ product.shortDescription }}
      </p>

      <!-- 價格區塊 -->
      <div class="flex items-center gap-2">
        <span class="text-2xl font-bold text-primary-600">
          {{ formatCurrency(product.price) }}
        </span>
        <span
          v-if="product.originalPrice && product.originalPrice > product.price"
          class="text-sm text-gray-400 line-through"
        >
          {{ formatCurrency(product.originalPrice) }}
        </span>
      </div>

      <!-- 庫存狀態 -->
      <div class="flex items-center gap-2 text-sm">
        <span
          :class="[
            'inline-flex items-center gap-1 rounded-full px-2 py-1',
            stockStatus.class
          ]"
        >
          <span class="h-2 w-2 rounded-full" :class="stockStatus.dotClass"></span>
          {{ stockStatus.text }}
        </span>
      </div>
    </div>

    <!-- 操作按鈕 -->
    <div class="mt-4 flex gap-2">
      <button
        data-testid="add-to-cart-button"
        class="btn btn-primary flex-1"
        :disabled="product.stock === 0"
        @click.stop="$emit('add-to-cart', product)"
      >
        加入購物車
      </button>
      <button
        class="btn btn-secondary"
        @click.stop="$emit('view-detail', product)"
      >
        查看詳情
      </button>
    </div>
  </article>
</template>

<script setup lang="ts">
import type { Product } from '~/types/product'
import { formatCurrency, formatDiscount } from '~/utils/currency'

interface Props {
  product: Product
}

const props = defineProps<Props>()

defineEmits<{
  click: [product: Product]
  'add-to-cart': [product: Product]
  'view-detail': [product: Product]
}>()

// 計算折扣文字
const discountText = computed(() => {
  if (props.product.originalPrice && props.product.originalPrice > props.product.price) {
    return formatDiscount(props.product.originalPrice, props.product.price)
  }
  return ''
})

// 庫存狀態
const stockStatus = computed(() => {
  const stock = props.product.stock

  if (stock === 0) {
    return {
      text: '已售完',
      class: 'bg-gray-100 text-gray-600',
      dotClass: 'bg-gray-400'
    }
  } else if (stock < 10) {
    return {
      text: `剩餘 ${stock} 件`,
      class: 'bg-orange-100 text-orange-600',
      dotClass: 'bg-orange-400'
    }
  } else {
    return {
      text: '庫存充足',
      class: 'bg-green-100 text-green-600',
      dotClass: 'bg-green-400'
    }
  }
})
</script>
