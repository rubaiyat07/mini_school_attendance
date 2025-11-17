<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Today's Attendance Summary</h1>

    <div v-if="loading" class="text-center p-4">Loading summary...</div>

    <div v-else class="grid grid-cols-3 gap-4">

      <div class="p-4 bg-green-100 border rounded text-center">
        <h2 class="text-xl font-bold">Present</h2>
        <p class="text-3xl font-bold">{{ summary.Present || 0 }}</p>
      </div>

      <div class="p-4 bg-red-100 border rounded text-center">
        <h2 class="text-xl font-bold">Absent</h2>
        <p class="text-3xl font-bold">{{ summary.Absent || 0 }}</p>
      </div>

      <div class="p-4 bg-yellow-100 border rounded text-center">
        <h2 class="text-xl font-bold">Late</h2>
        <p class="text-3xl font-bold">{{ summary.Late || 0 }}</p>
      </div>
    </div>

    <!-- Chart -->
    <canvas id="summaryChart" class="mt-6"></canvas>
  </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import Chart from "chart.js/auto";

const summary = ref({});
const loading = ref(true);

async function loadSummary() {
  try {
    const res = await axios.get("/api/attendance/summary/today");
    summary.value = res.data.data;
  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
    drawChart();
  }
}

function drawChart() {
  const ctx = document.getElementById("summaryChart");

  new Chart(ctx, {
    type: "pie",
    data: {
      labels: ["Present", "Absent", "Late"],
      datasets: [
        {
          data: [
            summary.value.Present || 0,
            summary.value.Absent || 0,
            summary.value.Late || 0
          ]
        }
      ]
    }
  });
}

onMounted(() => {
  loadSummary();
});
</script>
