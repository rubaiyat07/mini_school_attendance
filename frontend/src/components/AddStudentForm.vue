<template>
  <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full" @click="closeModal">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white" @click.stop>
      <div class="mt-3">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Add New Student</h3>
        <form @submit.prevent="submitForm">
          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">
              Name
            </label>
            <input
              v-model="form.name"
              type="text"
              id="name"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              required
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="student_id">
              Student ID
            </label>
            <input
              v-model="form.student_id"
              type="text"
              id="student_id"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              required
            />
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="class">
              Class
            </label>
            <select
              v-model="form.class"
              id="class"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              required
              @change="onClassChange"
            >
              <option value="">Select Class</option>
              <option v-for="cls in classes" :key="cls" :value="cls">{{ cls }}</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="section">
              Section
            </label>
            <select
              v-model="form.section"
              id="section"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
              required
            >
              <option value="">Select Section</option>
              <option v-for="sec in sections" :key="sec" :value="sec">{{ sec }}</option>
            </select>
          </div>

          <div class="mb-4">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="photo">
              Photo URL (Optional)
            </label>
            <input
              v-model="form.photo"
              type="url"
              id="photo"
              class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            />
          </div>

          <div class="flex items-center justify-between">
            <button
              type="submit"
              :disabled="loading"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline disabled:opacity-50"
            >
              {{ loading ? 'Adding...' : 'Add Student' }}
            </button>
            <button
              type="button"
              @click="closeModal"
              class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
            >
              Cancel
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { createStudent, fetchClasses, fetchSections } from '@/api/studentApi'

const props = defineProps({
  showModal: Boolean
})

const emit = defineEmits(['close', 'student-added'])

const form = ref({
  name: '',
  student_id: '',
  class: '',
  section: '',
  photo: ''
})

const classes = ref([])
const sections = ref([]) // Fetch from API
const loading = ref(false)

onMounted(async () => {
  try {
    const classesResponse = await fetchClasses()
    classes.value = classesResponse.data.classes
  } catch (error) {
    console.error('Error fetching classes:', error)
  }
})

const onClassChange = async () => {
  if (form.value.class) {
    try {
      const sectionsResponse = await fetchSections(form.value.class)
      sections.value = sectionsResponse.data.sections
    } catch (error) {
      console.error('Error fetching sections:', error)
      sections.value = []
    }
  } else {
    sections.value = []
  }
}

const submitForm = async () => {
  loading.value = true
  try {
    await createStudent(form.value)
    emit('student-added')
    closeModal()
  } catch (error) {
    console.error('Error adding student:', error)
    alert('Error adding student. Please try again.')
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  form.value = {
    name: '',
    student_id: '',
    class: '',
    section: '',
    photo: ''
  }
  emit('close')
}
</script>
