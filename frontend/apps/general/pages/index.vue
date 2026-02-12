<template>
  <div class="home-page">
    <!-- Hero Carousel -->
    <section class="carousel-section">
      <GeneralCarousel
        :slides="carouselSlides"
        :auto-play="true"
        :interval="5000"
      />
    </section>

    <div class="container">
      <!-- Featured Categories -->
      <section class="category-section">
        <div class="category-header">
          <h2>精選分類</h2>
          <p>為您的生活帶來便利與美好</p>
        </div>
        
        <div class="category-grid">
          <NuxtLink 
            v-for="category in categories" 
            :key="category.slug"
            :to="`/${category.slug}`"
            class="category-card"
          >
            <img :src="category.image" :alt="category.name" />
            <div class="category-info">
              <h3>{{ category.name }}</h3>
              <p>{{ category.description }}</p>
            </div>
          </NuxtLink>
        </div>
      </section>

      <!-- Featured Products -->
      <section class="category-section">
        <div class="category-header">
          <h2>熱門商品</h2>
          <p>精心挑選的優質生活用品</p>
        </div>
        
        <ProductGrid
          :products="featuredProducts"
          :columns="4"
          @product-click="handleProductClick"
          @add-to-cart="handleAddToCart"
        />
      </section>

      <!-- New Arrivals -->
      <section class="category-section">
        <div class="category-header">
          <h2>新品上市</h2>
          <p>最新到貨的生活好物</p>
        </div>
        
        <ProductGrid
          :products="newProducts"
          :columns="4"
          @add-to-cart="handleAddToCart"
        />
      </section>
    </div>
  </div>
</template>

<script setup lang="ts">
import type { Product, CarouselSlide } from '~/types';

const carouselSlides: CarouselSlide[] = [
  {
    image: 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=1600',
    title: '廚房美學',
    description: '精選廚房用品，讓烹飪更有樂趣',
    buttonText: '立即選購',
  },
  {
    image: 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=1600',
    title: '文具生活',
    description: '優質文具，提升工作效率',
    buttonText: '探索更多',
  },
  {
    image: 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?w=1600',
    title: '收納整理',
    description: '聰明收納，空間更整潔',
    buttonText: '查看商品',
  },
];

const categories = [
  {
    slug: 'kitchen',
    name: '廚房用品',
    description: '鍋具、餐具、烘焙用品',
    image: 'https://images.unsplash.com/photo-1556911220-bff31c812dba?w=600',
  },
  {
    slug: 'stationery',
    name: '文具用品',
    description: '筆記本、筆類、辦公用品',
    image: 'https://images.unsplash.com/photo-1484480974693-6ca0a78fb36b?w=600',
  },
  {
    slug: 'storage',
    name: '收納用品',
    description: '收納盒、置物架、整理箱',
    image: 'https://images.unsplash.com/photo-1595428774223-ef52624120d2?w=600',
  },
];

const featuredProducts: Product[] = [
  {
    id: 1,
    name: '不鏽鋼煎鍋',
    category: '廚房用品',
    price: 1280,
    image: 'https://images.unsplash.com/photo-1584990347449-39b4aa1a6a56?w=500',
    isNew: false,
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

const newProducts: Product[] = featuredProducts.filter(p => p.isNew).slice(0, 4);

const cart = useCart();

const handleProductClick = (product: Product) => {
  navigateTo(`/product/${product.id}`);
};

const handleAddToCart = (product: Product) => {
  cart.addItem(product);
  // 可以顯示通知
  console.log('已加入購物車:', product.name);
};
</script>

<style scoped>
.carousel-section {
  margin-bottom: 60px;
}

.category-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 30px;
  margin-bottom: 60px;
}

.category-card {
  position: relative;
  height: 300px;
  border-radius: 16px;
  overflow: hidden;
  cursor: pointer;
  transition: transform 0.4s;
  text-decoration: none;
}

.category-card:hover {
  transform: translateY(-10px);
}

.category-card img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s;
}

.category-card:hover img {
  transform: scale(1.1);
}

.category-info {
  position: absolute;
  bottom: 0;
  left: 0;
  right: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
  color: white;
  padding: 40px 25px 25px;
}

.category-info h3 {
  font-size: 1.8rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.category-info p {
  font-size: 1rem;
  opacity: 0.9;
}

@media (max-width: 992px) {
  .category-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 576px) {
  .category-grid {
    grid-template-columns: 1fr;
  }
}
</style>
