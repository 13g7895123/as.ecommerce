/**
 * Currency Utility Tests
 */

import { describe, it, expect } from 'vitest'
import { formatCurrency } from '~/utils/currency'

describe('formatCurrency', () => {
  it('should format integer amounts correctly', () => {
    expect(formatCurrency(1000)).toBe('NT$1,000')
    expect(formatCurrency(100)).toBe('NT$100')
    expect(formatCurrency(0)).toBe('NT$0')
  })

  it('should format decimal amounts correctly', () => {
    expect(formatCurrency(1234.56)).toBe('NT$1,234.56')
    expect(formatCurrency(99.99)).toBe('NT$99.99')
  })

  it('should handle large numbers', () => {
    expect(formatCurrency(1000000)).toBe('NT$1,000,000')
    expect(formatCurrency(123456789)).toBe('NT$123,456,789')
  })

  it('should handle negative numbers', () => {
    expect(formatCurrency(-100)).toBe('-NT$100')
    expect(formatCurrency(-1234.56)).toBe('-NT$1,234.56')
  })

  it('should handle edge cases', () => {
    expect(formatCurrency(0.01)).toBe('NT$0.01')
    expect(formatCurrency(0.99)).toBe('NT$0.99')
  })
})
