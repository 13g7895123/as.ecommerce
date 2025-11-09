/**
 * Auth Store Tests
 */

import { describe, it, expect, beforeEach, vi } from 'vitest'
import { setActivePinia, createPinia } from 'pinia'
import { useAuthStore } from '~/stores/auth'

// Mock $fetch
global.$fetch = vi.fn()

describe('Auth Store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.clearAllMocks()
    localStorage.clear()
  })

  describe('login', () => {
    it('should login successfully with valid credentials', async () => {
      const mockResponse = {
        token: 'test-token',
        user: {
          id: 'user-1',
          email: 'test@example.com',
          name: 'Test User',
          phone: '0912345678'
        }
      }

      vi.mocked($fetch).mockResolvedValueOnce(mockResponse)

      const auth = useAuthStore()
      await auth.login({ email: 'test@example.com', password: 'password' })

      expect(auth.isAuthenticated).toBe(true)
      expect(auth.user).toEqual(mockResponse.user)
      expect(auth.token).toBe(mockResponse.token)
    })

    it('should handle login errors', async () => {
      vi.mocked($fetch).mockRejectedValueOnce(new Error('Invalid credentials'))

      const auth = useAuthStore()
      
      await expect(
        auth.login({ email: 'wrong@example.com', password: 'wrong' })
      ).rejects.toThrow()

      expect(auth.isAuthenticated).toBe(false)
      expect(auth.user).toBeNull()
    })
  })

  describe('register', () => {
    it('should register successfully', async () => {
      const mockResponse = {
        token: 'test-token',
        user: {
          id: 'user-1',
          email: 'new@example.com',
          name: 'New User',
          phone: '0912345678'
        }
      }

      vi.mocked($fetch).mockResolvedValueOnce(mockResponse)

      const auth = useAuthStore()
      await auth.register({
        email: 'new@example.com',
        password: 'password123',
        name: 'New User',
        phone: '0912345678'
      })

      expect(auth.isAuthenticated).toBe(true)
      expect(auth.user).toEqual(mockResponse.user)
    })
  })

  describe('logout', () => {
    it('should clear user data on logout', async () => {
      const auth = useAuthStore()
      
      // Set some data
      auth.user = {
        id: 'user-1',
        email: 'test@example.com',
        name: 'Test User',
        phone: '0912345678'
      }
      auth.token = 'test-token'

      auth.logout()

      expect(auth.user).toBeNull()
      expect(auth.token).toBeNull()
      expect(auth.isAuthenticated).toBe(false)
    })
  })

  describe('persistence', () => {
    it('should save to localStorage on login', async () => {
      const mockResponse = {
        token: 'test-token',
        user: {
          id: 'user-1',
          email: 'test@example.com',
          name: 'Test User',
          phone: '0912345678'
        }
      }

      vi.mocked($fetch).mockResolvedValueOnce(mockResponse)

      const auth = useAuthStore()
      await auth.login({ email: 'test@example.com', password: 'password' })

      const savedToken = localStorage.getItem('ecommerce_auth_token')
      const savedUser = localStorage.getItem('ecommerce_user_data')

      expect(savedToken).toBe('test-token')
      expect(JSON.parse(savedUser!)).toEqual(mockResponse.user)
    })

    it('should clear localStorage on logout', () => {
      localStorage.setItem('ecommerce_auth_token', 'test-token')
      localStorage.setItem('ecommerce_user_data', JSON.stringify({ id: 'user-1' }))

      const auth = useAuthStore()
      auth.logout()

      expect(localStorage.getItem('ecommerce_auth_token')).toBeNull()
      expect(localStorage.getItem('ecommerce_user_data')).toBeNull()
    })
  })
})
