<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container-custom">
      <!-- Breadcrumb -->
      <nav class="mb-6 flex items-center gap-2 text-sm text-gray-600">
        <NuxtLink to="/" class="hover:text-primary-600">首頁</NuxtLink>
        <span>/</span>
        <span class="font-medium text-gray-900">我的訂單</span>
      </nav>

      <!-- Page Title -->
      <h1 class="mb-8 text-4xl font-bold text-gray-900">我的訂單</h1>

      <!-- Order List -->
      <OrderList
        :orders="orders"
        :loading="loading"
        :error="error"
        @order-click="handleOrderClick"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
// SEO
useHead({
  title: '我的訂單 - 購物網站',
  meta: [
    {
      name: 'description',
      content: '查看您的所有訂單記錄'
    }
  ]
})

// 使用 middleware 保護頁面
definePageMeta({
  middleware: 'auth'
})

const router = useRouter()
const orderStore = useOrderStore()

const orders = computed(() => orderStore.orders)
const loading = computed(() => orderStore.loading)
const error = computed(() => orderStore.error)

// 載入訂單列表
onMounted(async () => {
  try {
    await orderStore.fetchOrders()
  } catch (err) {
    console.error('Failed to load orders:', err)
  }
})

// 點擊訂單項目
const handleOrderClick = (orderId: string) => {
  router.push(`/orders/${orderId}`)
}
</script>
