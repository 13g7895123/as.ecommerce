export const useApi = () => {
  const config = useRuntimeConfig()
  const authStore = useAuthStore()

  const api = $fetch.create({
    baseURL: config.public.apiBase,
    onRequest({ options }) {
      if (authStore.token) {
        options.headers = options.headers || {}
        // @ts-ignore
        options.headers.Authorization = `Bearer ${authStore.token}`
      }
    },
    onResponseError({ response }) {
      if (response.status === 401) {
        authStore.logout()
      }
    }
  })

  return api
}
