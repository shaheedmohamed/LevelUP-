import { createRouter, createWebHistory } from 'vue-router'
import { h } from 'vue'
import auth from '../store/auth'

// Pages
import LandingPage from '../components/pages/Landing.vue'
import FeaturesPage from '../components/pages/Features.vue'
import AboutPage from '../components/pages/About.vue'
import ContactPage from '../components/pages/Contact.vue'
import LoginPage from '../components/pages/Login.vue'
import RegisterPage from '../components/pages/Register.vue'
import DashboardPage from '../components/pages/Dashboard.vue'
import AdminDashboard from '../components/pages/admin/Dashboard.vue'
import AdminUsers from '../components/pages/admin/Users.vue'
import AdminLogs from '../components/pages/admin/Logs.vue'
import AdminSettings from '../components/pages/admin/Settings.vue'

export const routes = [
  { path: '/', name: 'home', component: LandingPage },
  { path: '/features', name: 'features', component: FeaturesPage },
  { path: '/about', name: 'about', component: AboutPage },
  { path: '/contact', name: 'contact', component: ContactPage },
  { path: '/login', name: 'login', component: LoginPage, meta: { guestOnly: true } },
  { path: '/register', name: 'register', component: RegisterPage, meta: { guestOnly: true } },
  { path: '/dashboard', name: 'dashboard', component: DashboardPage, meta: { requiresAuth: true } },
  {
    path: '/admin',
    component: { render: () => h('router-view') },
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      { path: 'dashboard', name: 'admin-dashboard', component: AdminDashboard },
      { path: 'users', name: 'admin-users', component: AdminUsers },
      { path: 'logs', name: 'admin-logs', component: AdminLogs },
      { path: 'settings', name: 'admin-settings', component: AdminSettings },
    ]
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() { return { top: 0, behavior: 'smooth' } }
})

// Load state from storage when router initializes
auth.loadFromLocalStorage()

router.beforeEach((to) => {
  if (to.meta?.requiresAuth && !auth.isAuthenticated()) {
    return { name: 'login', query: { redirect: to.fullPath } }
  }
  // Enforce admin-only routes
  if (to.meta?.requiresAdmin) {
    const role = auth.state.user?.role
    if (role !== 'admin') return { name: 'dashboard' }
  }
  // Guests cannot access login/register; redirect based on role
  if (to.meta?.guestOnly && auth.isAuthenticated()) {
    const role = auth.state.user?.role
    return role === 'admin' ? { name: 'admin-dashboard' } : { name: 'dashboard' }
  }
})

export default router
