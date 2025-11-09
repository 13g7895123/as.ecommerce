<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container-custom">
      <!-- Breadcrumb -->
      <nav class="mb-6 flex items-center gap-2 text-sm text-gray-600">
        <NuxtLink to="/" class="hover:text-primary-600">首頁</NuxtLink>
        <span>/</span>
        <NuxtLink to="/cart" class="hover:text-primary-600">購物車</NuxtLink>
        <span>/</span>
        <span class="font-medium text-gray-900">結帳</span>
      </nav>

      <!-- Page Title -->
      <h1 class="mb-8 text-4xl font-bold text-gray-900">結帳</h1>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Forms -->
        <div class="space-y-6 lg:col-span-2">
          <!-- Step 1: Shipping Info -->
          <ShippingForm ref="shippingFormRef" />

          <!-- Step 2: Payment Method -->
          <PaymentMethod ref="paymentMethodRef" />
        </div>

        <!-- Order Review -->
        <div class="lg:col-span-1">
          <div class="sticky top-4">
            <OrderReview
              :items="items"
              :shipping-info="previewShippingInfo"
              :payment-method="previewPaymentMethod"
              :subtotal="cart.subtotal"
              :shipping="cart.shipping"
              :total="cart.total"
            />

            <!-- Submit Button -->
            <button
              class="btn btn-primary mt-6 w-full"
              :disabled="loading"
              @click="handleSubmit"
            >
              <span v-if="loading">處理中...</span>
              <span v-else>確認訂單</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// SEO
useHead({
  title: '結帳 - 購物網站',
  meta: [
    {
      name: 'description',
      content: '完成您的訂單結帳'
    }
  ]
})

// 使用 middleware 保護頁面
definePageMeta({
  middleware: 'auth'
})

const router = useRouter()
const { cart, items, isEmpty } = useCart()
const { createOrder, loading, validateCheckout } = useCheckout()

// 如果購物車為空，重定向到購物車頁面
if (isEmpty.value) {
  router.push('/cart')
}

// 驗證結帳條件
const validation = validateCheckout()
if (!validation.valid) {
  alert(validation.message)
  router.push(validation.message === '請先登入' ? '/login' : '/cart')
}

// Refs
const shippingFormRef = ref()
const paymentMethodRef = ref()

// Preview data
const previewShippingInfo = ref(null)
const previewPaymentMethod = ref(null)

// 處理提交
const handleSubmit = async () => {
  // 驗證收件資訊
  if (!shippingFormRef.value?.validate()) {
    alert('請填寫完整的收件資訊')
    return
  }

  // 驗證付款方式
  if (!paymentMethodRef.value?.validate()) {
    alert('請選擇付款方式')
    return
  }

  try {
    // 取得表單資料
    const shippingInfo = shippingFormRef.value.getData()
    const paymentMethod = paymentMethodRef.value.getData()

    // 更新預覽
    previewShippingInfo.value = shippingInfo
    previewPaymentMethod.value = paymentMethod

    // 建立訂單
    const order = await createOrder(shippingInfo, paymentMethod)

    // 前往訂單完成頁面
    router.push(`/orders/${order.id}/success`)
  } catch (error: any) {
    alert(error.message || '建立訂單失敗，請稍後再試')
  }
}
</script>
