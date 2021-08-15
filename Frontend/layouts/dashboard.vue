<template>
  <div class="dashboard-page">
    <AppSidebar />
    <section class="wrapper scrollable">
      <AppHeader />
      <div class="main-grid">
        <nuxt />
      </div>
      <!-- <AppFooter /> -->
    </section>
  </div>
</template>
<script>
import AppSidebar from '~/components/AppSidebar.vue'
import AppFooter from '~/components/AppFooter.vue'
import AppHeader from '~/components/AppHeader.vue'
export default {
  components: {
    AppSidebar,AppFooter,AppHeader
  }
}
</script>