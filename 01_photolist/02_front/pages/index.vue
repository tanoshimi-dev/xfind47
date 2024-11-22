<script setup lang="ts">
import type { NuxtError } from '#app'

const props = defineProps({
  error: Object as () => NuxtError
})
</script>

<template>
  <div class="bg-slate-400">
    <h1>Welcome to the homepage</h1>
    <AppAlert>
      This is an auto-imported component
    </AppAlert>
  </div>
  <div>
    <NuxtLink to="/users">Go to users</NuxtLink>
  </div> 
  <div>
    <NuxtLink to="/about">Go to about</NuxtLink>
  </div> 
  <div>
    <NuxtLink to="/products">Go to products</NuxtLink>
  </div> 
</template>
