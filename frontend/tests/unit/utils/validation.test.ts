/**
 * Validation Utility Tests
 */

import { describe, it, expect } from 'vitest'
import {
  loginSchema,
  registerSchema,
  shippingInfoSchema
} from '~/utils/validation'

describe('Validation Schemas', () => {
  describe('loginSchema', () => {
    it('should validate correct login data', () => {
      const validData = {
        email: 'test@example.com',
        password: 'password123'
      }

      const result = loginSchema.safeParse(validData)
      expect(result.success).toBe(true)
    })

    it('should reject invalid email', () => {
      const invalidData = {
        email: 'not-an-email',
        password: 'password123'
      }

      const result = loginSchema.safeParse(invalidData)
      expect(result.success).toBe(false)
    })

    it('should reject short password', () => {
      const invalidData = {
        email: 'test@example.com',
        password: ''
      }

      const result = loginSchema.safeParse(invalidData)
      expect(result.success).toBe(false)
    })

    it('should reject missing fields', () => {
      const result = loginSchema.safeParse({})
      expect(result.success).toBe(false)
    })
  })

  describe('registerSchema', () => {
    it('should validate correct registration data', () => {
      const validData = {
        email: 'test@example.com',
        password: 'password123',
        confirmPassword: 'password123',
        name: 'Test User',
        phone: '0912345678'
      }

      const result = registerSchema.safeParse(validData)
      expect(result.success).toBe(true)
    })

    it('should reject invalid phone number', () => {
      const invalidData = {
        email: 'test@example.com',
        password: 'password123',
        confirmPassword: 'password123',
        name: 'Test User',
        phone: '123'
      }

      const result = registerSchema.safeParse(invalidData)
      expect(result.success).toBe(false)
    })

    it('should reject empty name', () => {
      const invalidData = {
        email: 'test@example.com',
        password: 'password123',
        confirmPassword: 'password123',
        name: '',
        phone: '0912345678'
      }

      const result = registerSchema.safeParse(invalidData)
      expect(result.success).toBe(false)
    })
  })

  describe('shippingInfoSchema', () => {
    it('should validate correct shipping info', () => {
      const validData = {
        recipientName: 'Test User',
        recipientPhone: '0912345678',
        city: '台北市',
        district: '中正區',
        address: '測試路123號',
        postalCode: '100'
      }

      const result = shippingInfoSchema.safeParse(validData)
      expect(result.success).toBe(true)
    })

    it('should reject invalid postal code', () => {
      const invalidData = {
        recipientName: 'Test User',
        recipientPhone: '0912345678',
        city: '台北市',
        district: '中正區',
        address: '測試路123號',
        postalCode: '123456'
      }

      const result = shippingInfoSchema.safeParse(invalidData)
      expect(result.success).toBe(false)
    })

    it('should reject missing required fields', () => {
      const invalidData = {
        recipientName: 'Test User',
        recipientPhone: '0912345678'
      }

      const result = shippingInfoSchema.safeParse(invalidData)
      expect(result.success).toBe(false)
    })

    it('should accept various phone formats', () => {
      const validFormats = [
        '0912345678',
        '0987654321'
      ]

      validFormats.forEach(phone => {
        const data = {
          recipientName: 'Test User',
          recipientPhone: phone,
          city: '台北市',
          district: '中正區',
          address: '測試路123號',
          postalCode: '100'
        }

        const result = shippingInfoSchema.safeParse(data)
        expect(result.success).toBe(true)
      })
    })
  })
})
