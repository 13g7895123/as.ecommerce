<template>
  <nav class="bg-gray-50 border-b border-gray-200">
    <div class="container-custom">
      <div class="flex items-center gap-6 overflow-x-auto py-3 no-scrollbar md:justify-center">
        <NuxtLink
          to="/"
          class="whitespace-nowrap text-sm font-medium transition-colors hover:text-primary-600"
          :class="!currentCategory ? 'text-primary-600' : 'text-gray-700'"
        >
          首頁
        </NuxtLink>

        <NuxtLink
          v-for="category in categories"
          :key="category.id"
          :to="`/products/category/${category.slug}`"
          class="whitespace-nowrap text-sm font-medium transition-colors hover:text-primary-600"
          :class="currentCategory?.id === category.id ? 'text-primary-600' : 'text-gray-700'"
        >
          {{ category.name }}
        </NuxtLink>
      </div>
    </div>
  </nav>
</template>

<script setup lang="ts">
import type { Category } from '~/types/category'

interface Props {
  categories: Category[]
  currentCategory?: Category | null
}

defineProps<Props>()
</script>

<style scoped>
.no-scrollbar::-webkit-scrollbar {
  display: none;
}
.no-scrollbar {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
