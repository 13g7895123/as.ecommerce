<template>
  <div>
    <h2 class="text-2xl font-bold text-center mb-6">會員註冊</h2>
    <UserRegisterForm
      :loading="loading"
      :error-message="error || ''"
      @submit="handleRegister"
      @switch-to-login="goToLogin"
    />
  </div>
</template>

<script setup lang="ts">
import type { RegisterPayload } from '~/types/user'

// Page metadata
definePageMeta({
  layout: 'auth',
  middleware: 'guest'
})

// SEO
useHead({
  title: '會員註冊 - 購物網站',
  meta: [
    {
      name: 'description',
      content: '建立新帳號以享受完整購物體驗'
    }
  ]
})

const router = useRouter()
const { register, loading, error } = useAuth()

const handleRegister = async (payload: RegisterPayload) => {
  try {
    const success = await register(payload)
    if (success) {
      // Wait for auth state to be fully updated
      await nextTick()
      // Force full page reload to ensure middleware re-evaluates
      window.location.href = '/'
    }
  } catch (err: any) {
    console.error('Register failed:', err)
  }
}

const goToLogin = () => {
  router.push('/login')
}
</script>
