<template>
  <div class="d-flex">
    <AdminSidebar :open="sidebarOpen" />
    <div class="flex-grow-1" :style="{ marginLeft: '260px' }">
      <AdminNavbar @toggleSidebar="sidebarOpen = !sidebarOpen" />
      <main class="container-fluid py-4">
        <div class="card shadow-sm border-0">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong><i class="fa-solid fa-users me-2"></i>Users</strong>
            <div class="input-group" style="max-width: 280px;">
              <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
              <input v-model="q" type="text" class="form-control" placeholder="Search name/email" />
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-middle mb-0">
              <thead class="table-light">
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Email</th>
                  <th>Phone</th>
                  <th>Role</th>
                  <th>Created</th>
                  <th class="text-end">Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="u in filtered" :key="u.id">
                  <td>{{ u.id }}</td>
                  <td>{{ u.name }}</td>
                  <td>{{ u.email }}</td>
                  <td>{{ u.phone || '—' }}</td>
                  <td>
                    <select class="form-select form-select-sm w-auto" v-model="u.role" @change="saveRole(u)" :disabled="savingId===u.id">
                      <option value="user">user</option>
                      <option value="admin">admin</option>
                    </select>
                  </td>
                  <td>{{ new Date(u.created_at).toLocaleDateString() }}</td>
                  <td class="text-end">
                    <button class="btn btn-sm btn-outline-danger" @click="removeUser(u)" :disabled="savingId===u.id">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer bg-white d-flex justify-content-between align-items-center">
            <div class="small text-muted">Page {{ page }} / {{ lastPage }}</div>
            <div class="btn-group">
              <button class="btn btn-sm btn-outline-secondary" :disabled="page<=1" @click="go(page-1)">Prev</button>
              <button class="btn btn-sm btn-outline-secondary" :disabled="page>=lastPage" @click="go(page+1)">Next</button>
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
  name: 'AdminUsersPage',
  components: { AdminNavbar, AdminSidebar },
  data(){
    return { sidebarOpen:true, items:[], page:1, lastPage:1, q:'', savingId:null }
  },
  computed:{
    filtered(){
      const q = this.q.trim().toLowerCase()
      if(!q) return this.items
      return this.items.filter(u => (u.name+u.email).toLowerCase().includes(q))
    }
  },
  methods:{
    async load(p=1){
      const { data } = await axios.get('/api/admin/users', { params:{ page:p } })
      this.items = data.data
      this.page = data.current_page
      this.lastPage = data.last_page
    },
    async go(p){ await this.load(p) },
    async saveRole(u){
      this.savingId = u.id
      try{
        await axios.patch(`/api/admin/users/${u.id}/role`, { role:u.role })
      } finally { this.savingId = null }
    },
    async removeUser(u){
      if(!confirm(`Delete ${u.name}?`)) return
      this.savingId = u.id
      try{
        await axios.delete(`/api/admin/users/${u.id}`)
        this.items = this.items.filter(x => x.id !== u.id)
      } finally { this.savingId = null }
    }
  },
  async created(){ await this.load(1) }
}
</script>
