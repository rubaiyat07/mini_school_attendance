<template>
  <div class="w-64 bg-gray-800 text-white h-screen fixed left-0 top-0 p-4">
    <h2 class="text-xl font-bold mb-6">School Attendance</h2>
    <nav>
      <ul class="space-y-2">
        <li>
          <router-link to="/dashboard" class="block py-2 px-4 rounded hover:bg-gray-700">
            Dashboard
          </router-link>
        </li>
        <li>
          <router-link to="/attendance" class="block py-2 px-4 rounded hover:bg-gray-700">
            Take Attendance
          </router-link>
        </li>
        <li>
          <router-link to="/students" class="block py-2 px-4 rounded hover:bg-gray-700">
            Students
          </router-link>
        </li>
        <li>
          <router-link to="/monthly-report" class="block py-2 px-4 rounded hover:bg-gray-700">
            Monthly Report
          </router-link>
        </li>
      </ul>
    </nav>
  </div>
</template>

<script setup>
// No script needed for this component
</script>
