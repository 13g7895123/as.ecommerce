import type { CartItem, Product } from '../types';

interface CartState {
  items: CartItem[];
  isOpen: boolean;
}

export const useCart = () => {
  const state = useState<CartState>('cart', () => ({
    items: [],
    isOpen: false,
  }));

  // 從 localStorage 載入購物車
  if (process.client) {
    const savedCart = localStorage.getItem('cart');
    if (savedCart) {
      try {
        state.value.items = JSON.parse(savedCart);
      } catch (e) {
        console.error('Failed to parse cart from localStorage');
      }
    }
  }

  // 計算總數量
  const totalItems = computed(() => {
    return state.value.items.reduce((sum, item) => sum + item.quantity, 0);
  });

  // 計算總金額
  const totalPrice = computed(() => {
    return state.value.items.reduce((sum, item) => {
      const price = item.discount 
        ? item.price * (1 - item.discount / 100)
        : item.price;
      return sum + price * item.quantity;
    }, 0);
  });

  // 添加商品
  const addItem = (product: Product, quantity: number = 1) => {
    const existingItem = state.value.items.find(item => item.id === product.id);
    
    if (existingItem) {
      existingItem.quantity += quantity;
    } else {
      state.value.items.push({
        ...product,
        quantity,
      });
    }
    
    saveToLocalStorage();
  };

  // 移除商品
  const removeItem = (productId: string | number) => {
    state.value.items = state.value.items.filter(item => item.id !== productId);
    saveToLocalStorage();
  };

  // 更新數量
  const updateQuantity = (productId: string | number, quantity: number) => {
    const item = state.value.items.find(item => item.id === productId);
    if (item) {
      if (quantity <= 0) {
        removeItem(productId);
      } else {
        item.quantity = quantity;
        saveToLocalStorage();
      }
    }
  };

  // 清空購物車
  const clearCart = () => {
    state.value.items = [];
    saveToLocalStorage();
  };

  // 儲存到 localStorage
  const saveToLocalStorage = () => {
    if (process.client) {
      localStorage.setItem('cart', JSON.stringify(state.value.items));
    }
  };

  // 切換購物車顯示
  const toggleCart = () => {
    state.value.isOpen = !state.value.isOpen;
  };

  return {
    items: computed(() => state.value.items),
    isOpen: computed(() => state.value.isOpen),
    totalItems,
    totalPrice,
    addItem,
    removeItem,
    updateQuantity,
    clearCart,
    toggleCart,
  };
};
