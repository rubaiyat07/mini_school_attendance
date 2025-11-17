<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Monthly Attendance Report</h1>

    <!-- Select Class -->
    <label class="font-semibold">Select Class</label>
    <select v-model="className" class="border px-3 py-2 rounded w-full mb-4">
      <option value="">Choose Class</option>
      <option v-for="c in classes" :key="c">{{ c }}</option>
    </select>

    <!-- Select Month -->
    <label class="font-semibold">Select Month</label>
    <input
      type="month"
      v-model="month"
      class="border px-3 py-2 rounded w-full mb-4"
    />

    <button
      @click="loadReport"
      class="px-4 py-2 bg-blue-600 text-white rounded"
    >
      Load Report
    </button>

    <div v-if="loading" class="text-center p-4">Generating report...</div>

    <!-- Report Table -->
    <table v-if="!loading && Object.keys(report).length" class="mt-6 min-w-full border">
      <thead class="bg-gray-100">
        <tr>
          <th class="p-3 border">Student</th>
          <th class="p-3 border">Present</th>
          <th class="p-3 border">Absent</th>
          <th class="p-3 border">Late</th>
          <th class="p-3 border">Total Days</th>
        </tr>
      </thead>

      <tbody>
        <tr v-for="(records, student) in report" :key="student">
          <td class="p-3 border">{{ student }}</td>
          <td class="p-3 border">{{ count(records, 'Present') }}</td>
          <td class="p-3 border">{{ count(records, 'Absent') }}</td>
          <td class="p-3 border">{{ count(records, 'Late') }}</td>
          <td class="p-3 border">{{ records.length }}</td>
        </tr>
      </tbody>
    </table>

    <!-- Chart -->
    <canvas id="monthChart" class="mt-6"></canvas>
  </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import Chart from "chart.js/auto";

const className = ref("");
const month = ref("");
const report = ref({});
const loading = ref(false);

const classes = ["One", "Two", "Three", "Four", "Five"];

async function loadReport() {
  if (!className.value || !month.value) {
    alert("Select class and month");
    return;
  }

  loading.value = true;

  try {
    const res = await axios.post("/api/attendance/report/monthly", {
      class: className.value,
      month: month.value,
    });

    report.value = res.data;

  } catch (e) {
    console.error(e);
  } finally {
    loading.value = false;
    drawChart();
  }
}

function count(records, status) {
  return records.filter(r => r.status === status).length;
}

function drawChart() {
  const ctx = document.getElementById("monthChart");

  const totals = Object.keys(report.value).map(student => ({
    name: student,
    present: count(report.value[student], "Present")
  }));

  new Chart(ctx, {
    type: "bar",
    data: {
      labels: totals.map(t => t.name),
      datasets: [
        {
          label: "Present Days",
          data: totals.map(t => t.present)
        }
      ]
    }
  });
}
</script>
