/**
 * 本地儲存工具函式
 */

const CART_STORAGE_KEY = 'ecommerce_guest_cart'
const AUTH_TOKEN_KEY = 'ecommerce_auth_token'
const USER_DATA_KEY = 'ecommerce_user_data'

/**
 * 儲存訪客購物車資料
 */
export function saveGuestCart(cart: any): void {
  if (typeof window === 'undefined') return
  try {
    localStorage.setItem(CART_STORAGE_KEY, JSON.stringify(cart))
  } catch (error) {
    console.error('Failed to save guest cart:', error)
  }
}

/**
 * 讀取訪客購物車資料
 */
export function loadGuestCart(): any | null {
  if (typeof window === 'undefined') return null
  try {
    const data = localStorage.getItem(CART_STORAGE_KEY)
    return data ? JSON.parse(data) : null
  } catch (error) {
    console.error('Failed to load guest cart:', error)
    return null
  }
}

/**
 * 清除訪客購物車資料
 */
export function clearGuestCart(): void {
  if (typeof window === 'undefined') return
  try {
    localStorage.removeItem(CART_STORAGE_KEY)
  } catch (error) {
    console.error('Failed to clear guest cart:', error)
  }
}

/**
 * 儲存認證 Token
 */
export function saveAuthToken(token: string): void {
  if (typeof window === 'undefined') return
  try {
    localStorage.setItem(AUTH_TOKEN_KEY, token)
  } catch (error) {
    console.error('Failed to save auth token:', error)
  }
}

/**
 * 讀取認證 Token
 */
export function loadAuthToken(): string | null {
  if (typeof window === 'undefined') return null
  try {
    return localStorage.getItem(AUTH_TOKEN_KEY)
  } catch (error) {
    console.error('Failed to load auth token:', error)
    return null
  }
}

/**
 * 清除認證 Token
 */
export function clearAuthToken(): void {
  if (typeof window === 'undefined') return
  try {
    localStorage.removeItem(AUTH_TOKEN_KEY)
  } catch (error) {
    console.error('Failed to clear auth token:', error)
  }
}

/**
 * 儲存使用者資料
 */
export function saveUserData(user: any): void {
  if (typeof window === 'undefined') return
  try {
    localStorage.setItem(USER_DATA_KEY, JSON.stringify(user))
  } catch (error) {
    console.error('Failed to save user data:', error)
  }
}

/**
 * 讀取使用者資料
 */
export function loadUserData(): any | null {
  if (typeof window === 'undefined') return null
  try {
    const data = localStorage.getItem(USER_DATA_KEY)
    return data ? JSON.parse(data) : null
  } catch (error) {
    console.error('Failed to load user data:', error)
    return null
  }
}

/**
 * 清除使用者資料
 */
export function clearUserData(): void {
  if (typeof window === 'undefined') return
  try {
    localStorage.removeItem(USER_DATA_KEY)
  } catch (error) {
    console.error('Failed to clear user data:', error)
  }
}

/**
 * 清除所有儲存的資料
 */
export function clearAllStorage(): void {
  clearGuestCart()
  clearAuthToken()
  clearUserData()
}
