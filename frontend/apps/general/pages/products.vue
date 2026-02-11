<template>
  <div class="products-page">
    <div class="container">
      <div class="page-header">
        <h1>全部商品</h1>
        <p>探索我們精選的優質生活用品</p>
      </div>

      <!-- Filter Bar -->
      <div class="filter-bar">
        <select v-model="selectedCategory">
          <option value="">所有分類</option>
          <option value="廚房用品">廚房用品</option>
          <option value="文具用品">文具用品</option>
          <option value="收納用品">收納用品</option>
        </select>

        <select v-model="sortBy">
          <option value="default">排序方式</option>
          <option value="price-low">價格：低到高</option>
          <option value="price-high">價格：高到低</option>
          <option value="new">最新商品</option>
        </select>
      </div>

      <!-- Products Grid -->
      <ProductGrid
        :products="filteredProducts"
        :columns="4"
        @add-to-cart="handleAddToCart"
      />
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Product } from '~/types';

const selectedCategory = ref('');
const sortBy = ref('default');

const allProducts: Product[] = [
  {
    id: 1,
    name: '不鏽鋼煎鍋',
    category: '廚房用品',
    price: 1280,
    image: 'https://images.unsplash.com/photo-1584990347449-39b4aa1a6a56?w=500',
  },
  {
    id: 2,
    name: '高級鋼筆組',
    category: '文具用品',
    price: 890,
    image: 'https://images.unsplash.com/photo-1583485088034-697b5bc54ccd?w=500',
    discount: 15,
  },
  {
    id: 3,
    name: '透明收納盒',
    category: '收納用品',
    price: 450,
    image: 'https://images.unsplash.com/photo-1600096194534-95cf5ece04cf?w=500',
    isNew: true,
  },
  {
    id: 4,
    name: '木質砧板',
    category: '廚房用品',
    price: 680,
    image: 'https://images.unsplash.com/photo-1594212699903-ec8a3eca50f5?w=500',
  },
  {
    id: 5,
    name: '皮革筆記本',
    category: '文具用品',
    price: 750,
    image: 'https://images.unsplash.com/photo-1517971129774-8a2b38fa128e?w=500',
    isNew: true,
  },
  {
    id: 6,
    name: '竹製置物架',
    category: '收納用品',
    price: 1200,
    image: 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=500',
    discount: 20,
  },
  {
    id: 7,
    name: '陶瓷餐具組',
    category: '廚房用品',
    price: 1580,
    image: 'https://images.unsplash.com/photo-1578500494198-246f612d3b3d?w=500',
  },
  {
    id: 8,
    name: '辦公桌面收納',
    category: '文具用品',
    price: 550,
    image: 'https://images.unsplash.com/photo-1586281010691-7a0347230b93?w=500',
  },
];

const filteredProducts = computed(() => {
  let products = [...allProducts];

  // Filter by category
  if (selectedCategory.value) {
    products = products.filter(p => p.category === selectedCategory.value);
  }

  // Sort
  if (sortBy.value === 'price-low') {
    products.sort((a, b) => a.price - b.price);
  } else if (sortBy.value === 'price-high') {
    products.sort((a, b) => b.price - a.price);
  } else if (sortBy.value === 'new') {
    products.sort((a, b) => (b.isNew ? 1 : 0) - (a.isNew ? 1 : 0));
  }

  return products;
});

const cart = useCart();

const handleAddToCart = (product: Product) => {
  cart.addItem(product);
};
</script>

<style scoped>
.page-header {
  text-align: center;
  padding: 60px 0 40px;
}

.page-header h1 {
  font-size: 3rem;
  color: var(--primary-color);
  margin-bottom: 15px;
  font-weight: 700;
}

.page-header p {
  font-size: 1.2rem;
  color: #666;
}
</style>
