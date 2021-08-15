<template>
  <div>
    HAY INI BAGIAN ITEM GROUP
  </div>
</template>
<script>

export default {
  middleware: 'auth',
  layout: "dashboard",


  head() {
    return {
      title: "Items Group Setting",
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