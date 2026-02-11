export interface Product {
  id: string | number;
  name: string;
  category: string;
  price: number;
  image: string;
  images?: string[];
  description?: string;
  isNew?: boolean;
  discount?: number;
  stock?: number;
  sku?: string;
}

export interface CartItem extends Product {
  quantity: number;
}

export interface User {
  id: string;
  name: string;
  email: string;
  phone?: string;
  avatar?: string;
}

export interface NavLink {
  to: string;
  label: string;
}

export interface CarouselSlide {
  image: string;
  title?: string;
  description?: string;
  buttonText?: string;
  link?: string;
}
