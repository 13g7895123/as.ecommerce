<template>
  <div class="card animate-pulse">
    <!-- 圖片 Skeleton -->
    <div class="mb-4 aspect-square rounded-lg bg-gray-200"></div>

    <!-- 內容 Skeleton -->
    <div class="space-y-3">
      <!-- 標題 -->
      <div class="h-5 w-3/4 rounded bg-gray-200"></div>
      <div class="h-5 w-1/2 rounded bg-gray-200"></div>

      <!-- 描述 -->
      <div class="space-y-2">
        <div class="h-3 w-full rounded bg-gray-200"></div>
        <div class="h-3 w-4/5 rounded bg-gray-200"></div>
      </div>

      <!-- 價格 -->
      <div class="h-8 w-1/3 rounded bg-gray-200"></div>

      <!-- 按鈕 -->
      <div class="flex gap-2">
        <div class="h-10 flex-1 rounded-lg bg-gray-200"></div>
        <div class="h-10 w-24 rounded-lg bg-gray-200"></div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
// Skeleton loader for product card
</script>
