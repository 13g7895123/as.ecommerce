<template>
  <div class="product-grid">
    <ProductCard
      v-for="product in products"
      :key="product.id"
      :product="product"
      @click="handleProductClick"
      @add-to-cart="handleAddToCart"
    />
  </div>
</template>

<script setup lang="ts">
interface Product {
  id: string | number;
  name: string;
  category: string;
  price: number;
  image: string;
  isNew?: boolean;
  discount?: number;
}

interface Props {
  products: Product[];
  columns?: number;
}

interface Emits {
  (e: 'product-click', product: Product): void;
  (e: 'add-to-cart', product: Product): void;
}

const props = withDefaults(defineProps<Props>(), {
  columns: 4,
});

const emit = defineEmits<Emits>();

const handleProductClick = (product: Product) => {
  emit('product-click', product);
};

const handleAddToCart = (product: Product) => {
  emit('add-to-cart', product);
};
</script>

<style scoped>
.product-grid {
  display: grid;
  grid-template-columns: repeat(v-bind(columns), 1fr);
  gap: 30px;
  margin: 40px 0;
}

@media (max-width: 1200px) {
  .product-grid {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 768px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
  }
}

@media (max-width: 480px) {
  .product-grid {
    grid-template-columns: 1fr;
  }
}
</style>
