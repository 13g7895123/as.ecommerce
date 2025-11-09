/**
 * POST /api/auth/register
 * 會員註冊
 */

import { getUserByEmail, createUser } from '../../mock/users'
import type { RegisterPayload, AuthResponse } from '~/types/user'

export default defineEventHandler(async (event): Promise<AuthResponse> => {
  const body = await readBody<RegisterPayload>(event)

  // 驗證必填欄位
  if (!body.email || !body.password || !body.name || !body.phone) {
    throw createError({
      statusCode: 400,
      statusMessage: '所有欄位皆為必填'
    })
  }

  // 檢查 Email 是否已存在
  const existingUser = getUserByEmail(body.email)

  if (existingUser) {
    throw createError({
      statusCode: 409,
      statusMessage: '此 Email 已被註冊'
    })
  }

  // 建立新使用者
  const user = createUser(body.email, body.password, body.name, body.phone)

  // 產生 Token（模擬）
  const token = `mock-token-${user.id}-${Date.now()}`

  return {
    user,
    token
  }
})
