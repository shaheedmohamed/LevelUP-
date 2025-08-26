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
import AdminLayout from '../components/layouts/AdminLayout.vue'
import AdminUsers from '../components/pages/admin/Users.vue'
import AdminLogs from '../components/pages/admin/Logs.vue'
import AdminSettings from '../components/pages/admin/Settings.vue'
import SubjectPage from '../components/pages/Subject.vue'
import AdminSubjects from '../components/pages/admin/Subjects.vue'
import AdminBooks from '../components/pages/admin/Books.vue'
import ProfilePage from '../components/pages/Profile.vue'
import AdminUserProfile from '../components/pages/admin/UserProfile.vue'
import SubjectsPage from '../components/pages/Subjects.vue'
import AdvisorPage from '../components/pages/Advisor.vue'
import ChatPage from '../components/pages/Chat.vue'

export const routes = [
  { path: '/', name: 'home', component: LandingPage },
  { path: '/features', name: 'features', component: FeaturesPage },
  { path: '/about', name: 'about', component: AboutPage },
  { path: '/contact', name: 'contact', component: ContactPage },
  { path: '/login', name: 'login', component: LoginPage, meta: { guestOnly: true } },
  { path: '/register', name: 'register', component: RegisterPage, meta: { guestOnly: true } },
  { path: '/dashboard', name: 'dashboard', component: DashboardPage, meta: { requiresAuth: true } },
  { path: '/profile', name: 'profile', component: ProfilePage, meta: { requiresAuth: true } },
  { path: '/subjects', name: 'subjects', component: SubjectsPage },
  { path: '/subjects/:subject', name: 'subject', component: SubjectPage, props: true },
  { path: '/advisor', name: 'advisor', component: AdvisorPage },
  { path: '/chat', name: 'chat', component: ChatPage },
  // Admin area under shared layout
  {
    path: '/admin',
    component: AdminLayout,
    meta: { requiresAuth: true, requiresAdmin: true, admin: true },
    children: [
      { path: 'dashboard', name: 'admin-dashboard', component: AdminDashboard },
      { path: 'users', name: 'admin-users', component: AdminUsers },
      { path: 'users/:id/profile', name: 'admin-user-profile', component: AdminUserProfile, props: true },
      { path: 'logs', name: 'admin-logs', component: AdminLogs },
      { path: 'settings', name: 'admin-settings', component: AdminSettings },
      { path: 'subjects', name: 'admin-subjects', component: AdminSubjects },
      { path: 'books', name: 'admin-books', component: AdminBooks },
    ]
  },
  { path: '/:pathMatch(.*)*', redirect: { name: 'home' } },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior() { return { top: 0, behavior: 'smooth' } }
})

// Load state from storage when router initializes
auth.loadFromLocalStorage()

router.beforeEach(async (to) => {
  if (to.meta?.requiresAuth && !auth.isAuthenticated()) {
    return { name: 'login', query: { redirect: to.fullPath } }
  }
  // Guests cannot access login/register; redirect based on role
  if (to.meta?.guestOnly && auth.isAuthenticated()) {
    return { name: 'dashboard' }
  }

  // Admin role guard
  if (to.meta?.requiresAdmin) {
    // Ensure we have the latest user profile
    if (!auth.state.user && auth.isAuthenticated()) {
      await auth.fetchUser()
    }
    const role = auth.state.user?.role
    if (role !== 'admin') {
      return { name: 'dashboard', query: { noadmin: '1' } }
    }
  }
})

export default router
