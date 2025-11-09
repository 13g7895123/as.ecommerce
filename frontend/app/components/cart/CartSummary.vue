<template>
  <div class="card space-y-4">
    <h2 class="text-xl font-bold text-gray-900">訂單摘要</h2>

    <!-- Summary Items -->
    <div class="space-y-3 border-t border-gray-200 pt-4">
      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-600">小計 ({{ itemCount }} 件商品)</span>
        <span class="font-medium text-gray-900">{{ formatCurrency(subtotal) }}</span>
      </div>

      <div class="flex items-center justify-between text-sm">
        <span class="text-gray-600">運費</span>
        <span class="font-medium" :class="shipping === 0 ? 'text-green-600' : 'text-gray-900'">
          {{ shipping === 0 ? '免運費' : formatCurrency(shipping) }}
        </span>
      </div>

      <!-- Free Shipping Progress -->
      <div v-if="freeShippingRemaining > 0" class="rounded-lg bg-blue-50 p-3">
        <p class="mb-2 text-xs text-blue-800">
          再買 {{ formatCurrency(freeShippingRemaining) }} 即可享免運費
        </p>
        <div class="h-2 w-full overflow-hidden rounded-full bg-blue-200">
          <div
            class="h-full bg-blue-600 transition-all duration-300"
            :style="{ width: `${freeShippingProgress}%` }"
          ></div>
        </div>
      </div>

      <div
        class="flex items-center justify-between border-t border-gray-200 pt-3 text-lg font-bold"
      >
        <span class="text-gray-900">總計</span>
        <span class="text-primary-600">{{ formatCurrency(total) }}</span>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="space-y-2 border-t border-gray-200 pt-4">
      <button
        class="btn btn-primary w-full"
        :disabled="disabled"
        @click="$emit('checkout')"
      >
        前往結帳
      </button>
      <button class="btn btn-secondary w-full" @click="$emit('continue-shopping')">
        繼續購物
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { formatCurrency, calculateFreeShippingRemaining } from '~/utils/currency'

interface Props {
  subtotal: number
  shipping: number
  total: number
  itemCount: number
  disabled?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false
})

defineEmits<{
  checkout: []
  'continue-shopping': []
}>()

// 計算免運費剩餘金額
const freeShippingRemaining = computed(() => {
  return calculateFreeShippingRemaining(props.subtotal)
})

// 計算免運費進度（百分比）
const freeShippingProgress = computed(() => {
  const threshold = 1000
  const progress = (props.subtotal / threshold) * 100
  return Math.min(progress, 100)
})
</script>
