<template>
  <div class="card space-y-6">
    <h2 class="text-xl font-bold text-gray-900">訂單確認</h2>

    <!-- Items -->
    <div class="space-y-3 border-t border-gray-200 pt-4">
      <h3 class="font-semibold text-gray-900">訂購商品</h3>
      <div
        v-for="item in items"
        :key="item.productId"
        class="flex items-center gap-4"
      >
        <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
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

    <!-- Shipping Info -->
    <div v-if="shippingInfo" class="space-y-2 border-t border-gray-200 pt-4">
      <h3 class="font-semibold text-gray-900">收件資訊</h3>
      <div class="text-sm text-gray-600">
        <p>{{ shippingInfo.name }} / {{ shippingInfo.phone }}</p>
        <p>
          {{ shippingInfo.postalCode }}
          {{ shippingInfo.city }}{{ shippingInfo.district }}
          {{ shippingInfo.address }}
        </p>
      </div>
    </div>

    <!-- Payment Method -->
    <div v-if="paymentMethod" class="space-y-2 border-t border-gray-200 pt-4">
      <h3 class="font-semibold text-gray-900">付款方式</h3>
      <p class="text-sm text-gray-600">{{ paymentMethodText }}</p>
    </div>

    <!-- Summary -->
    <div class="space-y-3 border-t border-gray-200 pt-4">
      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-600">小計</span>
        <span class="font-medium text-gray-900">{{ formatCurrency(subtotal) }}</span>
      </div>
      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-600">運費</span>
        <span class="font-medium" :class="shipping === 0 ? 'text-green-600' : 'text-gray-900'">
          {{ shipping === 0 ? '免運費' : formatCurrency(shipping) }}
        </span>
      </div>
      <div class="flex items-center justify-between border-t border-gray-200 pt-3 text-lg font-bold">
        <span class="text-gray-900">總計</span>
        <span class="text-primary-600">{{ formatCurrency(total) }}</span>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { CartItem } from '~/types/cart'
import type { ShippingInfo } from '~/types/address'
import { formatCurrency } from '~/utils/currency'

interface Props {
  items: CartItem[]
  shippingInfo?: ShippingInfo | null
  paymentMethod?: string | null
  subtotal: number
  shipping: number
  total: number
}

const props = withDefaults(defineProps<Props>(), {
  shippingInfo: null,
  paymentMethod: null
})

const paymentMethodText = computed(() => {
  const methods: Record<string, string> = {
    'credit-card': '信用卡',
    'atm': 'ATM 轉帳',
    'cod': '貨到付款'
  }
  return props.paymentMethod ? methods[props.paymentMethod] || props.paymentMethod : ''
})
</script>
