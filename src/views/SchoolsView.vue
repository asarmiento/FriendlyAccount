<template>
  <div class="schools-view">
    <SchoolsIndex />
  </div>
</template>

<script>
import SchoolsIndex from '@/components/Schools/Index.vue'

export default {
  name: 'SchoolsView',
  components: {
    SchoolsIndex
  }
}
</script>

<style scoped>
.schools-view {
  min-height: 100vh;
  background-color: #f8f9fa;
}
</style> 