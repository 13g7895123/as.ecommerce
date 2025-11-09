<template>
  <form class="card space-y-6" @submit.prevent="handleSubmit">
    <div>
      <h2 class="mb-2 text-2xl font-bold text-gray-900">會員註冊</h2>
      <p class="text-sm text-gray-600">建立新帳號以享受完整購物體驗</p>
    </div>

    <!-- Error Message -->
    <div v-if="errorMessage" class="rounded-lg bg-red-50 p-4">
      <p class="text-sm text-red-800">{{ errorMessage }}</p>
    </div>

    <!-- Name -->
    <div>
      <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
        姓名
      </label>
      <input
        id="name"
        name="name"
        v-model="formData.name"
        type="text"
        class="input"
        :class="{ 'input-error': errors.name }"
        placeholder="請輸入您的姓名"
        required
      />
      <p v-if="errors.name" class="mt-1 text-sm text-red-600">
        {{ errors.name }}
      </p>
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

    <!-- Phone -->
    <div>
      <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
        手機號碼
      </label>
      <input
        id="phone"
        name="phone"
        v-model="formData.phone"
        type="tel"
        class="input"
        :class="{ 'input-error': errors.phone }"
        placeholder="0912345678"
        required
      />
      <p v-if="errors.phone" class="mt-1 text-sm text-red-600">
        {{ errors.phone }}
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
        placeholder="至少 8 個字元，包含英文與數字"
        required
      />
      <p v-if="errors.password" class="mt-1 text-sm text-red-600">
        {{ errors.password }}
      </p>
    </div>

    <!-- Confirm Password -->
    <div>
      <label for="confirmPassword" class="mb-2 block text-sm font-medium text-gray-700">
        確認密碼
      </label>
      <input
        id="confirmPassword"
        name="confirmPassword"
        v-model="formData.confirmPassword"
        type="password"
        class="input"
        :class="{ 'input-error': errors.confirmPassword }"
        placeholder="請再次輸入密碼"
        required
      />
      <p v-if="errors.confirmPassword" class="mt-1 text-sm text-red-600">
        {{ errors.confirmPassword }}
      </p>
    </div>

    <!-- Terms -->
    <div class="flex items-start gap-2">
      <input
        id="terms"
        v-model="acceptTerms"
        type="checkbox"
        class="mt-1 rounded border-gray-300"
        required
      />
      <label for="terms" class="text-sm text-gray-700">
        我已閱讀並同意
        <a href="#" class="text-primary-600 hover:text-primary-700">使用條款</a>
        及
        <a href="#" class="text-primary-600 hover:text-primary-700">隱私權政策</a>
      </label>
    </div>

    <!-- Submit Button -->
    <button
      type="submit"
      class="btn btn-primary w-full"
      :disabled="loading || !acceptTerms"
    >
      <span v-if="loading">註冊中...</span>
      <span v-else>註冊</span>
    </button>

    <!-- Login Link -->
    <div class="text-center text-sm text-gray-600">
      已經有帳號？
      <button
        type="button"
        class="font-medium text-primary-600 hover:text-primary-700"
        @click="$emit('switch-to-login')"
      >
        立即登入
      </button>
    </div>
  </form>
</template>

<script setup lang="ts">
import { registerSchema } from '~/utils/validation'
import type { RegisterPayload } from '~/types/user'

interface Props {
  loading?: boolean
  errorMessage?: string
}

withDefaults(defineProps<Props>(), {
  loading: false,
  errorMessage: ''
})

const emit = defineEmits<{
  submit: [payload: RegisterPayload]
  'switch-to-login': []
}>()

const formData = reactive({
  name: '',
  email: '',
  phone: '',
  password: '',
  confirmPassword: ''
})

const acceptTerms = ref(false)
const errors = reactive<Record<string, string>>({})

const handleSubmit = () => {
  // Clear previous errors
  Object.keys(errors).forEach(key => delete errors[key])

  // Validate
  try {
    registerSchema.parse(formData)
    const { confirmPassword, ...payload } = formData
    emit('submit', payload as RegisterPayload)
  } catch (err: any) {
    if (err.errors) {
      err.errors.forEach((error: any) => {
        errors[error.path[0]] = error.message
      })
    }
  }
}
</script>
