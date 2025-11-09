<template>
  <div class="flex min-h-[400px] flex-col items-center justify-center rounded-lg bg-gray-50 p-12">
    <div class="mb-6 flex h-32 w-32 items-center justify-center rounded-full bg-gray-100">
      <span class="text-6xl">🛒</span>
    </div>
    <h2 class="mb-3 text-2xl font-bold text-gray-900">購物車是空的</h2>
    <p class="mb-8 text-center text-gray-600">
      您的購物車中還沒有商品<br />
      快去選購喜歡的商品吧！
    </p>
    <button class="btn btn-primary" @click="$emit('start-shopping')">
      開始購物
    </button>
  </div>
</template>

<script setup lang="ts">
defineEmits<{
  'start-shopping': []
}>()
</script>
