<template>
  <header class="app-header">
    <div class="container">
      <nav class="navbar">
        <NuxtLink to="/" class="logo">
          {{ brandName }}
        </NuxtLink>
        
        <ul class="nav-links">
          <li v-for="link in navLinks" :key="link.to">
            <NuxtLink :to="link.to">{{ link.label }}</NuxtLink>
          </li>
        </ul>
        
        <div class="nav-icons">
          <NuxtLink to="/search">
            <i class="fa-solid fa-magnifying-glass"></i>
          </NuxtLink>
          <NuxtLink to="/login">
            <i class="fa-solid fa-user"></i>
          </NuxtLink>
          <NuxtLink to="/checkout">
            <i class="fa-solid fa-cart-shopping"></i>
            <span v-if="cartCount > 0" class="cart-badge">{{ cartCount }}</span>
          </NuxtLink>
        </div>
      </nav>
    </div>
  </header>
</template>

<script setup lang="ts">
interface NavLink {
  to: string;
  label: string;
}

interface Props {
  brandName?: string;
  navLinks?: NavLink[];
}

const props = withDefaults(defineProps<Props>(), {
  brandName: 'COLLECTION',
  navLinks: () => [],
});

const cartCount = ref(0);

// 可以從 store 取得購物車數量
// const cartStore = useCartStore();
// const cartCount = computed(() => cartStore.totalItems);
</script>

<style scoped>
.app-header {
  background: white;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
  position: sticky;
  top: 0;
  z-index: 1000;
  backdrop-filter: blur(10px);
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 0;
}

.logo {
  font-size: 1.8rem;
  font-weight: 800;
  color: var(--primary-color, #333);
  text-decoration: none;
  letter-spacing: 3px;
  font-family: var(--font-serif);
}

.nav-links {
  display: flex;
  gap: 35px;
  list-style: none;
}

.nav-links a {
  color: #333;
  text-decoration: none;
  font-weight: 500;
  transition: all 0.3s;
  position: relative;
}

.nav-links a::after {
  content: '';
  position: absolute;
  width: 0;
  height: 2px;
  bottom: -5px;
  left: 50%;
  background: var(--accent-color, #c49b63);
  transition: all 0.3s;
  transform: translateX(-50%);
}

.nav-links a:hover::after,
.nav-links a.router-link-active::after {
  width: 100%;
}

.nav-links a:hover,
.nav-links a.router-link-active {
  color: var(--primary-color, #333);
}

.nav-icons {
  display: flex;
  gap: 25px;
  align-items: center;
}

.nav-icons a {
  color: #333;
  font-size: 1.2rem;
  text-decoration: none;
  position: relative;
  transition: all 0.3s;
}

.nav-icons a:hover {
  color: var(--primary-color, #333);
  transform: translateY(-2px);
}

.cart-badge {
  position: absolute;
  top: -8px;
  right: -10px;
  background: var(--primary-color, #333);
  color: white;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.7rem;
  font-weight: bold;
}

@media (max-width: 768px) {
  .navbar {
    flex-direction: column;
    gap: 20px;
  }

  .nav-links {
    gap: 20px;
    flex-wrap: wrap;
    justify-content: center;
  }
}
</style>
