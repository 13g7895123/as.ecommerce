<template>
  <div class="card space-y-6">
    <h2 class="text-xl font-bold text-gray-900">收件資訊</h2>

    <div class="space-y-4">
      <!-- Name -->
      <div>
        <label for="name" class="mb-2 block text-sm font-medium text-gray-700">
          收件人姓名 <span class="text-red-500">*</span>
        </label>
        <input
          id="name"
          v-model="formData.name"
          type="text"
          class="input"
          :class="{ 'input-error': errors.name }"
          placeholder="請輸入收件人姓名"
          required
        />
        <p v-if="errors.name" class="mt-1 text-sm text-red-600">
          {{ errors.name }}
        </p>
      </div>

      <!-- Phone -->
      <div>
        <label for="phone" class="mb-2 block text-sm font-medium text-gray-700">
          手機號碼 <span class="text-red-500">*</span>
        </label>
        <input
          id="phone"
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

      <!-- City & District -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label for="city" class="mb-2 block text-sm font-medium text-gray-700">
            城市 <span class="text-red-500">*</span>
          </label>
          <input
            id="city"
            v-model="formData.city"
            type="text"
            class="input"
            :class="{ 'input-error': errors.city }"
            placeholder="台北市"
            required
          />
          <p v-if="errors.city" class="mt-1 text-sm text-red-600">
            {{ errors.city }}
          </p>
        </div>

        <div>
          <label for="district" class="mb-2 block text-sm font-medium text-gray-700">
            地區 <span class="text-red-500">*</span>
          </label>
          <input
            id="district"
            v-model="formData.district"
            type="text"
            class="input"
            :class="{ 'input-error': errors.district }"
            placeholder="中正區"
            required
          />
          <p v-if="errors.district" class="mt-1 text-sm text-red-600">
            {{ errors.district }}
          </p>
        </div>
      </div>

      <!-- Address -->
      <div>
        <label for="address" class="mb-2 block text-sm font-medium text-gray-700">
          詳細地址 <span class="text-red-500">*</span>
        </label>
        <input
          id="address"
          v-model="formData.address"
          type="text"
          class="input"
          :class="{ 'input-error': errors.address }"
          placeholder="請輸入詳細地址"
          required
        />
        <p v-if="errors.address" class="mt-1 text-sm text-red-600">
          {{ errors.address }}
        </p>
      </div>

      <!-- Postal Code -->
      <div>
        <label for="postalCode" class="mb-2 block text-sm font-medium text-gray-700">
          郵遞區號 <span class="text-red-500">*</span>
        </label>
        <input
          id="postalCode"
          v-model="formData.postalCode"
          type="text"
          class="input"
          :class="{ 'input-error': errors.postalCode }"
          placeholder="100"
          required
        />
        <p v-if="errors.postalCode" class="mt-1 text-sm text-red-600">
          {{ errors.postalCode }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { shippingInfoSchema } from '~/utils/validation'
import type { ShippingInfo } from '~/types/address'

const formData = reactive<ShippingInfo>({
  name: '',
  phone: '',
  city: '',
  district: '',
  address: '',
  postalCode: ''
})

const errors = reactive<Record<string, string>>({})

// 自動填入使用者資訊
const { user } = useAuth()
if (user.value) {
  formData.name = user.value.name
  formData.phone = user.value.phone
}

const validate = (): boolean => {
  // Clear previous errors
  Object.keys(errors).forEach(key => delete errors[key])

  try {
    shippingInfoSchema.parse(formData)
    return true
  } catch (err: any) {
    if (err.errors) {
      err.errors.forEach((error: any) => {
        errors[error.path[0]] = error.message
      })
    }
    return false
  }
}

const getData = (): ShippingInfo => {
  return { ...formData }
}

defineExpose({
  validate,
  getData
})
</script>
