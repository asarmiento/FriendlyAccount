<template>
  <div class="login-view">
    <Login />
  </div>
</template>

<script>
import Login from '@/components/Auth/Login.vue'

export default {
  name: 'LoginView',
  components: {
    Login
  }
}
</script>

<style scoped>
.login-view {
  min-height: 100vh;
}
</style> 