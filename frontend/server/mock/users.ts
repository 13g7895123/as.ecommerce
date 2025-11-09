/**
 * 使用者模擬資料
 */

import type { User } from '~/types/user'
import bcrypt from 'bcryptjs'

export interface MockUser extends User {
  passwordHash: string
}

export const mockUsers: MockUser[] = [
  {
    id: 'user-001',
    email: 'test@example.com',
    name: '測試使用者',
    phone: '0912345678',
    passwordHash: bcrypt.hashSync('password123', 10),
    createdAt: '2025-10-01T00:00:00Z',
    updatedAt: '2025-10-20T00:00:00Z'
  },
  {
    id: 'user-002',
    email: 'demo@example.com',
    name: '示範帳號',
    phone: '0987654321',
    passwordHash: bcrypt.hashSync('demo123', 10),
    createdAt: '2025-09-15T00:00:00Z',
    updatedAt: '2025-10-18T00:00:00Z'
  }
]

export function getUserByEmail(email: string): MockUser | undefined {
  return mockUsers.find((u) => u.email === email)
}

export function getUserById(id: string): MockUser | undefined {
  return mockUsers.find((u) => u.id === id)
}

export function createUser(email: string, password: string, name: string, phone: string): User {
  const newUser: MockUser = {
    id: `user-${Date.now()}`,
    email,
    name,
    phone,
    passwordHash: bcrypt.hashSync(password, 10),
    createdAt: new Date().toISOString(),
    updatedAt: new Date().toISOString()
  }
  mockUsers.push(newUser)

  // Return user without password hash
  const { passwordHash, ...user } = newUser
  return user
}

export function verifyPassword(user: MockUser, password: string): boolean {
  return bcrypt.compareSync(password, user.passwordHash)
}
