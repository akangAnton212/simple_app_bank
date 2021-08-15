<template>
  <div>
    HAY INI BAGIAN ITEMS
  </div>
</template>
<script>

export default {
  middleware: 'auth',
  layout: "dashboard",


  head() {
    return {
      title: "Items Setting",
      link: [
        { rel: 'stylesheet', type: 'text/css', href: '/css/vue-multiselect.min.css' }
      ]
    };
  },

  data() {
    return {
     
    };
  },

  created(){
   
  },
  
  watch:{
   
  },

  computed: {
    
  },

  methods: {
    
  }
};
</script>