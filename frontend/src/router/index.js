import { createRouter, createWebHashHistory } from 'vue-router'
import LoginPage from '../pages/LoginPage.vue'
import StudentPage from '../pages/StudentPage.vue'
import AttendancePage from '../pages/AttendancePage.vue'
import DashboardPage from '../pages/DashboardPage.vue'
import MonthlyReport from '../pages/MonthlyReport.vue'

const routes = [
  { path: '/', redirect: '/login' },               // Redirect root to /login
  { path: '/login', component: LoginPage, meta: { title: 'Login' } },       // Login page
  { path: '/dashboard', component: DashboardPage, meta: { requiresAuth: true, title: 'Dashboard' } },
  { path: '/students', component: StudentPage, meta: { requiresAuth: true, title: 'Students' } },
  { path: '/attendance', component: AttendancePage, meta: { requiresAuth: true, title: 'Take Attendance' } },
  { path: '/monthly-report', component: MonthlyReport, meta: { requiresAuth: true, title: 'Monthly Report' } },
  { path: '/:pathMatch(.*)*', redirect: '/login' } // catch-all
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

// Route guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  if (to.meta.requiresAuth && !token) next('/login')
  else next()
})

export default router
