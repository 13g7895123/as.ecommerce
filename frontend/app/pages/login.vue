<template>
  <div>
    <h2 class="text-2xl font-bold text-center mb-6">會員登入</h2>
    <UserLoginForm
      :loading="loading"
      :error-message="error || ''"
      @submit="handleLogin"
      @switch-to-register="goToRegister"
    />
  </div>
</template>

<script setup lang="ts">
import type { LoginPayload } from '~/types/user'

// Page metadata
definePageMeta({
  layout: 'auth',
  middleware: 'guest'
})

// SEO
useHead({
  title: '會員登入 - 購物網站',
  meta: [
    {
      name: 'description',
      content: '登入您的帳號以享受更多服務'
    }
  ]
})

const router = useRouter()
const { login, loading, error } = useAuth()

const handleLogin = async (payload: LoginPayload) => {
  try {
    const success = await login(payload)
    if (success) {
      // Wait for auth state to be fully updated
      await nextTick()
      // Force full page reload to ensure middleware re-evaluates
      window.location.href = '/'
    }
  } catch (err: any) {
    console.error('Login failed:', err)
  }
}

const goToRegister = () => {
  router.push('/register')
}
</script>
