<template>
  <div class="py-5">
    <section id="register" class="container" v-reveal>
      <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
          <div class="card border-0 shadow-sm">
            <div class="card-body p-4 p-lg-5">
              <h1 class="h3 fw-bold mb-3"><i class="fa-solid fa-user-plus me-2"></i>Create an account</h1>
              <div v-if="error" class="alert alert-danger" role="alert">{{ error }}</div>
              <div v-if="success" class="alert alert-success" role="alert">{{ success }}</div>
              <form @submit.prevent="submit">
                <div class="mb-3">
                  <label class="form-label">I am registering as</label>
                  <div class="d-flex gap-3">
                    <label class="form-check">
                      <input class="form-check-input" type="radio" value="user" v-model="form.role"> Student
                    </label>
                    <label class="form-check">
                      <input class="form-check-input" type="radio" value="parent" v-model="form.role"> Parent / Guardian
                    </label>
                  </div>
                </div>
                <div class="mb-3">
                  <label class="form-label">Name</label>
                  <input v-model="form.name" type="text" class="form-control form-control-lg" placeholder="Your name" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input v-model="form.email" type="email" class="form-control form-control-lg" placeholder="you@example.com" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Phone (optional)</label>
                  <input v-model="form.phone" type="text" class="form-control form-control-lg" placeholder="(+20) 1X XXX XXXX">
                </div>
                <div class="mb-3" v-if="form.role==='user'">
                  <label class="form-label">Age</label>
                  <input v-model.number="form.age" type="number" min="5" max="100" class="form-control form-control-lg" placeholder="Your age">
                </div>
                <div class="mb-3" v-if="form.role==='user'">
                  <label class="form-label">Education Stage</label>
                  <select v-model="form.education_stage" class="form-select form-select-lg">
                    <option value="" disabled>Select your stage</option>
                    <option value="Primary">Primary</option>
                    <option value="Preparatory">Preparatory</option>
                    <option value="Secondary">Secondary</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Password</label>
                  <input v-model="form.password" type="password" class="form-control form-control-lg" placeholder="••••••••" required>
                </div>
                <div class="mb-4">
                  <label class="form-label">Confirm Password</label>
                  <input v-model="form.password_confirmation" type="password" class="form-control form-control-lg" placeholder="••••••••" required>
                </div>
                <button class="btn btn-primary btn-lg w-100 btn-shine" type="submit" :disabled="loading">
                  <span v-if="loading" class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                  <i class="fa-solid fa-user-check me-2" v-if="!loading"></i>
                  {{ loading ? 'Creating account...' : 'Sign up' }}
                </button>
              </form>
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
import axios from 'axios'
import auth from '../../store/auth'
export default {
  name: 'RegisterPage',
  components: { Footer },
  data(){
    return { form: { role:'user', name:'', email:'', phone:'', age:null, education_stage:'', password:'', password_confirmation:'' }, loading:false, error:'', success:'' }
  },
  methods:{
    async submit(){
      this.error=''; this.success='';
      if(this.form.password !== this.form.password_confirmation){ this.error='Passwords do not match'; return }
      this.loading = true
      try{
        const { data } = await axios.post('/api/register', this.form)
        auth.setToken(data.token)
        auth.setUser(data.user)
        this.success = 'Account created successfully'
        const dest = (data.user?.role === 'parent') ? { name:'parent-dashboard' } : { name:'dashboard' }
        setTimeout(()=> this.$router.push(dest), 400)
      }catch(err){
        this.error = err?.response?.data?.message
          || Object.values(err?.response?.data?.errors || {})[0]?.[0]
          || 'Registration failed'
      }finally{ this.loading = false }
    }
  }
}
</script>
