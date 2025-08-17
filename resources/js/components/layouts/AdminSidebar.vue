<template>
  <aside :class="['admin-sidebar bg-dark text-white vh-100 position-fixed top-0 start-0', { 'd-none': !open, 'd-lg-block': true }]" style="width:260px;">
    <div class="p-3 border-bottom border-secondary d-flex align-items-center gap-2">
      <i class="fa-solid fa-route text-warning"></i>
      <strong>Smart Path Admin</strong>
    </div>
    <nav class="p-3">
      <ul class="nav nav-pills flex-column gap-2">
        <li class="nav-item">
          <RouterLink class="nav-link text-start" active-class="active" :to="{ name:'admin-dashboard' }">
            <i class="fa-solid fa-gauge-high me-2"></i> Dashboard
          </RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink class="nav-link text-start" active-class="active" :to="{ name:'admin-users' }">
            <i class="fa-solid fa-users me-2"></i> Users
          </RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink class="nav-link text-start" active-class="active" :to="{ name:'admin-logs' }">
            <i class="fa-solid fa-clipboard-list me-2"></i> Logs
          </RouterLink>
        </li>
        <li class="nav-item">
          <RouterLink class="nav-link text-start" active-class="active" :to="{ name:'admin-settings' }">
            <i class="fa-solid fa-gear me-2"></i> Settings
          </RouterLink>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script>
export default {
  name: 'AdminSidebar',
  props: { open: { type: Boolean, default: true } }
}
</script>

<style scoped>
.nav-link { color: #cfd8dc; }
.nav-link.active, .nav-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
</style>
