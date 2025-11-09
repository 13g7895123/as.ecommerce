<template>
  <div class="min-h-screen bg-gray-50 py-12">
    <div class="container-custom">
      <div class="mx-auto max-w-2xl">
        <!-- Success Card -->
        <div class="card text-center">
          <!-- Success Icon -->
          <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100">
            <span class="text-5xl">✓</span>
          </div>

          <!-- Title -->
          <h1 class="mb-3 text-3xl font-bold text-gray-900">訂單完成！</h1>
          <p class="mb-8 text-lg text-gray-600">
            感謝您的購買，我們已收到您的訂單
          </p>

          <!-- Order Info -->
          <div class="mb-8 rounded-lg bg-gray-50 p-6">
            <div class="mb-4 flex items-center justify-between">
              <span class="text-sm text-gray-600">訂單編號</span>
              <span class="font-mono font-semibold text-gray-900">{{ orderId }}</span>
            </div>
            <div class="flex items-center justify-between">
              <span class="text-sm text-gray-600">訂單金額</span>
              <span class="text-2xl font-bold text-primary-600">
                {{ formatCurrency(order?.total || 0) }}
              </span>
            </div>
          </div>

          <!-- Next Steps -->
          <div class="mb-8 text-left">
            <h2 class="mb-4 font-semibold text-gray-900">接下來的步驟</h2>
            <ol class="space-y-3 text-sm text-gray-600">
              <li class="flex items-start gap-2">
                <span class="font-semibold text-primary-600">1.</span>
                <span>我們已發送訂單確認信至您的 Email</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="font-semibold text-primary-600">2.</span>
                <span>商品將於 3-5 個工作天內送達</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="font-semibold text-primary-600">3.</span>
                <span>您可以隨時在「我的訂單」中查看訂單狀態</span>
              </li>
            </ol>
          </div>

          <!-- Actions -->
          <div class="flex gap-4">
            <button class="btn btn-primary flex-1" @click="goToOrders">
              查看訂單
            </button>
            <button class="btn btn-secondary flex-1" @click="goToHome">
              繼續購物
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { formatCurrency } from '~/utils/currency'

// SEO
useHead({
  title: '訂單完成 - 購物網站',
  meta: [
    {
      name: 'description',
      content: '您的訂單已成功建立'
    }
  ]
})

const route = useRoute()
const router = useRouter()
const orderStore = useOrderStore()

const orderId = computed(() => route.params.id as string)
const order = computed(() => orderStore.currentOrder)

// 載入訂單資訊
onMounted(async () => {
  if (orderId.value) {
    try {
      await orderStore.fetchOrderById(orderId.value)
    } catch (error) {
      console.error('Failed to load order:', error)
    }
  }
})

const goToOrders = () => {
  router.push('/orders')
}

const goToHome = () => {
  router.push('/')
}
</script>
