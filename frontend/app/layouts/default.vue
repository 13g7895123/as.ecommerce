<template>
  <div class="flex min-h-screen flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm sticky top-0 z-40">
      <div class="container-custom">
        <div class="flex items-center justify-between h-16">
          <!-- Logo -->
          <NuxtLink to="/" class="text-2xl font-bold text-primary-600">
            購物網站
          </NuxtLink>

          <!-- Desktop Navigation -->
          <nav class="hidden md:flex items-center gap-6">
            <NuxtLink
              to="/"
              class="text-gray-700 hover:text-primary-600 transition-colors font-medium"
              :class="{ 'text-primary-600': route.path === '/' }"
            >
              首頁
            </NuxtLink>
            <NuxtLink
              v-for="category in categories"
              :key="category.id"
              :to="`/products/category/${category.slug}`"
              class="text-gray-700 hover:text-primary-600 transition-colors font-medium"
              :class="{ 'text-primary-600': currentCategory?.id === category.id }"
            >
              {{ category.name }}
            </NuxtLink>
          </nav>

          <!-- Right Actions -->
          <div class="flex items-center gap-4">
            <!-- Cart -->
            <NuxtLink to="/cart" class="relative text-gray-700 hover:text-primary-600 transition-colors">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
              <span
                v-if="cartStore.itemCount > 0"
                data-testid="cart-badge"
                class="absolute -top-2 -right-2 bg-primary-600 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center"
              >
                {{ cartStore.itemCount }}
              </span>
            </NuxtLink>

            <!-- User Menu (Desktop) -->
            <div v-if="isAuthenticated" class="hidden md:flex items-center gap-2">
              <NuxtLink to="/orders" class="text-gray-700 hover:text-primary-600 transition-colors text-sm">
                訂單查詢
              </NuxtLink>
              <span class="text-gray-400">|</span>
              <button
                class="text-gray-700 hover:text-primary-600 transition-colors text-sm"
                @click="handleLogout"
              >
                登出
              </button>
            </div>
            <div v-else class="hidden md:flex items-center gap-2">
              <NuxtLink to="/login" class="text-gray-700 hover:text-primary-600 transition-colors text-sm">
                登入
              </NuxtLink>
              <span class="text-gray-400">|</span>
              <NuxtLink to="/register" class="text-primary-600 font-medium hover:text-primary-700 transition-colors text-sm">
                註冊
              </NuxtLink>
            </div>

            <!-- Mobile Menu Button -->
            <button
              class="md:hidden p-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
              @click="mobileMenuOpen = !mobileMenuOpen"
            >
              <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>

        <!-- Mobile Navigation -->
        <div v-if="mobileMenuOpen" class="md:hidden border-t border-gray-200 py-4">
          <div class="space-y-2">
            <NuxtLink
              to="/"
              class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors font-medium"
              :class="{ 'bg-primary-50 text-primary-600': route.path === '/' }"
              @click="mobileMenuOpen = false"
            >
              首頁
            </NuxtLink>
            <NuxtLink
              v-for="category in categories"
              :key="category.id"
              :to="`/products/category/${category.slug}`"
              class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors font-medium"
              :class="{ 'bg-primary-50 text-primary-600': currentCategory?.id === category.id }"
              @click="mobileMenuOpen = false"
            >
              {{ category.name }}
            </NuxtLink>

            <!-- Mobile User Menu -->
            <div v-if="isAuthenticated" class="pt-2 mt-2 border-t border-gray-200 space-y-2">
              <NuxtLink
                to="/orders"
                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                @click="mobileMenuOpen = false"
              >
                訂單查詢
              </NuxtLink>
              <button
                class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-lg transition-colors"
                @click="handleLogout"
              >
                登出
              </button>
            </div>
            <div v-else class="pt-2 mt-2 border-t border-gray-200 space-y-2">
              <NuxtLink
                to="/login"
                class="block px-4 py-2 text-gray-700 hover:bg-gray-100 rounded-lg transition-colors"
                @click="mobileMenuOpen = false"
              >
                登入
              </NuxtLink>
              <NuxtLink
                to="/register"
                class="block px-4 py-2 text-primary-600 font-medium hover:bg-gray-100 rounded-lg transition-colors"
                @click="mobileMenuOpen = false"
              >
                註冊
              </NuxtLink>
            </div>
          </div>
        </div>
      </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 bg-white py-8">
      <div class="container-custom">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-3">
          <div>
            <h3 class="mb-4 font-semibold text-gray-900">關於我們</h3>
            <p class="text-sm text-gray-600">
              提供優質的購物體驗，精選商品，快速配送。
            </p>
          </div>
          <div>
            <h3 class="mb-4 font-semibold text-gray-900">客戶服務</h3>
            <ul class="space-y-2 text-sm text-gray-600">
              <li><a href="#" class="hover:text-primary-600">常見問題</a></li>
              <li><a href="#" class="hover:text-primary-600">配送資訊</a></li>
              <li><a href="#" class="hover:text-primary-600">退換貨政策</a></li>
              <li><a href="#" class="hover:text-primary-600">聯絡我們</a></li>
            </ul>
          </div>
          <div>
            <h3 class="mb-4 font-semibold text-gray-900">購物資訊</h3>
            <ul class="space-y-2 text-sm text-gray-600">
              <li><a href="#" class="hover:text-primary-600">付款方式</a></li>
              <li><a href="#" class="hover:text-primary-600">會員權益</a></li>
              <li><a href="#" class="hover:text-primary-600">隱私權政策</a></li>
              <li><a href="#" class="hover:text-primary-600">使用條款</a></li>
            </ul>
          </div>
        </div>
        <div class="mt-8 border-t border-gray-200 pt-8 text-center text-sm text-gray-500">
          <p>&copy; 2025 購物網站. All rights reserved.</p>
        </div>
      </div>
    </footer>

    <!-- Toast Notifications -->
    <div class="fixed top-4 right-4 z-50 space-y-2">
      <BaseToast
        v-for="toast in toasts"
        :key="toast.id"
        :show="true"
        :type="toast.type"
        :message="toast.message"
        :duration="0"
        @close="useToast().remove(toast.id)"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia'
import type { Category } from '~/types/category'
import { useAuthStore } from '@/stores/auth'
import { useCartStore } from '@/stores/cart'
import { useCategories } from '@/composables/useCategories'
import { useToast } from '@/composables/useToast'
import BaseToast from '@/components/base/BaseToast.vue'

const { categories, fetchCategories } = useCategories()
const authStore = useAuthStore()
const cartStore = useCartStore()
const { isAuthenticated } = storeToRefs(authStore)
const route = useRoute()
const { toasts } = useToast()
const mobileMenuOpen = ref(false)

// 載入類別
onMounted(async () => {
  try {
    await fetchCategories()
  } catch (error) {
    console.error('Failed to load categories:', error)
  }
})

// 根據當前路由判斷當前類別
const currentCategory = computed<Category | null>(() => {
  const slug = route.params.slug as string
  if (!slug) return null
  return categories.value.find((c) => c.slug === slug) || null
})

// 登出處理
const handleLogout = async () => {
  await authStore.logout()
  mobileMenuOpen.value = false
  navigateTo('/')
}

// 關閉移動端菜單當路由改變時
watch(() => route.path, () => {
  mobileMenuOpen.value = false
})
</script>
