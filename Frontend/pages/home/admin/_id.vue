<template>
  <no-ssr>
    <div class="content">
      <div class="col-lg-12">
        <div class="row">
          WELCOME TO HOME ADMIN
        </div>
      </div>
    </div>
  </no-ssr>
</template>

<script>

export default {
  middleware: 'auth',
  layout: 'home_admin',
  
  head() {
    return {
      title: "Home Admin",
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