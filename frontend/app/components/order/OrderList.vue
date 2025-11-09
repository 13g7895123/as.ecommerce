<template>
  <div class="space-y-4">
    <!-- Empty State -->
    <div v-if="orders.length === 0 && !loading" class="card text-center py-12">
      <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-gray-100">
        <span class="text-4xl">📦</span>
      </div>
      <h3 class="mb-2 text-xl font-semibold text-gray-900">還沒有訂單</h3>
      <p class="mb-6 text-gray-600">快去選購喜歡的商品吧！</p>
      <NuxtLink to="/" class="btn btn-primary">
        開始購物
      </NuxtLink>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="card py-12 text-center">
      <div class="mb-4 text-4xl">⏳</div>
      <p class="text-gray-600">載入中...</p>
    </div>

    <!-- Error State -->
    <div v-if="error && !loading" class="card bg-red-50 p-4">
      <p class="text-sm text-red-800">{{ error }}</p>
    </div>

    <!-- Order List -->
    <div v-if="orders.length > 0 && !loading" class="space-y-4">
      <OrderItem
        v-for="order in orders"
        :key="order.id"
        :order="order"
        @click="$emit('order-click', order.id)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Order } from '~/types/order'

interface Props {
  orders: Order[]
  loading?: boolean
  error?: string | null
}

withDefaults(defineProps<Props>(), {
  loading: false,
  error: null
})

defineEmits<{
  'order-click': [orderId: string]
}>()
</script>
