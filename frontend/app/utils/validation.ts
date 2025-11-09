/**
 * 表單驗證 Schema（使用 Zod）
 */

import { z } from 'zod'

/**
 * Email 驗證
 */
export const emailSchema = z
  .string()
  .min(1, 'Email 為必填欄位')
  .email('請輸入有效的 Email 格式')

/**
 * 密碼驗證（至少 8 個字元，包含英文與數字）
 */
export const passwordSchema = z
  .string()
  .min(8, '密碼至少需要 8 個字元')
  .regex(/[a-zA-Z]/, '密碼必須包含英文字母')
  .regex(/[0-9]/, '密碼必須包含數字')

/**
 * 手機號碼驗證（台灣手機格式）
 */
export const phoneSchema = z
  .string()
  .min(1, '手機號碼為必填欄位')
  .regex(/^09\d{8}$/, '請輸入有效的手機號碼格式（例：0912345678）')

/**
 * 姓名驗證
 */
export const nameSchema = z.string().min(1, '姓名為必填欄位').min(2, '姓名至少需要 2 個字元')

/**
 * 地址驗證
 */
export const addressSchema = z.string().min(1, '地址為必填欄位').min(5, '請輸入完整地址')

/**
 * 郵遞區號驗證
 */
export const postalCodeSchema = z
  .string()
  .min(1, '郵遞區號為必填欄位')
  .regex(/^\d{3,5}$/, '請輸入有效的郵遞區號')

/**
 * 會員註冊表單驗證
 */
export const registerSchema = z
  .object({
    email: emailSchema,
    password: passwordSchema,
    confirmPassword: z.string().min(1, '請確認密碼'),
    name: nameSchema,
    phone: phoneSchema
  })
  .refine((data) => data.password === data.confirmPassword, {
    message: '密碼與確認密碼不相符',
    path: ['confirmPassword']
  })

/**
 * 會員登入表單驗證
 */
export const loginSchema = z.object({
  email: emailSchema,
  password: z.string().min(1, '密碼為必填欄位')
})

/**
 * 收件資訊驗證
 */
export const shippingInfoSchema = z.object({
  recipientName: nameSchema,
  recipientPhone: phoneSchema,
  city: z.string().min(1, '城市為必填欄位'),
  district: z.string().min(1, '地區為必填欄位'),
  address: addressSchema,
  postalCode: postalCodeSchema
})

/**
 * 個人資料更新驗證
 */
export const updateProfileSchema = z.object({
  name: nameSchema.optional(),
  phone: phoneSchema.optional(),
  currentPassword: z.string().optional(),
  newPassword: passwordSchema.optional()
})

/**
 * 地址管理驗證
 */
export const addressFormSchema = z.object({
  recipientName: nameSchema,
  recipientPhone: phoneSchema,
  city: z.string().min(1, '城市為必填欄位'),
  district: z.string().min(1, '地區為必填欄位'),
  address: addressSchema,
  postalCode: postalCodeSchema,
  isDefault: z.boolean().optional()
})

/**
 * 數量驗證
 */
export const quantitySchema = z.number().int().min(1, '數量至少為 1')

/**
 * 產品 ID 驗證
 */
export const productIdSchema = z.string().min(1, '產品 ID 為必填')
