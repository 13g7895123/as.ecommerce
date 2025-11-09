<template>
  <div data-testid="cart-item" class="card flex items-center gap-4">
    <!-- Product Image -->
    <div class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-lg bg-gray-100">
      <img
        v-if="item.productImage"
        :src="item.productImage"
        :alt="item.productName"
        class="h-full w-full object-cover"
      />
      <div v-else class="flex h-full w-full items-center justify-center text-gray-400">
        <span class="text-2xl">📦</span>
      </div>
    </div>

    <!-- Product Info -->
    <div class="flex-1">
      <h3 class="mb-1 font-semibold text-gray-900">{{ item.productName }}</h3>
      <p class="mb-2 text-lg font-bold text-primary-600">
        {{ formatCurrency(item.price) }}
      </p>

      <!-- Quantity Controls -->
      <div class="flex items-center gap-2">
        <button
          class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 bg-white transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
          :disabled="item.quantity <= 1"
          @click="$emit('decrease')"
        >
          −
        </button>
        <span class="w-12 text-center text-sm font-medium">{{ item.quantity }}</span>
        <button
          class="flex h-8 w-8 items-center justify-center rounded-lg border border-gray-300 bg-white transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
          :disabled="item.quantity >= item.stock"
          @click="$emit('increase')"
        >
          +
        </button>
        <span v-if="item.quantity >= item.stock" class="ml-2 text-xs text-orange-600">
          已達庫存上限
        </span>
      </div>
    </div>

    <!-- Subtotal & Remove -->
    <div class="flex flex-col items-end gap-2">
      <p class="text-lg font-bold text-gray-900">
        {{ formatCurrency(item.price * item.quantity) }}
      </p>
      <button
        class="text-sm text-red-600 transition-colors hover:text-red-700"
        @click="$emit('remove')"
      >
        移除
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { CartItem } from '~/types/cart'
import { formatCurrency } from '~/utils/currency'

interface Props {
  item: CartItem
}

defineProps<Props>()

defineEmits<{
  increase: []
  decrease: []
  remove: []
}>()
</script>
