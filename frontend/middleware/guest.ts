/**
 * Guest Middleware
 * 已登入使用者不能訪問登入/註冊頁面
 */

export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()

  // 初始化認證狀態
  if (!authStore.user && !authStore.token) {
    authStore.init()
  }

  // 如果已登入，重定向到首頁
  if (authStore.isAuthenticated) {
    return navigateTo('/')
  }
})
