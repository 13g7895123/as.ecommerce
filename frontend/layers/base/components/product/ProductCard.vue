<template>
  <div class="product-card" @click="handleClick">
    <div class="card-image">
      <img 
        :src="product.image" 
        :alt="product.name"
        loading="lazy"
      />
      <span v-if="product.isNew" class="badge new">NEW</span>
      <span v-if="product.discount" class="badge sale">SALE</span>
    </div>
    <div class="card-content">
      <h3 class="product-name">{{ product.name }}</h3>
      <p class="product-category">{{ product.category }}</p>
      <div class="price-row">
        <span v-if="product.discount" class="old-price">
          NT$ {{ product.price.toLocaleString() }}
        </span>
        <span class="price">
          NT$ {{ finalPrice.toLocaleString() }}
        </span>
      </div>
      <div class="card-actions">
        <button class="btn-primary" @click.stop="addToCart">
          <i class="fa-solid fa-cart-plus"></i> 加入購物車
        </button>
      </div>
    </div>
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
  product: Product;
}

interface Emits {
  (e: 'click', product: Product): void;
  (e: 'add-to-cart', product: Product): void;
}

const props = defineProps<Props>();
const emit = defineEmits<Emits>();

const finalPrice = computed(() => {
  if (props.product.discount) {
    return props.product.price * (1 - props.product.discount / 100);
  }
  return props.product.price;
});

const handleClick = () => {
  emit('click', props.product);
  navigateTo(`/product/${props.product.id}`);
};

const addToCart = () => {
  emit('add-to-cart', props.product);
  // useCartStore().addItem(props.product);
};
</script>

<style scoped>
.product-card {
  background: white;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  cursor: pointer;
  height: 100%;
  display: flex;
  flex-direction: column;
}

.product-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.15);
}

.card-image {
  position: relative;
  width: 100%;
  padding-top: 125%; /* 4:5 aspect ratio */
  overflow: hidden;
  background: #f5f5f5;
}

.card-image img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.4s;
}

.product-card:hover .card-image img {
  transform: scale(1.08);
}

.badge {
  position: absolute;
  top: 15px;
  right: 15px;
  padding: 6px 14px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
  letter-spacing: 1px;
  z-index: 1;
}

.badge.new {
  background: var(--primary-color, #333);
  color: white;
}

.badge.sale {
  background: #e74c3c;
  color: white;
}

.card-content {
  padding: 20px;
  flex: 1;
  display: flex;
  flex-direction: column;
}

.product-name {
  font-size: 1.1rem;
  font-weight: 600;
  margin-bottom: 8px;
  color: #333;
  line-height: 1.4;
}

.product-category {
  font-size: 0.85rem;
  color: #888;
  margin-bottom: 12px;
}

.price-row {
  margin-bottom: 16px;
  display: flex;
  align-items: center;
  gap: 12px;
}

.old-price {
  color: #999;
  text-decoration: line-through;
  font-size: 0.9rem;
}

.price {
  font-size: 1.3rem;
  font-weight: 700;
  color: var(--primary-color, #333);
}

.card-actions {
  margin-top: auto;
}

.btn-primary {
  width: 100%;
  padding: 12px;
  background: var(--primary-color, #333);
  color: white;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.3s;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
}

.btn-primary:hover {
  background: var(--accent-color, #555);
  transform: translateY(-2px);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}
</style>
