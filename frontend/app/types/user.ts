/**
 * 使用者相關型別定義
 */

export interface User {
  id: string
  email: string
  name: string
  phone: string
  createdAt: string
  updatedAt: string
}

export interface RegisterPayload {
  email: string
  password: string
  name: string
  phone: string
}

export interface LoginPayload {
  email: string
  password: string
}

export interface AuthResponse {
  user: User
  token: string
}

export interface UpdateProfilePayload {
  name?: string
  phone?: string
  currentPassword?: string
  newPassword?: string
}
