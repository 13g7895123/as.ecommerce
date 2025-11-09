/**
 * useAuth Composable
 * 認證相關邏輯封裝
 */

import type { LoginPayload, RegisterPayload } from '~/types/user'

export function useAuth() {
  const store = useAuthStore()
  const router = useRouter()

  /**
   * 登入
   */
  const login = async (payload: LoginPayload) => {
    try {
      await store.login(payload)
      return true
    } catch (error: any) {
      console.error('Failed to login:', error)
      throw error
    }
  }

  /**
   * 註冊
   */
  const register = async (payload: RegisterPayload) => {
    try {
      await store.register(payload)
      return true
    } catch (error: any) {
      console.error('Failed to register:', error)
      throw error
    }
  }

  /**
   * 登出
   */
  const logout = async () => {
    try {
      await store.logout()
      router.push('/')
    } catch (error: any) {
      console.error('Failed to logout:', error)
    }
  }

  /**
   * 前往登入頁面
   */
  const goToLogin = () => {
    router.push('/login')
  }

  /**
   * 前往註冊頁面
   */
  const goToRegister = () => {
    router.push('/register')
  }

  /**
   * 前往會員中心
   */
  const goToProfile = () => {
    router.push('/profile')
  }

  return {
    // State
    user: computed(() => store.currentUser),
    isAuthenticated: computed(() => store.isAuthenticated),
    loading: computed(() => store.loading),
    error: computed(() => store.error),

    // Actions
    login,
    register,
    logout,
    goToLogin,
    goToRegister,
    goToProfile
  }
}
