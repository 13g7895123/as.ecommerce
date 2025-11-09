<template>
  <div class="space-y-6">
    <!-- Order Header -->
    <div class="card">
      <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div>
          <h2 class="mb-2 font-mono text-2xl font-bold text-gray-900">
            {{ order.id }}
          </h2>
          <p class="text-sm text-gray-600">
            下單時間：{{ formatDate(order.createdAt) }}
          </p>
        </div>
        <span
          class="inline-block rounded-full px-4 py-2 text-sm font-semibold"
          :class="statusClass"
        >
          {{ statusText }}
        </span>
      </div>

      <!-- Order Status Timeline -->
      <div class="mt-6 border-t border-gray-200 pt-6">
        <h3 class="mb-4 font-semibold text-gray-900">訂單狀態</h3>
        <div class="space-y-3">
          <div class="flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-green-100">
              <span class="text-green-600">✓</span>
            </div>
            <div>
              <p class="font-medium text-gray-900">訂單已成立</p>
              <p class="text-xs text-gray-600">{{ formatDate(order.createdAt) }}</p>
            </div>
          </div>
          
          <div v-if="order.status !== 'cancelled'" class="flex items-center gap-3">
            <div
              class="flex h-8 w-8 items-center justify-center rounded-full"
              :class="['processing', 'shipped', 'delivered'].includes(order.status) ? 'bg-green-100' : 'bg-gray-100'"
            >
              <span :class="['processing', 'shipped', 'delivered'].includes(order.status) ? 'text-green-600' : 'text-gray-400'">
                {{ ['processing', 'shipped', 'delivered'].includes(order.status) ? '✓' : '○' }}
              </span>
            </div>
            <div>
              <p class="font-medium" :class="['processing', 'shipped', 'delivered'].includes(order.status) ? 'text-gray-900' : 'text-gray-400'">
                處理中
              </p>
            </div>
          </div>

          <div v-if="order.status !== 'cancelled'" class="flex items-center gap-3">
            <div
              class="flex h-8 w-8 items-center justify-center rounded-full"
              :class="['shipped', 'delivered'].includes(order.status) ? 'bg-green-100' : 'bg-gray-100'"
            >
              <span :class="['shipped', 'delivered'].includes(order.status) ? 'text-green-600' : 'text-gray-400'">
                {{ ['shipped', 'delivered'].includes(order.status) ? '✓' : '○' }}
              </span>
            </div>
            <div>
              <p class="font-medium" :class="['shipped', 'delivered'].includes(order.status) ? 'text-gray-900' : 'text-gray-400'">
                已出貨
              </p>
            </div>
          </div>

          <div v-if="order.status !== 'cancelled'" class="flex items-center gap-3">
            <div
              class="flex h-8 w-8 items-center justify-center rounded-full"
              :class="order.status === 'delivered' ? 'bg-green-100' : 'bg-gray-100'"
            >
              <span :class="order.status === 'delivered' ? 'text-green-600' : 'text-gray-400'">
                {{ order.status === 'delivered' ? '✓' : '○' }}
              </span>
            </div>
            <div>
              <p class="font-medium" :class="order.status === 'delivered' ? 'text-gray-900' : 'text-gray-400'">
                已送達
              </p>
            </div>
          </div>

          <div v-if="order.status === 'cancelled'" class="flex items-center gap-3">
            <div class="flex h-8 w-8 items-center justify-center rounded-full bg-red-100">
              <span class="text-red-600">✕</span>
            </div>
            <div>
              <p class="font-medium text-red-600">訂單已取消</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Order Items -->
    <div class="card">
      <h3 class="mb-4 text-xl font-semibold text-gray-900">訂單商品</h3>
      <div class="space-y-4">
        <div
          v-for="item in order.items"
          :key="item.productId"
          class="flex items-center gap-4"
        >
          <div class="h-20 w-20 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
            <img
              v-if="item.productImage"
              :src="item.productImage"
              :alt="item.productName"
              class="h-full w-full object-cover"
            />
          </div>
          <div class="flex-1">
            <p class="font-medium text-gray-900">{{ item.productName }}</p>
            <p class="text-sm text-gray-600">
              {{ formatCurrency(item.price) }} × {{ item.quantity }}
            </p>
          </div>
          <p class="font-semibold text-gray-900">
            {{ formatCurrency(item.price * item.quantity) }}
          </p>
        </div>
      </div>
    </div>

    <!-- Shipping Info -->
    <div class="card">
      <h3 class="mb-4 text-xl font-semibold text-gray-900">收件資訊</h3>
      <div class="text-sm text-gray-600">
        <p class="mb-1">
          <span class="font-medium text-gray-900">收件人：</span>
          {{ order.shippingInfo.name }}
        </p>
        <p class="mb-1">
          <span class="font-medium text-gray-900">電話：</span>
          {{ order.shippingInfo.phone }}
        </p>
        <p>
          <span class="font-medium text-gray-900">地址：</span>
          {{ order.shippingInfo.postalCode }}
          {{ order.shippingInfo.city }}{{ order.shippingInfo.district }}
          {{ order.shippingInfo.address }}
        </p>
      </div>
    </div>

    <!-- Payment & Summary -->
    <div class="card">
      <h3 class="mb-4 text-xl font-semibold text-gray-900">付款資訊</h3>
      <div class="space-y-3">
        <div class="flex items-center justify-between text-sm">
          <span class="text-gray-600">付款方式</span>
          <span class="font-medium text-gray-900">{{ paymentMethodText }}</span>
        </div>
        <div class="flex items-center justify-between text-sm">
          <span class="text-gray-600">商品小計</span>
          <span class="font-medium text-gray-900">{{ formatCurrency(order.subtotal) }}</span>
        </div>
        <div class="flex items-center justify-between text-sm">
          <span class="text-gray-600">運費</span>
          <span class="font-medium" :class="order.shipping === 0 ? 'text-green-600' : 'text-gray-900'">
            {{ order.shipping === 0 ? '免運費' : formatCurrency(order.shipping) }}
          </span>
        </div>
        <div class="flex items-center justify-between border-t border-gray-200 pt-3 text-lg font-bold">
          <span class="text-gray-900">訂單總額</span>
          <span class="text-primary-600">{{ formatCurrency(order.total) }}</span>
        </div>
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
