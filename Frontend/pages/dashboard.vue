<template>
  <no-ssr>
    <div class="content">
      <div class="col-lg-12">
        <div class="row">
          WELCOME TO SIMPLE RESTO
        </div>
      </div>
    </div>
  </no-ssr>
</template>

<script>

export default {
  middleware: 'auth',
  layout: 'dashboard',
  

  head() {
    return {
      title: "Dashboard",
      link: [
        { rel: 'stylesheet', type: 'text/css', href: '/fonts/roboto.css' },
        { rel: 'stylesheet', type: 'text/css', href: '/css/vue-multiselect.min.css' }
      ]
    };
  },

  data() {
    return {
    }
  },

  created(){
  },

  methods: {
    
  }
}

</script>
<style>
  .content {
    font-family: 'Roboto';
  }
</style>