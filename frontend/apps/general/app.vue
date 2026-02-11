<template>
  <div>
    <AppHeader 
      :brand-name="config.public.brandName"
      :nav-links="navLinks"
    />
    
    <main>
      <NuxtPage />
    </main>
    
    <AppFooter
      :brand-name="config.public.brandName"
      :slogan="config.public.brandSlogan"
      contact-email="hello@gcollection.com"
      contact-phone="02-2345-6789"
    />
  </div>
</template>

<script setup lang="ts">
const config = useRuntimeConfig();

const navLinks = [
  { to: '/', label: '首頁' },
  { to: '/products', label: '全部商品' },
  { to: '/kitchen', label: '廚房用品' },
  { to: '/stationery', label: '文具用品' },
  { to: '/storage', label: '收納用品' },
];
</script>
