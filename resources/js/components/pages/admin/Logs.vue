<template>
  <div class="d-flex">
    <AdminSidebar :open="sidebarOpen" />
    <div class="flex-grow-1" :style="{ marginLeft: '260px' }">
      <AdminNavbar @toggleSidebar="sidebarOpen = !sidebarOpen" />
      <main class="container-fluid py-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white fw-bold">
            <i class="fa-solid fa-clipboard-list me-2"></i>Login Logs (last 50)
          </div>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Email</th>
                  <th>User</th>
                  <th>Result</th>
                  <th>IP</th>
                  <th>User Agent</th>
                  <th>Time</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="l in items" :key="l.id">
                  <td>{{ l.id }}</td>
                  <td>{{ l.email || '—' }}</td>
                  <td>{{ l.name || '—' }}</td>
                  <td>
                    <span class="badge" :class="l.success ? 'bg-success' : 'bg-danger'">{{ l.success ? 'Success' : 'Fail' }}</span>
                  </td>
                  <td>{{ l.ip || '—' }}</td>
                  <td class="text-truncate" style="max-width: 320px;" :title="l.user_agent">{{ l.user_agent }}</td>
                  <td>{{ new Date(l.created_at).toLocaleString() }}</td>
                </tr>
              </tbody>
            </table>
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
  name: 'AdminLogsPage',
  components: { AdminNavbar, AdminSidebar },
  data(){ return { sidebarOpen:true, items:[] } },
  async created(){
    const { data } = await axios.get('/api/admin/logs')
    this.items = data
  }
}
</script>
