<template>
  <div>
    <Navbar v-if="!isAdmin" />
    <router-view v-slot="{ Component }">
      <transition name="route-fade" mode="out-in">
        <component :is="Component" />
      </transition>
    </router-view>
  </div>
</template>

<script>
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from './components/layouts/Navbar.vue'

export default {
  name: 'AppRoot',
  components: { Navbar },
  setup(){
    const route = useRoute()
    const isAdmin = computed(() => route.matched.some(r => r.meta?.admin))
    return { isAdmin }
  }
}
</script>
