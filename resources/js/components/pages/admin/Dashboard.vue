<template>
  <div class="d-flex">
    <AdminSidebar :open="sidebarOpen" />
    <div class="flex-grow-1" :style="{ marginLeft: '260px' }">
      <AdminNavbar @toggleSidebar="sidebarOpen = !sidebarOpen" />
      <main class="container-fluid py-4">
        <h1 class="h4 fw-bold mb-3"><i class="fa-solid fa-gauge-high me-2"></i>Admin Dashboard</h1>
        <div v-if="loadError" class="alert alert-warning">Failed to load stats. Showing placeholders. {{ loadError }}</div>
        <div class="row g-3">
          <div class="col-12 col-md-6 col-xl-3" v-for="card in statCards" :key="card.title">
            <div class="card shadow-sm border-0 animate-float" style="animation-duration: 12s;">
              <div class="card-body d-flex align-items-center gap-3">
                <div class="display-6 text-primary"><i :class="card.icon"></i></div>
                <div>
                  <div class="small text-muted">{{ card.title }}</div>
                  <div class="h4 m-0">{{ card.value }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row g-3 mt-1">
          <div class="col-12 col-lg-7">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white fw-bold">
                Signups (last 14 days)
              </div>
              <div class="card-body">
                <div class="text-muted">Chart placeholder. We can plug chart.js here.</div>
                <div class="progress mt-3" style="height:6px;">
                  <div class="progress-bar" role="progressbar" :style="{ width: signupProgress+'%' }"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-lg-5">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white fw-bold">
                Recent Login Activity
              </div>
              <div class="list-group list-group-flush">
                <div class="list-group-item" v-for="log in lastLogins" :key="log.id">
                  <i :class="log.success ? 'fa-solid fa-check-circle text-success' : 'fa-solid fa-xmark-circle text-danger'"></i>
                  <span class="ms-2">{{ log.email || '—' }}</span>
                  <span class="ms-2 badge" :class="log.success ? 'bg-success' : 'bg-danger'">{{ log.success ? 'Success' : 'Fail' }}</span>
                  <span class="ms-2 text-muted small">{{ new Date(log.created_at).toLocaleString() }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import axios from 'axios'
import AdminNavbar from '../../layouts/AdminNavbar.vue'
import AdminSidebar from '../../layouts/AdminSidebar.vue'
export default {
  name: 'AdminDashboardPage',
  components: { AdminNavbar, AdminSidebar },
  data(){
    return { sidebarOpen: true, stats: null, lastLogins: [], signupProgress: 0, loadError: '' }
  },
  computed:{
    statCards(){
      return [
        { title: 'Total Users', value: this.stats?.total_users ?? '—', icon: 'fa-solid fa-users' },
        { title: 'Admins', value: this.stats?.admins ?? '—', icon: 'fa-solid fa-user-shield' },
        { title: 'New (7d)', value: this.stats?.new_last_7d ?? '—', icon: 'fa-solid fa-sparkles' },
        { title: 'Online', value: this.stats?.online ?? '—', icon: 'fa-solid fa-signal' },
      ]
    }
  },
  async created(){
    try{
      const { data } = await axios.get('/api/admin/stats')
      this.stats = data
      this.lastLogins = data.last_logins || []
      const total = Math.max(...(data.signups_daily?.map(s=>s.c) || [0]), 1)
      const last = (data.signups_daily?.slice(-1)[0]?.c) || 0
      this.signupProgress = Math.round((last/total)*100)
    }catch(err){
      console.error('Failed to load /api/admin/stats', err)
      this.loadError = err?.response?.data?.message || err?.message || 'Unknown error'
    }
  }
}
</script>
