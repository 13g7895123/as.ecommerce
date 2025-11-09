/**
 * Auth Store - Pinia
 * 管理使用者認證狀態
 */

import { defineStore } from 'pinia'
import type { User, LoginPayload, RegisterPayload, AuthResponse } from '~/types/user'
import { saveAuthToken, loadAuthToken, saveUserData, loadUserData, clearAuthToken, clearUserData } from '~/utils/storage'

interface AuthState {
  user: User | null
  token: string | null
  loading: boolean
  error: string | null
}

export const useAuthStore = defineStore('auth', {
  state: (): AuthState => ({
    user: null,
    token: null,
    loading: false,
    error: null
  }),

  getters: {
    /**
     * 檢查是否已登入
     */
    isAuthenticated: (state): boolean => {
      return !!state.token && !!state.user
    },

    /**
     * 取得使用者資訊
     */
    currentUser: (state): User | null => {
      return state.user
    }
  },

  actions: {
    /**
     * 初始化認證狀態（從 localStorage 載入）
     */
    init(): void {
      const token = loadAuthToken()
      const user = loadUserData()

      if (token && user) {
        this.token = token
        this.user = user
      }
    },

    /**
     * 會員登入
     */
    async login(payload: LoginPayload): Promise<void> {
      this.loading = true
      this.error = null

      try {
        const response = await $fetch<AuthResponse>('/api/auth/login', {
          method: 'POST',
          body: payload
        })

        this.token = response.token
        this.user = response.user

        // 儲存到 localStorage
        saveAuthToken(response.token)
        saveUserData(response.user)
      } catch (err: any) {
        this.error = err.data?.message || err.message || '登入失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 會員註冊
     */
    async register(payload: RegisterPayload): Promise<void> {
      this.loading = true
      this.error = null

      try {
        const response = await $fetch<AuthResponse>('/api/auth/register', {
          method: 'POST',
          body: payload
        })

        this.token = response.token
        this.user = response.user

        // 儲存到 localStorage
        saveAuthToken(response.token)
        saveUserData(response.user)
      } catch (err: any) {
        this.error = err.data?.message || err.message || '註冊失敗'
        throw err
      } finally {
        this.loading = false
      }
    },

    /**
     * 登出
     */
    async logout(): Promise<void> {
      this.user = null
      this.token = null

      // 清除 localStorage
      clearAuthToken()
      clearUserData()
    },

    /**
     * 更新使用者資訊
     */
    updateUser(user: User): void {
      this.user = user
      saveUserData(user)
    },

    /**
     * 重設狀態
     */
    reset(): void {
      this.user = null
      this.token = null
      this.loading = false
      this.error = null
    }
  }
})
