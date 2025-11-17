<template>
  <div class="p-6">
    <div class="flex justify-between items-center mb-4">
      <h1 class="text-2xl font-bold">Students List</h1>
      <button
        @click="showAddForm = true"
        class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700"
      >
        Add Student
      </button>
    </div>

    <!-- Search Bar -->
    <input
      v-model="store.search"
      @input="store.loadStudents()"
      type="text"
      placeholder="Search students..."
      class="border px-3 py-2 mb-4 w-full rounded"
    />

    <!-- Student Table -->
    <StudentTable :students="store.students" :loading="store.loading" />

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <button
        :disabled="store.pagination.current_page === 1"
        @click="changePage(store.pagination.current_page - 1)"
        class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50"
      >
        Previous
      </button>

      <span>
        Page {{ store.pagination.current_page }} of {{ store.pagination.last_page }}
      </span>

      <button
        :disabled="store.pagination.current_page === store.pagination.last_page"
        @click="changePage(store.pagination.current_page + 1)"
        class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50"
      >
        Next
      </button>
    </div>

    <!-- Add Student Form Modal -->
    <AddStudentForm
      :showModal="showAddForm"
      @close="showAddForm = false"
      @student-added="onStudentAdded"
    />
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import { useStudentStore } from "../stores/studentStore";
import StudentTable from "../components/StudentTable.vue";
import AddStudentForm from "../components/AddStudentForm.vue";

const store = useStudentStore();
const showAddForm = ref(false);

onMounted(() => {
  store.loadStudents();
});

function changePage(page) {
  store.loadStudents(page);
}

function onStudentAdded() {
  store.loadStudents(); // Refresh the student list
}
</script>
