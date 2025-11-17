<template>
  <div class="p-6">

    <!-- Title -->
    <h1 class="text-2xl font-bold mb-4">Take Attendance</h1>

    <!-- Select Class -->
    <label class="font-semibold">Select Class</label>
    <select
      v-model="selectedClass"
      @change="loadSections"
      class="border px-3 py-2 rounded w-full mb-4"
    >
      <option disabled value="">Choose a class</option>
      <option v-for="c in classes" :key="c" :value="c">{{ c }}</option>
    </select>

    <!-- Select Section -->
    <label class="font-semibold">Select Section</label>
    <select
      v-model="selectedSection"
      @change="loadStudents"
      class="border px-3 py-2 rounded w-full mb-4"
      :disabled="!selectedClass"
    >
      <option disabled value="">Choose a section</option>
      <option v-for="s in sections" :key="s" :value="s">{{ s }}</option>
    </select>

    <!-- Select Date -->
    <label class="font-semibold">Select Date</label>
    <input
      type="date"
      v-model="date"
      class="border px-3 py-2 rounded w-full mb-4"
    />

    <!-- Students Table -->
    <div v-if="loading" class="text-center p-4">Loading students...</div>

    <table v-else class="min-w-full border mt-4">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 border">Student</th>
          <th class="p-3 border">Status</th>
          <th class="p-3 border">Note</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="(item, index) in attendanceData"
          :key="index"
        >
          <td class="p-3 border">{{ item.student.name }}</td>

          <td class="p-3 border">
            <input
              type="checkbox"
              v-model="item.present"
              @change="updateStatus(item)"
              class="w-1 h-1 cursor-pointer"
            />
            <span class="ml-2">{{ item.present ? 'Present' : 'Absent' }}</span>
          </td>

          <td class="p-3 border">
            <input
              v-model="item.note"
              type="text"
              class="border px-2 py-1 rounded w-full"
              placeholder="Note (optional)"
            />
          </td>
        </tr>
      </tbody>
    </table>

    <!-- Submit Button -->
    <button
      @click="submitAttendance"
      :disabled="submitting"
      class="mt-4 px-5 py-2 bg-blue-600 text-white rounded disabled:opacity-50"
    >
      {{ submitting ? "Submitting..." : "Submit Attendance" }}
    </button>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { fetchClasses } from "@/api/studentApi";

const selectedClass = ref("");
const selectedSection = ref("");
const classes = ref([]);
const sections = ref([]);
const students = ref([]);
const attendanceData = ref([]);
const date = ref(new Date().toISOString().substr(0, 10));
const loading = ref(false);
const submitting = ref(false);

onMounted(async () => {
  try {
    const response = await fetchClasses();
    classes.value = response.data.classes;
  } catch (error) {
    console.error('Error fetching classes:', error);
  }
});

// Update status based on checkbox
function updateStatus(item) {
  item.status = item.present ? "Present" : "Absent";
}

// Load sections for selected class
async function loadSections() {
  selectedSection.value = "";
  sections.value = [];
  if (!selectedClass.value) return;

  try {
    // FIXED: Remove /api prefix since baseURL already includes it
    const response = await axios.get(`/students/sections?class=${selectedClass.value}`);
    sections.value = response.data.sections;
  } catch (error) {
    console.error("Error loading sections:", error);
  }
}

// Load Students by Class and Section
async function loadStudents() {
  if (!selectedClass.value || !selectedSection.value) return;

  loading.value = true;

  try {
    // FIXED: Remove /api prefix
    const response = await axios.get(`/students?class=${selectedClass.value}&section=${selectedSection.value}`);

    students.value = response.data.data;

    attendanceData.value = students.value.map(s => ({
      student_id: s.id,
      student: s,
      present: false,
      status: "Absent",
      note: ""
    }));

  } catch (error) {
    console.error(error);
  } finally {
    loading.value = false;
  }
}

// Submit Attendance (Bulk API)
async function submitAttendance() {
  submitting.value = true;

  try {
    // FIXED: Remove /api prefix
    await axios.post("/attendance/bulk", {
      date: date.value,
      recorded_by: "Teacher A",
      attendances: attendanceData.value
    });

    alert("Attendance Submitted Successfully!");

  } catch (error) {
    console.error(error);
    alert("Error submitting attendance");
  } finally {
    submitting.value = false;
  }
}
</script>
