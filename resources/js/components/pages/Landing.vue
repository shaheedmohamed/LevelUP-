<template>
  <div>
    <section id="top" class="hero-animated-bg py-5 py-lg-6 position-relative">
      <div class="container py-5">
        <div class="row align-items-center gy-4">
          <div class="col-lg-6" v-reveal>
            <span class="badge bg-gradient-primary text-white mb-3 shadow-sm">Smart Path · Digitopia 2025</span>
            <img src="/images/logo-smartpath.png" alt="Smart Path" class="hero-logo mb-2" />
            <h1 class="display-5 fw-bold text-gradient">AI that teaches you your way</h1>
            <p class="lead text-muted mt-3">Personalized lessons, an always-on AI tutor, and smart reminders.</p>
            <div class="d-flex gap-3 mt-4">
              <RouterLink :to="primaryCta.to" class="btn btn-primary btn-lg px-4 btn-shine">
                <i class="fa-solid fa-user-plus me-2"></i>{{ primaryCta.label }}
              </RouterLink>
              <RouterLink :to="{ name: 'features' }" class="btn btn-outline-primary btn-lg px-4">
                <i class="fa-solid fa-circle-play me-2"></i>Learn More
              </RouterLink>
            </div>
          </div>
          <div class="col-lg-6" v-reveal>
            <div class="ratio ratio-16x9 rounded-4 shadow overflow-hidden bg-white" data-parallax data-speed="0.15">
              <img src="/images/hero2.jpg" alt="Smart Path Hero" class="w-100 h-100 animate-float" style="object-fit: cover;" />
            </div>
          </div>
        </div>
      </div>
      <!-- Decorative parallax blobs -->
      <div class="blob blob-primary animate-float" style="width:180px;height:180px; left:-40px; top:40px;" data-parallax data-speed="0.25"></div>
      <div class="blob blob-secondary animate-rotate-slow" style="width:220px;height:220px; right:-60px; top:120px;" data-parallax data-speed="0.18" data-rotate="1"></div>
      <div class="blob blob-accent animate-wobble" style="width:140px;height:140px; left:20%; bottom:-40px;" data-parallax data-speed="0.3"></div>
    </section>

    <!-- Icon strip -->
    <section class="py-4 bg-white border-top border-bottom">
      <div class="container">
        <div class="d-flex flex-wrap justify-content-center align-items-center gap-4 fs-3 text-primary" v-reveal>
          <i class="fa-solid fa-book-open icon-pulse"></i>
          <i class="fa-solid fa-brain"></i>
          <i class="fa-solid fa-robot"></i>
          <i class="fa-solid fa-chart-line"></i>
          <i class="fa-solid fa-bell"></i>
          <i class="fa-solid fa-graduation-cap"></i>
          <i class="fa-solid fa-language"></i>
          <i class="fa-solid fa-microchip"></i>
          <i class="fa-solid fa-lightbulb"></i>
          <i class="fa-solid fa-chalkboard-user"></i>
          <i class="fa-solid fa-trophy"></i>
          <i class="fa-solid fa-star"></i>
        </div>
      </div>
    </section>

    <section id="features" class="py-5">
      <div class="container">
        <div class="text-center mb-5" v-reveal>
          <h2 class="fw-bold">Built to learn smarter</h2>
          <p class="text-muted">Personalization, tutoring, reminders, and Q&A — all in one.</p>
        </div>
        <div class="row g-4">
          <div class="col-md-6 col-lg-3" v-for="(f,i) in features" :key="i" v-reveal>
            <div class="card border-0 shadow-sm h-100 card-hover">
              <div class="card-body p-4">
                <div class="display-6 text-primary mb-3"><i :class="f.icon"></i></div>
                <h5 class="fw-semibold">{{ f.title }}</h5>
                <p class="text-muted mb-0">{{ f.text }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section id="how" class="py-5 bg-light">
      <div class="container">
        <div class="text-center mb-4" v-reveal>
          <h2 class="fw-bold">How it works</h2>
        </div>
        <div class="row g-4 row-cols-1 row-cols-md-2 row-cols-lg-5">
          <div class="col" v-for="(s,j) in steps" :key="j" v-reveal>
            <div class="h-100 text-center p-4 bg-white rounded-4 shadow-sm card-hover">
              <div class="fs-1 text-primary mb-2"><i :class="s.icon"></i></div>
              <h6 class="fw-semibold mb-1">{{ s.title }}</h6>
              <p class="text-muted small mb-0">{{ s.text }}</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Extra section: Subjects grid based on education stage (visible when logged in) -->
    <section v-if="isLoggedIn && subjects.length" class="py-5">
      <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4" v-reveal>
          <h2 class="fw-bold mb-0">Subjects for {{ stage }}</h2>
          <small class="text-muted">Choose a subject to start learning</small>
        </div>
        <div class="row g-4 row-cols-1 row-cols-sm-2 row-cols-lg-3">
          <div class="col" v-for="(sub, idx) in subjects" :key="idx" v-reveal>
            <RouterLink :to="{ name: 'subject', params: { subject: sub.slug } }" class="text-decoration-none">
              <div class="card border-0 shadow-sm h-100 card-hover">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                  <div class="display-6 text-primary"><i :class="sub.icon"></i></div>
                  <div>
                    <h5 class="fw-semibold mb-1">{{ sub.name }}</h5>
                    <p class="text-muted small mb-0">Click to view {{ sub.name }} lessons</p>
                  </div>
                </div>
              </div>
            </RouterLink>
          </div>
        </div>
      </div>
    </section>

    <Footer />
  </div>
</template>

<script>
import Footer from '../layouts/Footer.vue'
import auth from '../../store/auth'
export default {
  name: 'LandingPage',
  components: { Footer },
  data(){
    return {
      features: [
        { icon:'fa-solid fa-wand-magic-sparkles', title:'Personalized lessons', text:'Tailored to your pace.' },
        { icon:'fa-solid fa-robot', title:'AI tutor assistance', text:'Ask and learn instantly.' },
        { icon:'fa-solid fa-bell', title:'Smart reminders', text:'Stay on track.' },
        { icon:'fa-solid fa-circle-question', title:'Interactive Q&A', text:'Practice with feedback.' },
      ],
      steps: [
        { icon:'fa-solid fa-book-open', title:'Choose subject', text:'Pick your topic.' },
        { icon:'fa-solid fa-bolt', title:'Quick test', text:'Assess your level.' },
        { icon:'fa-solid fa-brain', title:'AI selects method', text:'Match your style.' },
        { icon:'fa-solid fa-calendar-check', title:'Reminders', text:'Plan to succeed.' },
        { icon:'fa-solid fa-graduation-cap', title:'Lessons', text:'Daily micro-learning.' },
      ]
    }
  },
  computed: {
    isLoggedIn(){ return auth.isAuthenticated() },
    stage(){ return auth.state.user?.education_stage || '' },
    primaryCta(){
      return this.isLoggedIn
        ? { to: { name:'subjects' }, label: 'Browse Subjects' }
        : { to: { name:'register' }, label: 'Sign Up' }
    },
    subjects(){
      const map = {
        Primary: [
          { name: 'Math', slug: 'math', icon: 'fa-solid fa-square-root-variable' },
          { name: 'Arabic', slug: 'arabic', icon: 'fa-solid fa-language' },
          { name: 'Science', slug: 'science', icon: 'fa-solid fa-flask' },
          { name: 'English', slug: 'english', icon: 'fa-solid fa-book' },
        ],
        Preparatory: [
          { name: 'Math', slug: 'math', icon: 'fa-solid fa-square-root-variable' },
          { name: 'Science', slug: 'science', icon: 'fa-solid fa-flask' },
          { name: 'History', slug: 'history', icon: 'fa-solid fa-landmark' },
          { name: 'English', slug: 'english', icon: 'fa-solid fa-book' },
          { name: 'Arabic', slug: 'arabic', icon: 'fa-solid fa-language' },
        ],
        Secondary: [
          { name: 'Math', slug: 'math', icon: 'fa-solid fa-square-root-variable' },
          { name: 'Physics', slug: 'physics', icon: 'fa-solid fa-atom' },
          { name: 'Chemistry', slug: 'chemistry', icon: 'fa-solid fa-vial' },
          { name: 'Biology', slug: 'biology', icon: 'fa-solid fa-dna' },
          { name: 'Arabic', slug: 'arabic', icon: 'fa-solid fa-language' },
          { name: 'English', slug: 'english', icon: 'fa-solid fa-book' },
        ],
      }
      return map[this.stage] || []
    }
  }
}
</script>
