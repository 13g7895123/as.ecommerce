<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="container-custom">
      <!-- Breadcrumb -->
      <nav class="mb-6 flex items-center gap-2 text-sm text-gray-600">
        <NuxtLink to="/" class="hover:text-primary-600">首頁</NuxtLink>
        <span>/</span>
        <span class="font-medium text-gray-900">購物車</span>
      </nav>

      <!-- Page Title -->
      <h1 class="mb-8 text-4xl font-bold text-gray-900">購物車</h1>

      <!-- Empty Cart -->
      <CartEmpty v-if="isEmpty" @start-shopping="goToHome" />

      <!-- Cart Content -->
      <div v-else class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Cart Items -->
        <div class="space-y-4 lg:col-span-2">
          <CartItem
            v-for="item in items"
            :key="item.productId"
            :item="item"
            @increase="handleIncrease(item.productId)"
            @decrease="handleDecrease(item.productId)"
            @remove="handleRemove(item.productId)"
          />
        </div>

        <!-- Cart Summary -->
        <div class="lg:col-span-1">
          <div class="sticky top-4">
            <CartSummary
              :subtotal="cart.subtotal"
              :shipping="cart.shipping"
              :total="cart.total"
              :item-count="cart.itemCount"
              @checkout="handleCheckout"
              @continue-shopping="goToHome"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// SEO
useHead({
  title: '購物車 - 購物網站',
  meta: [
    {
      name: 'description',
      content: '查看您的購物車商品，準備結帳'
    }
  ]
})

const router = useRouter()
const { cart, items, isEmpty, updateQuantity, removeFromCart, goToCheckout } = useCart()

// 增加數量
const handleIncrease = (productId: string) => {
  const item = items.value.find((i) => i.productId === productId)
  if (!item) return

  try {
    updateQuantity({
      productId,
      quantity: item.quantity + 1
    })
  } catch (error: any) {
    alert(error.message || '更新數量失敗')
  }
}

// 減少數量
const handleDecrease = (productId: string) => {
  const item = items.value.find((i) => i.productId === productId)
  if (!item) return

  try {
    updateQuantity({
      productId,
      quantity: item.quantity - 1
    })
  } catch (error: any) {
    alert(error.message || '更新數量失敗')
  }
}

// 移除商品
const handleRemove = (productId: string) => {
  if (confirm('確定要移除此商品嗎？')) {
    try {
      removeFromCart(productId)
    } catch (error: any) {
      alert(error.message || '移除商品失敗')
    }
  }
}

// 前往結帳
const handleCheckout = () => {
  goToCheckout()
}

// 回首頁
const goToHome = () => {
  router.push('/')
}
</script>
