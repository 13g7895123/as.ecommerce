<template>
  <form class="card space-y-6" @submit.prevent="handleSubmit">
    <div>
      <h2 class="mb-2 text-2xl font-bold text-gray-900">會員登入</h2>
      <p class="text-sm text-gray-600">使用您的帳號登入以享受更多服務</p>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="rounded-lg bg-red-50 p-4">
      <p class="text-sm text-red-800">{{ errorMessage }}</p>
    </div>

    <!-- Email -->
    <div>
      <label for="email" class="mb-2 block text-sm font-medium text-gray-700">
        Email
      </label>
      <input
        id="email"
        name="email"
        v-model="formData.email"
        type="email"
        class="input"
        :class="{ 'input-error': errors.email }"
        placeholder="your@email.com"
        required
      />
      <p v-if="errors.email" class="mt-1 text-sm text-red-600">
        {{ errors.email }}
      </p>
    </div>

    <!-- Password -->
    <div>
      <label for="password" class="mb-2 block text-sm font-medium text-gray-700">
        密碼
      </label>
      <input
        id="password"
        name="password"
        v-model="formData.password"
        type="password"
        class="input"
        :class="{ 'input-error': errors.password }"
        placeholder="••••••••"
        required
      />
      <p v-if="errors.password" class="mt-1 text-sm text-red-600">
        {{ errors.password }}
      </p>
    </div>

    <!-- Remember Me & Forgot Password -->
    <div class="flex items-center justify-between text-sm">
      <label class="flex items-center gap-2">
        <input type="checkbox" class="rounded border-gray-300" />
        <span class="text-gray-700">記住我</span>
      </label>
      <a href="#" class="text-primary-600 hover:text-primary-700">
        忘記密碼？
      </a>
    </div>

    <!-- Submit Button -->
    <button
      type="submit"
      class="btn btn-primary w-full"
      :disabled="loading"
    >
      <span v-if="loading">登入中...</span>
      <span v-else>登入</span>
    </button>

    <!-- Register Link -->
    <div class="text-center text-sm text-gray-600">
      還沒有帳號？
      <button
        type="button"
        class="font-medium text-primary-600 hover:text-primary-700"
        @click="$emit('switch-to-register')"
      >
        立即註冊
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { loginSchema } from '~/utils/validation'
import type { LoginPayload } from '~/types/user'

interface Props {
  loading?: boolean
  errorMessage?: string
}

withDefaults(defineProps<Props>(), {
  loading: false,
  errorMessage: ''
})

const emit = defineEmits<{
  submit: [payload: LoginPayload]
  'switch-to-register': []
}>()

const formData = reactive<LoginPayload>({
  email: '',
  password: ''
})

const errors = reactive<Record<string, string>>({})

const handleSubmit = () => {
  // Clear previous errors
  Object.keys(errors).forEach(key => delete errors[key])

  // Validate
  try {
    loginSchema.parse(formData)
    emit('submit', { ...formData })
  } catch (err: any) {
    if (err.errors) {
      err.errors.forEach((error: any) => {
        errors[error.path[0]] = error.message
      })
    }
  }
}
</script>
