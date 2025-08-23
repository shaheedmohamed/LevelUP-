<template>
  <div class="py-5">
    <section class="container">
      <div class="row justify-content-center mb-4">
        <div class="col-lg-10">
          <div class="text-center" v-reveal>
            <span class="badge bg-gradient-primary text-white shadow-sm">Digitopia 2025</span>
            <h1 class="fw-bold mt-3">About Smart Path</h1>
            <p class="text-muted">Our mission is to empower every learner with an AI that teaches their way.</p>
          </div>
        </div>
      </div>

      <div class="row g-4">
        <div class="col-lg-6" v-reveal>
          <div class="p-4 rounded-4 bg-white shadow-sm h-100">
            <h5 class="fw-semibold mb-3"><i class="fa-solid fa-people-group me-2"></i>Our Story</h5>
            <p class="text-muted">We blend pedagogy with AI to personalize learning journeys at scale.</p>
          </div>
        </div>
        <div class="col-lg-6" v-reveal>
          <div class="p-4 rounded-4 bg-white shadow-sm h-100">
            <h5 class="fw-semibold mb-3"><i class="fa-solid fa-bullseye me-2"></i>Mission</h5>
            <p class="text-muted">Learning should be personal, adaptive, and supportive for students, teachers, and parents.</p>
          </div>
        </div>
      </div>

      <div class="row mt-5">
        <div class="col-12" v-reveal>
          <h4 class="fw-bold mb-3">Student Journey</h4>
          <div class="timeline">
            <div class="timeline-step" v-for="(t,k) in timeline" :key="k">
              <span class="dot"></span>
              <div class="ms-4">
                <strong><i :class="t.icon" class="me-2"></i>{{ t.title }}</strong>
                <div class="text-muted small">{{ t.text }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
    <Footer />
  </div>
</template>

<script>
import Footer from '../layouts/Footer.vue'
export default {
  name: 'AboutPage',
  components: { Footer },
  data(){
    return {
      timeline: [
        { icon:'fa-solid fa-book-open', title:'Choose subject', text:'Pick what to learn' },
        { icon:'fa-solid fa-bolt', title:'Quick test', text:'Gauge level' },
        { icon:'fa-solid fa-brain', title:'AI selects method', text:'Fit your style' },
        { icon:'fa-solid fa-bell', title:'Reminders', text:'Stay consistent' },
        { icon:'fa-solid fa-graduation-cap', title:'Lessons', text:'Daily delivery' },
      ]
    }
  }
}
</script>
