<template>
  <div class="dashboard-page">
    <AppSidebarCustomer />
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
import AppSidebarCustomer from '~/components/AppSidebarCustomer.vue'
import AppFooter from '~/components/AppFooter.vue'
import AppHeader from '~/components/AppHeaderCustomer.vue'
export default {
  components: {
    AppSidebarCustomer,AppFooter,AppHeader
  }
}
</script>