import axios from 'axios'

// Fetch all students with optional pagination & class filter
export function fetchStudents(params = {}) {
  return axios.get('/students', { params })
}

// Fetch a single student by ID
export function fetchStudent(id) {
  return axios.get(`/students/${id}`)
}

// Create a new student
export function createStudent(data) {
  return axios.post('/students', data)
}

// Update a student
export function updateStudent(id, data) {
  return axios.put(`/students/${id}`, data)
}

// Delete a student
export function deleteStudent(id) {
  return axios.delete(`/students/${id}`)
}

// Fetch unique classes
export function fetchClasses() {
  return axios.get('/students/classes')
}

// Fetch sections for a class
export function fetchSections(className) {
  return axios.get('/students/sections', { params: { class: className } })
}
