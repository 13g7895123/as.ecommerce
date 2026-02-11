export default defineNuxtConfig({
  extends: ['../../layers/base'],
  
  devtools: { enabled: true },
  
  css: [
    '~/assets/css/general.css',
  ],

  app: {
    head: {
      title: 'G.Collection - 優質生活雜貨',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
        { name: 'description', content: 'G.Collection 提供廚房用品、文具、收納用品等優質生活雜貨' },
      ],
    },
  },

  runtimeConfig: {
    public: {
      siteType: 'general',
      brandName: 'G.Collection',
      brandSlogan: '優質生活，從細節開始',
      primaryColor: '#556B2F',
      accentColor: '#D4A574',
    },
  },

  compatibilityDate: '2024-12-19',
});
