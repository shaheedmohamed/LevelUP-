<template>
  <div class="min-vh-100 bg-light">
    <section class="container py-5">
      <div class="bg-white rounded-3 shadow-sm p-4 p-lg-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center gap-2">
            <h1 class="h4 fw-bold mb-0">Not sure where to start? — AI Study Advisor</h1>
            <span class="badge bg-primary-subtle text-primary">Shaheed</span>
          </div>
          <RouterLink :to="{ name: 'home' }" class="btn btn-outline-secondary">Back to Home</RouterLink>
        </div>

        <p class="text-muted">Fill in the details and the AI will suggest a personalized study plan tailored to the Egyptian curriculum.</p>

        <form @submit.prevent="submit" class="row g-3">
          <!-- Stage -->
          <div class="col-md-4">
            <label class="form-label">Education stage</label>
            <select v-model="form.stage" class="form-select" required>
              <option value="">Select</option>
              <option>Primary</option>
              <option>Preparatory</option>
              <option>Secondary</option>
            </select>
          </div>

          <!-- Grade -->
          <div class="col-md-4">
            <label class="form-label">Grade</label>
            <input v-model="form.grade" type="text" class="form-control" placeholder="e.g., Grade 5 / Prep 2 / Sec 3" required />
          </div>

          <!-- Time -->
          <div class="col-md-4">
            <label class="form-label">Available time</label>
            <input v-model="form.time" type="text" class="form-control" placeholder="e.g., 2 hours daily or 10 hours weekly" required />
          </div>

          <!-- Subjects -->
          <div class="col-12">
            <label class="form-label">Subjects</label>
            <div class="d-flex flex-wrap gap-2">
              <label v-for="s in allSubjects" :key="s" class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" :value="s" v-model="form.subjects" />
                <span class="form-check-label">{{ s }}</span>
              </label>
            </div>
            <small class="text-muted d-block">Select one or more subjects.</small>
          </div>

          <!-- Style -->
          <div class="col-md-6">
            <label class="form-label">Preferred study style</label>
            <select v-model="form.style" class="form-select" required>
              <option value="">Select</option>
              <option>Videos</option>
              <option>Practice exercises</option>
              <option>Written summaries</option>
              <option>Mix</option>
            </select>
          </div>

          <!-- Goal -->
          <div class="col-md-6">
            <label class="form-label">Goal</label>
            <input v-model="form.goal" type="text" class="form-control" placeholder="e.g., improve grades this month / prepare for midterms / high final score" required />
          </div>

          <!-- Language -->
          <div class="col-md-3">
            <label class="form-label">Plan language</label>
            <select v-model="form.language" class="form-select">
              <option value="en">English</option>
              <option value="ar">Arabic</option>
            </select>
          </div>

          <div class="col-12 d-flex align-items-center gap-2">
            <button class="btn btn-primary" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              Generate AI Plan
            </button>
            <span v-if="error" class="text-danger small">{{ error }}</span>
          </div>
        </form>

        <!-- Result -->
        <div v-if="plan" class="mt-4">
          <div class="d-flex justify-content-between align-items-center">
            <h5 class="fw-semibold mb-2">Suggested Plan</h5>
            <div class="d-flex gap-2">
              <button class="btn btn-sm btn-outline-secondary" @click="copyPlan">Copy</button>
              <button class="btn btn-sm btn-outline-secondary" @click="downloadPlan">Download .md</button>
            </div>
          </div>
          <div class="card border-0 shadow-sm">
            <div class="card-body">
              <pre class="mb-0" style="white-space: pre-wrap">{{ plan }}</pre>
            </div>
          </div>
        </div>

        <!-- Dev notes -->
        <!--
          Setup:
            - Backend route: POST /api/ai/study-plan
            - Requires .env: OPENROUTER_API_KEY=sk-or-... (optional: OPENROUTER_BASE_URL, OPENROUTER_MODEL)
            - See controller: app/Http/Controllers/AIController.php
        -->
      </div>
    </section>
  </div>
</template>

<script>
import axios from 'axios'
export default {
  name: 'AdvisorPage',
  data(){
    return {
      form: {
        stage: '',
        grade: '',
        subjects: [],
        time: '',
        style: '',
        goal: '',
        language: 'en'
      },
      allSubjects: ['Arabic','English','Math','Science','Social Studies','Physics','Chemistry','Biology','Geography','History'],
      loading: false,
      error: '',
      errorDetails: null,
      plan: ''
    }
  },
  methods: {
    async submit(){
      this.error = ''
      this.plan = ''
      this.errorDetails = null
      // minimal validation
      if (!this.form.stage || !this.form.grade || !this.form.time || !this.form.style || !this.form.goal || this.form.subjects.length===0) {
        this.error = 'Please fill all required fields and select at least one subject.'
        return
        }
      this.loading = true
      try{
        const { data } = await axios.post('/api/ai/study-plan', this.form)
        this.plan = data.plan || ''
      }catch(e){
        const d = e?.response?.data
        this.error = d?.message || 'Failed to generate the plan. Please check the server API key settings.'
        this.errorDetails = d ? { status: d.status, details: d.details, hint: d.hint } : null
      }finally{
        this.loading = false
      }
    },
    copyPlan(){
      if (!this.plan) return
      navigator.clipboard?.writeText(this.plan)
    },
    downloadPlan(){
      const blob = new Blob([this.plan], { type: 'text/markdown;charset=utf-8' })
      const url = URL.createObjectURL(blob)
      const a = document.createElement('a')
      a.href = url
      a.download = 'study-plan.md'
      a.click()
      URL.revokeObjectURL(url)
    }
  }
}
</script>
