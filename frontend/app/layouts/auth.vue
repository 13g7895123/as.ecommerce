<script setup lang="ts">
const { toasts } = useToast()
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
    <div class="w-full max-w-md">
      <!-- Logo -->
      <div class="text-center mb-8">
        <NuxtLink to="/" class="text-3xl font-bold text-primary-600">
          購物網站
        </NuxtLink>
      </div>

      <!-- Content -->
      <div class="bg-white rounded-lg shadow-lg p-8">
        <slot />
      </div>

      <!-- Back to Home -->
      <div class="text-center mt-6">
        <NuxtLink to="/" class="text-sm text-gray-600 hover:text-primary-600 transition-colors">
          ← 返回首頁
        </NuxtLink>
      </div>
    </div>

    <!-- Toast Notifications -->
    <div class="fixed top-4 right-4 z-50 space-y-2">
      <BaseToast
        v-for="toast in toasts"
        :key="toast.id"
        :show="true"
        :type="toast.type"
        :message="toast.message"
        :duration="0"
        @close="useToast().remove(toast.id)"
      />
    </div>
  </div>
</template>
