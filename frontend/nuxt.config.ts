// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  modules: ['@nuxtjs/tailwindcss', '@pinia/nuxt', '@vueuse/nuxt'],

  css: ['assets/main.css'],

  typescript: {
    strict: true,
    typeCheck: false
  },

  app: {
    head: {
      titleTemplate: '%s - 購物網站',
      title: '購物網站',
      htmlAttrs: {
        lang: 'zh-TW'
      },
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      meta: [
        { name: 'description', content: '完整的電商購物網站' },
        { name: 'format-detection', content: 'telephone=no' }
      ]
    }
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.API_BASE || '/api'
    }
  }
})

