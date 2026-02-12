export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  
  components: {
    dirs: [
      {
        path: '~/components',
        global: true,
        pathPrefix: false,
      },
    ],
  },

  imports: {
    dirs: ['composables', 'utils', 'types'],
  },

  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@nuxt/image',
    '@vueuse/nuxt',
  ],

  css: [
    '~/assets/css/main.css',
  ],

  runtimeConfig: {
    public: {
      apiBase: process.env.API_BASE_URL || 'http://localhost:8000/api',
    },
  },

  app: {
    head: {
      charset: 'utf-8',
      viewport: 'width=device-width, initial-scale=1',
      link: [
        {
          rel: 'stylesheet',
          href: 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        },
      ],
    },
  },

  typescript: {
    strict: true,
    typeCheck: true,
  },
});
