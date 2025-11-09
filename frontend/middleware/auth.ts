/**
 * Auth Middleware
 * 保護需要登入的頁面
 */

export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()

  // 初始化認證狀態
  if (!authStore.user && !authStore.token) {
    authStore.init()
  }

  // 檢查是否已登入
  if (!authStore.isAuthenticated) {
    // 儲存原本要前往的頁面
    return navigateTo({
      path: '/login',
      query: { redirect: to.fullPath }
    })
  }
})
