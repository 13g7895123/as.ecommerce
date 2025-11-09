<template>
  <div
    class="card cursor-pointer transition-shadow hover:shadow-lg"
    @click="$emit('click')"
  >
    <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
      <!-- Order Info -->
      <div class="flex-1">
        <div class="mb-2 flex items-center gap-3">
          <h3 class="font-mono text-lg font-semibold text-gray-900">
            {{ order.id }}
          </h3>
          <span
            class="rounded-full px-3 py-1 text-xs font-semibold"
            :class="statusClass"
          >
            {{ statusText }}
          </span>
        </div>
        
        <div class="mb-2 text-sm text-gray-600">
          <p>{{ formatDate(order.createdAt) }}</p>
          <p class="mt-1">{{ order.items.length }} 件商品</p>
        </div>

        <!-- Payment Method -->
        <div class="text-sm text-gray-600">
          <span>{{ paymentMethodText }}</span>
        </div>
      </div>

      <!-- Amount -->
      <div class="text-right">
        <p class="text-sm text-gray-600">訂單金額</p>
        <p class="text-2xl font-bold text-primary-600">
          {{ formatCurrency(order.total) }}
        </p>
      </div>
    </div>

    <!-- Order Items Preview -->
    <div v-if="order.items.length > 0" class="mt-4 flex gap-2 border-t border-gray-200 pt-4">
      <div
        v-for="(item, index) in order.items.slice(0, 3)"
        :key="index"
        class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100"
      >
        <img
          v-if="item.productImage"
          :src="item.productImage"
          :alt="item.productName"
          class="h-full w-full object-cover"
        />
      </div>
      <div
        v-if="order.items.length > 3"
        class="flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-lg bg-gray-100 text-sm font-semibold text-gray-600"
      >
        +{{ order.items.length - 3 }}
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Order } from '~/types/order'
import { formatCurrency } from '~/utils/currency'

interface Props {
  order: Order
}

const props = defineProps<Props>()

defineEmits<{
  click: []
}>()

// 訂單狀態文字
const statusText = computed(() => {
  const statusMap: Record<string, string> = {
    pending: '處理中',
    processing: '處理中',
    shipped: '已出貨',
    delivered: '已送達',
    cancelled: '已取消'
  }
  return statusMap[props.order.status] || props.order.status
})

// 訂單狀態樣式
const statusClass = computed(() => {
  const classMap: Record<string, string> = {
    pending: 'bg-yellow-100 text-yellow-800',
    processing: 'bg-blue-100 text-blue-800',
    shipped: 'bg-purple-100 text-purple-800',
    delivered: 'bg-green-100 text-green-800',
    cancelled: 'bg-red-100 text-red-800'
  }
  return classMap[props.order.status] || 'bg-gray-100 text-gray-800'
})

// 付款方式文字
const paymentMethodText = computed(() => {
  const methods: Record<string, string> = {
    'credit-card': '信用卡',
    'atm': 'ATM 轉帳',
    'cod': '貨到付款'
  }
  return methods[props.order.paymentMethod] || props.order.paymentMethod
})

// 格式化日期
const formatDate = (date: string): string => {
  return new Date(date).toLocaleDateString('zh-TW', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
