<template>
  <nav class="bg-white shadow-sm">
    <div class="container-custom">
      <div class="flex items-center justify-between py-4">
        <!-- Logo / Brand -->
        <button
          class="flex items-center gap-2 text-xl font-bold text-primary-600 transition-colors hover:text-primary-700"
          @click="goToHome"
        >
          <span class="text-2xl">🛒</span>
          <span>購物網站</span>
        </button>

        <!-- Desktop Navigation -->
        <div class="hidden items-center gap-6 md:flex">
          <button
            class="font-medium text-gray-700 transition-colors hover:text-primary-600"
            :class="{ 'text-primary-600': !currentCategory }"
            @click="goToHome"
          >
            首頁
          </button>

          <button
            v-for="category in categories"
            :key="category.id"
            class="font-medium text-gray-700 transition-colors hover:text-primary-600"
            :class="{ 'text-primary-600': currentCategory?.id === category.id }"
            @click="goToCategory(category.slug)"
          >
            {{ category.name }}
          </button>

          <!-- Cart Icon -->
          <button
            class="relative ml-4 rounded-lg p-2 text-gray-700 transition-colors hover:bg-gray-100"
            @click="goToCart"
          >
            <span class="text-2xl">🛒</span>
            <span
              v-if="cartItemCount > 0"
              class="absolute -right-1 -top-1 flex h-5 w-5 items-center justify-center rounded-full bg-primary-600 text-xs font-bold text-white"
            >
              {{ cartItemCount }}
            </span>
          </button>

          <!-- User Menu -->
          <div v-if="isAuthenticated" class="relative ml-2">
            <button
              class="flex items-center gap-2 rounded-lg px-3 py-2 text-gray-700 transition-colors hover:bg-gray-100"
              @click="userMenuOpen = !userMenuOpen"
            >
              <span class="text-xl">👤</span>
              <span class="text-sm font-medium">{{ userName }}</span>
            </button>

            <!-- Dropdown Menu -->
            <div
              v-if="userMenuOpen"
              class="absolute right-0 top-full z-10 mt-2 w-48 rounded-lg bg-white shadow-lg"
            >
              <button
                class="block w-full px-4 py-2 text-left text-sm text-gray-700 transition-colors hover:bg-gray-100"
                @click="handleProfileClick"
              >
                會員中心
              </button>
              <button
                class="block w-full px-4 py-2 text-left text-sm text-gray-700 transition-colors hover:bg-gray-100"
                @click="handleOrdersClick"
              >
                我的訂單
              </button>
              <hr class="my-1" />
              <button
                class="block w-full px-4 py-2 text-left text-sm text-red-600 transition-colors hover:bg-gray-100"
                @click="handleLogout"
              >
                登出
              </button>
            </div>
          </div>

          <!-- Login Button -->
          <button
            v-else
            class="btn btn-primary ml-2"
            @click="handleLoginClick"
          >
            登入
          </button>
        </div>

        <!-- Mobile Menu Button -->
        <button
          class="rounded-lg p-2 text-gray-700 transition-colors hover:bg-gray-100 md:hidden"
          @click="mobileMenuOpen = !mobileMenuOpen"
        >
          <span v-if="!mobileMenuOpen" class="text-2xl">☰</span>
          <span v-else class="text-2xl">✕</span>
        </button>
      </div>

      <!-- Mobile Navigation -->
      <div
        v-if="mobileMenuOpen"
        class="border-t border-gray-200 py-4 md:hidden"
      >
        <div class="space-y-2">
          <button
            class="block w-full rounded-lg px-4 py-2 text-left font-medium text-gray-700 transition-colors hover:bg-gray-100"
            :class="{ 'bg-primary-50 text-primary-600': !currentCategory }"
            @click="handleMobileNavClick('/')"
          >
            首頁
          </button>

          <button
            v-for="category in categories"
            :key="category.id"
            class="block w-full rounded-lg px-4 py-2 text-left font-medium text-gray-700 transition-colors hover:bg-gray-100"
            :class="{
              'bg-primary-50 text-primary-600': currentCategory?.id === category.id
            }"
            @click="handleMobileNavClick(`/products/category/${category.slug}`)"
          >
            {{ category.name }}
          </button>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import type { Category } from '~/types/category'

interface Props {
  categories: Category[]
  currentCategory?: Category | null
}

defineProps<Props>()

const router = useRouter()
const mobileMenuOpen = ref(false)
const userMenuOpen = ref(false)
const { itemCount: cartItemCount, goToCart } = useCart()
const { isAuthenticated, user, logout } = useAuth()

const userName = computed(() => user.value?.name || '會員')

const goToHome = () => {
  router.push('/')
  mobileMenuOpen.value = false
}

const goToCategory = (slug: string) => {
  router.push(`/products/category/${slug}`)
  mobileMenuOpen.value = false
}

const handleMobileNavClick = (path: string) => {
  router.push(path)
  mobileMenuOpen.value = false
}

const handleLoginClick = () => {
  router.push('/login')
}

const handleProfileClick = () => {
  router.push('/profile')
  userMenuOpen.value = false
}

const handleOrdersClick = () => {
  router.push('/orders')
  userMenuOpen.value = false
}

const handleLogout = async () => {
  if (confirm('確定要登出嗎？')) {
    await logout()
    userMenuOpen.value = false
  }
}

// Close menus when route changes
watch(() => router.currentRoute.value.path, () => {
  mobileMenuOpen.value = false
  userMenuOpen.value = false
})

// Close user menu when clicking outside
if (process.client) {
  onMounted(() => {
    document.addEventListener('click', (e) => {
      const target = e.target as HTMLElement
      if (!target.closest('.relative')) {
        userMenuOpen.value = false
      }
    })
  })
}
</script>
