<template>
  <div class="max-w-md mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Login</h1>

    <input v-model="email" type="email" placeholder="Email" class="border p-2 w-full mb-3"/>
    <input v-model="password" type="password" placeholder="Password" class="border p-2 w-full mb-3"/>

    <button @click="login" class="bg-blue-600 text-white px-4 py-2 rounded w-full">
      Login
    </button>

    <p v-if="error" class="text-red-500 mt-2">{{ error }}</p>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')

async function login() {
  error.value = ''
  try {
    const res = await axios.post('/login', { email: email.value, password: password.value })
    localStorage.setItem('token', res.data.token)
    router.push('/dashboard') // redirect after login
  } catch (e) {
    error.value = e.response?.data?.message || 'Login failed'
  }
}
</script>
