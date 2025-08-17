<template>
  <div class="d-flex">
    <AdminSidebar :open="sidebarOpen" />
    <div class="flex-grow-1" :style="{ marginLeft: '260px' }">
      <AdminNavbar @toggleSidebar="sidebarOpen = !sidebarOpen" />
      <main class="container-fluid py-4">
        <div class="row g-3">
          <div class="col-12 col-lg-8">
            <div class="card shadow-sm border-0">
              <div class="card-header bg-white fw-bold"><i class="fa-solid fa-gear me-2"></i>Site Settings</div>
              <div class="card-body">
                <div v-if="message" class="alert alert-success">{{ message }}</div>
                <div class="mb-3">
                  <label class="form-label">Site Slogan</label>
                  <input v-model="form.site_slogan" class="form-control" placeholder="Great learning, your way" />
                </div>
                <div class="mb-3">
                  <label class="form-label">Site Description</label>
                  <textarea v-model="form.site_description" rows="4" class="form-control" placeholder="Description..."></textarea>
                </div>
                <button class="btn btn-primary" :disabled="saving" @click="save">
                  <span v-if="saving" class="spinner-border spinner-border-sm me-2"></span>
                  <i v-else class="fa-solid fa-floppy-disk me-2"></i>
                  Save Changes
                </button>
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
  name: 'AdminSettingsPage',
  components: { AdminNavbar, AdminSidebar },
  data(){ return { sidebarOpen:true, form:{ site_slogan:'', site_description:'' }, saving:false, message:'' } },
  methods:{
    async load(){
      const { data } = await axios.get('/api/admin/settings')
      this.form.site_slogan = data.site_slogan || ''
      this.form.site_description = data.site_description || ''
    },
    async save(){
      this.saving = true; this.message = ''
      try{
        await axios.post('/api/admin/settings', this.form)
        this.message = 'Saved successfully'
      } finally { this.saving = false }
    }
  },
  async created(){ await this.load() }
}
</script>
