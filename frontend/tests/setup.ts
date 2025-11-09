/**
 * Test Setup File
 * Configure global mocks and utilities
 */

import { vi } from 'vitest'

// Mock window if not defined
if (typeof window === 'undefined') {
  global.window = {} as any
}

// Mock localStorage
const localStorageMock = (() => {
  let store: Record<string, string> = {}

  return {
    getItem: (key: string) => store[key] || null,
    setItem: (key: string, value: string) => {
      store[key] = value.toString()
    },
    removeItem: (key: string) => {
      delete store[key]
    },
    clear: () => {
      store = {}
    }
  }
})()

global.localStorage = localStorageMock as Storage

// Mock $fetch
global.$fetch = vi.fn()
