<template>
  <footer class="py-4 border-top bg-white">
    <div class="container d-flex flex-column flex-lg-row align-items-center justify-content-between gap-3">
      <div class="text-muted">© {{ new Date().getFullYear() }} Smart Path</div>
      <div class="d-flex flex-wrap gap-3 fs-5">
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-facebook"></i></a>
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-x-twitter"></i></a>
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-instagram"></i></a>
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-linkedin"></i></a>
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-youtube"></i></a>
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-tiktok"></i></a>
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-github"></i></a>
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-discord"></i></a>
        <a href="#" class="text-decoration-none"><i class="fa-brands fa-telegram"></i></a>
      </div>
    </div>
  </footer>
</template>

<script>
export default { name: 'FooterLayout' }
</script>
