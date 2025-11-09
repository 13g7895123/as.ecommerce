/**
 * Auth Plugin
 * 在客戶端初始化認證狀態
 */

export default defineNuxtPlugin(() => {
  const authStore = useAuthStore()
  
  // 從 localStorage 載入認證狀態
  authStore.init()
})
