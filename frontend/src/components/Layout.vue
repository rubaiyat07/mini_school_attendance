<template>
  <div class="flex min-h-screen bg-gray-50">
    <SidebarComponent />
    <div class="flex-1 flex flex-col">
      <NavbarComponent />
      <main>
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import SidebarComponent from './SidebarComponent.vue'
import NavbarComponent from './NavbarComponent.vue'
</script>
