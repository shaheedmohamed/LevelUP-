<template>
  <nav class="navbar navbar-expand-lg navbar-light bg-transparent border-0 sticky-top shadow-sm nav-glass" v-reveal>
    <div class="container">
      <RouterLink class="navbar-brand d-flex align-items-center fw-bold text-decoration-none" :to="{ name: 'home' }">
        <img src="/images/logo-smartpath.png" alt="Smart Path Logo" class="me-2 brand-logo" />
        <span>Smart Path</span>
      </RouterLink>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="mainNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
          <li class="nav-item"><RouterLink class="nav-link" :to="{ name: 'home' }"><i class="fa-solid fa-house me-1 d-lg-none"></i>Home</RouterLink></li>
          <li class="nav-item"><RouterLink class="nav-link" :to="{ name: 'features' }"><i class="fa-solid fa-table-cells-large me-1 d-lg-none"></i>Features</RouterLink></li>
          <li class="nav-item"><RouterLink class="nav-link" :to="{ name: 'about' }"><i class="fa-solid fa-lightbulb me-1 d-lg-none"></i>About</RouterLink></li>
          <li class="nav-item"><RouterLink class="nav-link" :to="{ name: 'contact' }"><i class="fa-solid fa-envelope me-1 d-lg-none"></i>Contact</RouterLink></li>
          <template v-if="!isAuthed">
            <li class="nav-item"><RouterLink class="nav-link" :to="{ name: 'login' }"><i class="fa-solid fa-right-to-bracket me-1 d-lg-none"></i>Login</RouterLink></li>
            <li class="nav-item"><RouterLink class="btn btn-sm btn-primary ms-lg-3" :to="{ name: 'register' }"><i class="fa-solid fa-user-plus me-1"></i>Register</RouterLink></li>
          </template>
          <template v-else>
            <li class="nav-item" v-if="isAdmin"><RouterLink class="btn btn-sm btn-warning ms-lg-3" :to="{ name: 'admin-dashboard' }"><i class="fa-solid fa-shield-halved me-1"></i>Admin</RouterLink></li>
            <li class="nav-item"><RouterLink class="btn btn-sm btn-outline-primary ms-lg-3" :to="{ name: 'dashboard' }"><i class="fa-solid fa-gauge-high me-1"></i>Dashboard</RouterLink></li>
            <li class="nav-item dropdown ms-lg-2">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-regular fa-user me-1"></i>{{ userName }}
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><button class="dropdown-item" @click="doLogout"><i class="fa-solid fa-right-from-bracket me-2"></i>Logout</button></li>
              </ul>
            </li>
          </template>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import auth from '../../store/auth'
export default {
  name: 'NavbarLayout',
  computed:{
    isAuthed(){ return auth.isAuthenticated() },
    userName(){ return auth.state.user?.name || 'Account' },
    isAdmin(){ return auth.state.user?.role === 'admin' }
  },
  methods:{
    async doLogout(){
      await auth.logout()
      this.$router.push({ name:'home' })
    }
  }
}
</script>
