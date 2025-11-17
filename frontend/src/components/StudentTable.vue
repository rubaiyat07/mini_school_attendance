<template>
  <div class="overflow-x-auto shadow rounded-lg">
    <table class="min-w-full text-left border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 border">ID</th>
          <th class="p-3 border">Name</th>
          <th class="p-3 border">Roll</th>
          <th class="p-3 border">Class</th>
          <th class="p-3 border">Present Days</th>
          <th class="p-3 border">Absent Days</th>
        </tr>
      </thead>

      <!-- Loading State -->
      <tbody v-if="loading">
        <tr>
          <td colspan="6" class="text-center p-4">Loading...</td>
        </tr>
      </tbody>

      <!-- Table Rows -->
      <tbody v-else>
        <tr v-for="student in students" :key="student.id" class="hover:bg-gray-50">
          <td class="p-3 border">{{ student.id }}</td>
          <td class="p-3 border">{{ student.name }}</td>
          <td class="p-3 border">{{ student.roll }}</td>
          <td class="p-3 border">{{ student.class }}</td>
          <td class="p-3 border">{{ student.present_days || 0 }}</td>
          <td class="p-3 border">{{ student.absent_days || 0 }}</td>
        </tr>
      </tbody>
    </table>

    <!-- No data -->
    <div v-if="students.length === 0 && !loading" class="text-center p-4">
      No students found.
    </div>
  </div>
</template>

<script setup>
defineProps({
  students: Array,
  loading: Boolean
});
</script>
