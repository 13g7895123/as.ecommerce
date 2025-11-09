<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container-custom">
      <!-- Breadcrumb -->
      <nav class="mb-6 flex items-center gap-2 text-sm text-gray-600">
        <NuxtLink to="/" class="hover:text-primary-600">首頁</NuxtLink>
        <span>/</span>
        <NuxtLink to="/orders" class="hover:text-primary-600">我的訂單</NuxtLink>
        <span>/</span>
        <span class="font-medium text-gray-900">訂單詳情</span>
      </nav>

      <!-- Loading State -->
      <div v-if="loading" class="card py-12 text-center">
        <div class="mb-4 text-4xl">⏳</div>
        <p class="text-gray-600">載入中...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="card bg-red-50 p-4">
        <p class="text-sm text-red-800">{{ error }}</p>
        <button class="btn btn-secondary mt-4" @click="retry">
          重試
        </button>
      </div>

      <!-- Order Detail -->
      <OrderDetail v-else-if="order" :order="order" />

      <!-- Not Found -->
      <div v-else class="card py-12 text-center">
        <div class="mb-4 text-4xl">❌</div>
        <h2 class="mb-2 text-xl font-semibold text-gray-900">找不到訂單</h2>
        <p class="mb-6 text-gray-600">此訂單不存在或已被刪除</p>
        <NuxtLink to="/orders" class="btn btn-primary">
          返回訂單列表
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// SEO
useHead({
  title: '訂單詳情 - 購物網站',
  meta: [
    {
      name: 'description',
      content: '查看訂單的詳細資訊'
    }
  ]
})

// 使用 middleware 保護頁面
definePageMeta({
  middleware: 'auth'
})

const route = useRoute()
const orderStore = useOrderStore()

const orderId = computed(() => route.params.id as string)
const order = computed(() => orderStore.currentOrder)
const loading = computed(() => orderStore.loading)
const error = computed(() => orderStore.error)

// 載入訂單詳情
const loadOrder = async () => {
  if (orderId.value) {
    try {
      await orderStore.fetchOrderById(orderId.value)
    } catch (err) {
      console.error('Failed to load order:', err)
    }
  }
}

// 重試
const retry = () => {
  loadOrder()
}

onMounted(() => {
  loadOrder()
})
</script>
