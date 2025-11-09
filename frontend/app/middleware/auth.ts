export default defineNuxtRouteMiddleware((to, from) => {
  const authStore = useAuthStore()

  // 如果未登入，重導向至登入頁面
  if (!authStore.isAuthenticated) {
    return navigateTo({
      path: '/login',
      query: { redirect: to.fullPath }
    })
  }
})
