<template>
  <div class="card space-y-6">
    <h2 class="text-xl font-bold text-gray-900">付款方式</h2>

    <div class="space-y-3">
      <!-- Credit Card -->
      <label
        class="flex cursor-pointer items-start gap-3 rounded-lg border-2 p-4 transition-colors"
        :class="selectedMethod === 'credit-card' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300'"
      >
        <input
          v-model="selectedMethod"
          type="radio"
          value="credit-card"
          class="mt-1"
        />
        <div class="flex-1">
          <div class="font-semibold text-gray-900">💳 信用卡</div>
          <p class="mt-1 text-sm text-gray-600">
            支援 Visa、Mastercard、JCB
          </p>
        </div>
      </label>

      <!-- ATM Transfer -->
      <label
        class="flex cursor-pointer items-start gap-3 rounded-lg border-2 p-4 transition-colors"
        :class="selectedMethod === 'atm' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300'"
      >
        <input
          v-model="selectedMethod"
          type="radio"
          value="atm"
          class="mt-1"
        />
        <div class="flex-1">
          <div class="font-semibold text-gray-900">🏦 ATM 轉帳</div>
          <p class="mt-1 text-sm text-gray-600">
            取得虛擬帳號後至 ATM 轉帳
          </p>
        </div>
      </label>

      <!-- Cash on Delivery -->
      <label
        class="flex cursor-pointer items-start gap-3 rounded-lg border-2 p-4 transition-colors"
        :class="selectedMethod === 'cod' ? 'border-primary-600 bg-primary-50' : 'border-gray-200 hover:border-gray-300'"
      >
        <input
          v-model="selectedMethod"
          type="radio"
          value="cod"
          class="mt-1"
        />
        <div class="flex-1">
          <div class="font-semibold text-gray-900">📦 貨到付款</div>
          <p class="mt-1 text-sm text-gray-600">
            收到商品時以現金付款
          </p>
        </div>
      </label>
    </div>

    <p v-if="error" class="text-sm text-red-600">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
const selectedMethod = ref<string>('credit-card')
const error = ref<string>('')

const validate = (): boolean => {
  error.value = ''
  
  if (!selectedMethod.value) {
    error.value = '請選擇付款方式'
    return false
  }
  
  return true
}

const getData = (): string => {
  return selectedMethod.value
}

defineExpose({
  validate,
  getData
})
</script>
