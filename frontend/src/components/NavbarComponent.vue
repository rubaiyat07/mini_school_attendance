<template>
  <nav class="bg-blue-600 text-white p-4 flex justify-between items-center">
    <h1 class="text-xl font-bold">{{ pageTitle }}</h1>
    <div class="flex items-center space-x-4">
      <div class="flex items-center space-x-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
        </svg>
        <span>{{ userName }}</span>
      </div>
      <button @click="logout" class="bg-red-600 text-white px-3 py-1 rounded hover:bg-red-700">
        Logout
      </button>
    </div>
  </nav>
</template>

<script setup>
import axios from 'axios'
import { useRouter, useRoute } from 'vue-router'
import { ref, computed, onMounted } from 'vue'

const router = useRouter()
const route = useRoute()

const userName = ref('Admin') // Default user name, can be made dynamic later

const pageTitle = computed(() => {
  return route.meta?.title || 'School Attendance System'
})

function logout() {
  axios.post('/logout')
    .finally(() => {
      localStorage.removeItem('token')
      router.push('/login')
    })
}
</script>
