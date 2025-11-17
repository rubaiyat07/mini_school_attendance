<template>
  <router-view v-if="!$route.meta.requiresAuth" />
  <Layout v-else />
</template>

<script setup>
import Layout from './components/Layout.vue'
</script>

<style scoped>
</style>
