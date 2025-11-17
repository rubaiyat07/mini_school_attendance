<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard</h1>

    <!-- Key Metrics -->
    <div class="flex flex-wrap gap-12 mb-8">
      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-700">Total Students</h3>
        <p class="text-3xl font-bold text-blue-600">{{ dashboardData.total_students || 0 }}</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-700">Overall Attendance Rate</h3>
        <p class="text-3xl font-bold text-green-600">{{ dashboardData.overall_attendance_rate || 0 }}%</p>
      </div>

      <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-lg font-semibold text-gray-700">Today's Summary</h3>
        <div class="flex justify-between mt-2">
          <span class="text-green-600">Present: {{ todaySummary.Present || 0 }}</span>
          <span class="text-red-600">Absent: {{ todaySummary.Absent || 0 }}</span>
          <span class="text-yellow-600">Late: {{ todaySummary.Late || 0 }}</span>
        </div>
      </div>
    </div>

    <!-- Attendance Rate by Class/Section -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
      <h3 class="text-lg font-semibold text-gray-700 mb-4">Attendance Rate by Class</h3>

      <!-- Selection Bar -->
      <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-2">View by:</label>
        <select v-model="viewMode" @change="updateChart" class="border px-3 py-2 rounded">
          <option value="class">Class</option>
          <option value="class_section">Class & Section</option>
        </select>
      </div>

      <!-- Bar Chart -->
      <canvas id="attendanceChart" class="w-full"></canvas>
    </div>


  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';

import { fetchDashboardData } from '@/api/attendance';
import Chart from 'chart.js/auto';

const dashboardData = ref({});
const todaySummary = ref({});
const viewMode = ref('class');
let chartInstance = null;

async function loadDashboardData() {
  try {
    const response = await fetchDashboardData();
    dashboardData.value = response.data.data;
    updateChart();
  } catch (error) {
    console.error('Error loading dashboard data:', error);
  }
}

function updateChart() {
  const ctx = document.getElementById('attendanceChart');
  if (chartInstance) {
    chartInstance.destroy();
  }

  let labels = [];
  let data = [];

  if (viewMode.value === 'class') {
    labels = dashboardData.value.attendance_rate_by_class?.map(item => item.class) || [];
    data = dashboardData.value.attendance_rate_by_class?.map(item => item.rate) || [];
  } else {
    labels = dashboardData.value.attendance_rate_by_class_section?.map(item => `${item.class} - ${item.section}`) || [];
    data = dashboardData.value.attendance_rate_by_class_section?.map(item => item.rate) || [];
  }

  chartInstance = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        label: 'Attendance Rate (%)',
        data: data,
        backgroundColor: 'rgba(54, 162, 235, 0.6)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          max: 100
        }
      }
    }
  });
}

onMounted(() => {
  loadDashboardData();
});
</script>
