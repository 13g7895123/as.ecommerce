export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()

  // 如果已登入，重導向至首頁
  if (authStore.isAuthenticated) {
    return navigateTo('/')
  }
})
