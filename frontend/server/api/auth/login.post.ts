/**
 * POST /api/auth/login
 * 會員登入
 */

import { getUserByEmail, verifyPassword } from '../../mock/users'
import type { LoginPayload, AuthResponse } from '~/types/user'

export default defineEventHandler(async (event): Promise<AuthResponse> => {
  const body = await readBody<LoginPayload>(event)

  // 驗證必填欄位
  if (!body.email || !body.password) {
    throw createError({
      statusCode: 400,
      statusMessage: 'Email 與密碼為必填欄位'
    })
  }

  // 查詢使用者
  const mockUser = getUserByEmail(body.email)

  if (!mockUser) {
    throw createError({
      statusCode: 401,
      statusMessage: 'Email 或密碼錯誤'
    })
  }

  // 驗證密碼
  const isPasswordValid = verifyPassword(mockUser, body.password)

  if (!isPasswordValid) {
    throw createError({
      statusCode: 401,
      statusMessage: 'Email 或密碼錯誤'
    })
  }

  // 移除密碼欄位
  const { passwordHash, ...user } = mockUser

  // 產生 Token（模擬）
  const token = `mock-token-${user.id}-${Date.now()}`

  return {
    user,
    token
  }
})
