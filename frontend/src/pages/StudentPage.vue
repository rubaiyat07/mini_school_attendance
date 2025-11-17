<template>
  <div class="p-0">
    <div class="flex justify-between items-center mb-0">
      <h1 class="text-2xl font-bold">Students List</h1>
      <button
        @click="showAddForm = true"
        class="px-4 bg-green-600 text-white rounded hover:bg-green-700"
      >
        Add Student
      </button>
    </div>

    <!-- Filters -->
    <div class="flex gap-1 items-end">
      <!-- Search Bar -->
      <div class="flex-0 min-w-64">
        <label class="block font-semibold">Search Students</label>
        <input
          v-model="search"
          @input="loadStudents()"
          type="text"
          placeholder="Search by name or student ID..."
          class="border px-3 py-2 rounded w-full"
        />
      </div>

      <!-- Select Class -->
      <div class="w-100">
        <label class="block font-semibold">Class</label>
        <select
          v-model="selectedClass"
          @change="onClassChange"
          class="border px-3 py-2 rounded w-full"
        >
          <option disabled value="">Choose a class</option>
          <option v-for="c in classes" :key="c" :value="c">{{ c }}</option>
        </select>
      </div>

      <!-- Select Section -->
      <div class="w-100">
        <label class="block font-semibold">Section</label>
        <select
          v-model="selectedSection"
          @change="loadStudents"
          class="border px-3 py-2 rounded w-full"
          :disabled="!selectedClass"
        >
          <option disabled value="">Choose a section</option>
          <option v-for="s in sections" :key="s" :value="s">{{ s }}</option>
        </select>
      </div>

      <!-- Reset Filters Button -->
      <div class="w-100">
        <button
          @click="resetFilters"
          class="px-3 py-2 bg-gray-600 text-white rounded hover:bg-gray-700"
        >
          Reset Filters
        </button>
      </div>
    </div>

    <!-- Students Table -->
    <div v-if="loading" class="text-center p-4">Loading students...</div>

    <table v-else class="min-w-full border mt-4">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 border">ID</th>
          <th class="p-3 border">Name</th>
          <th class="p-3 border">Class</th>
          <th class="p-3 border">Section</th>
          <th class="p-3 border">Roll</th>
          <th class="p-3 border">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="student in students"
          :key="student.id"
        >
          <td class="p-3 border">{{ student.id }}</td>
          <td class="p-3 border">{{ student.name }}</td>
          <td class="p-3 border">{{ student.class }}</td>
          <td class="p-3 border">{{ student.section }}</td>
          <td class="p-3 border">{{ student.roll }}</td>
          <td class="p-3 border">
            <button class="px-2 py-1 bg-blue-500 text-white rounded text-sm">Edit</button>
            <button class="ml-2 px-2 py-1 bg-red-500 text-white rounded text-sm">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
      <button
        :disabled="pagination.current_page === 1"
        @click="changePage(pagination.current_page - 1)"
        class="px-4 py-2 bg-blue-600 text-white rounded disabled:opacity-50"
      >
        Previous
      </button>

      <span>
        Page {{ pagination.current_page }} of {{ pagination.last_page }}
      </span>

      <button
        :disabled="pagination.current_page === pagination.last_page"
        @click="changePage(pagination.current_page + 1)"
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
import axios from "axios";
import { fetchClasses } from "@/api/studentApi";
import StudentTable from "../components/StudentTable.vue";
import AddStudentForm from "../components/AddStudentForm.vue";

const selectedClass = ref("");
const selectedSection = ref("");
const classes = ref([]);
const sections = ref([]);
const students = ref([]);
const pagination = ref({});
const loading = ref(false);
const search = ref("");
const showAddForm = ref(false);

onMounted(async () => {
  try {
    const response = await fetchClasses();
    classes.value = response.data.classes;
  } catch (error) {
    console.error('Error fetching classes:', error);
  }
  loadStudents();
});

// Load sections for selected class
async function onClassChange() {
  selectedSection.value = "";
  sections.value = [];
  if (!selectedClass.value) return;

  try {
    const response = await axios.get(`/students/sections?class=${selectedClass.value}`);
    sections.value = response.data.sections;
  } catch (error) {
    console.error("Error loading sections:", error);
  }
  loadStudents();
}

// Load Students with filters
async function loadStudents(page = 1) {
  loading.value = true;

  try {
    const params = { page };
    if (search.value) {
      params.search = search.value;
    }
    if (selectedClass.value) {
      params.class = selectedClass.value;
    }
    if (selectedSection.value) {
      params.section = selectedSection.value;
    }

    const response = await axios.get('/students', { params });
    students.value = response.data.data;
    pagination.value = {
      current_page: response.data.current_page,
      last_page: response.data.last_page,
    };
  } catch (error) {
    console.error('Error loading students:', error);
  } finally {
    loading.value = false;
  }
}

function changePage(page) {
  loadStudents(page);
}

function resetFilters() {
  search.value = "";
  selectedClass.value = "";
  selectedSection.value = "";
  sections.value = [];
  loadStudents();
}

function onStudentAdded() {
  loadStudents(); // Refresh the student list
}
</script>
