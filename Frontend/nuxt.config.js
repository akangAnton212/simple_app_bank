require('dotenv').config()
module.exports = {
  mode: 'universal',
  /*
  ** Headers of the page
  */
  head: {
    title: process.env.npm_package_name || 'SIMPLE BANK',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: process.env.npm_package_description || '' }
    ],
    script: [
      //Codebase Core JS
      { src: '/vendor/jquery/jquery-3.2.1.min.js', body: true },
      { src: '/vendor/bootstrap/js/popper.js', body: true },
      { src: '/vendor/bootstrap/js/bootstrap.min.js', body: true },
      { src: '/vendor/select2/select2.min.js', body: true },
      { src: '/js/main.js', body: true },
      { src: '/js/modernizr.js', body: true },
      { src: '/js/jquery.cookie.js', body: true },
      // { src: '/js/screenfull.js', body: true },
      { src: '/js/bootstrap.js', body: true },
      { src: '/js/proton.js', body: true },
      { src: '/js/skycons.js', body: true },

      { src: 'https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js', body: true},
      { src: 'https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.2.4/jspdf.plugin.autotable.min.js', body: true},
      { src: '/notification/notifications.js', body:true },

    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      { rel: 'stylesheet', type: 'text/css', href: '/vendor/bootstrap/css/bootstrap.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/vendor/animate/animate.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/vendor/css-hamburgers/hamburgers.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/vendor/animsition/css/animsition.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/vendor/select2/select2.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/util.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/main.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/style.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/fonts/font-awesome-4.7.0/css/font-awesome.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/fonts/iconic/css/material-design-iconic-font.min.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/bootstrap.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/css/font.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/fonts/roboto.css' },
      { rel: 'stylesheet', type: 'text/css', href: '/notification/notifications.css' }
    ]
  },
  /*
  ** Customize the progress-bar color
  */
  loading: { color: '#fff' },
  /*
  ** Global CSS
  */
  css: [
  ],
  vendor: [
    'vue-swal',
    'aframe',
  ],
  /*
  ** Plugins to load before mounting the App
  */
  plugins: [
    { src: '~plugins/axios.js', },
    { src: '~plugins/vue-swal.js' },
    { src: '~plugins/datepicker.js', ssr:false },
    { src: '~plugins/vee-validate.js', ssr: true },
    { src: '~plugins/vue-cookies.js', ssr: true },
    { src: '~plugins/simple-vue-validator.js'},
  ],
  /*
  ** Nuxt.js dev-modules
  */
  devModules: [
  ],
  /*
  ** Nuxt.js modules
  */
  modules: [
    // Doc: https://bootstrap-vue.js.org/docs/
    'bootstrap-vue/nuxt',
    '@nuxtjs/axios',
    '@nuxtjs/moment',
    '@nuxtjs/dotenv',
    '@nuxtjs/font-awesome',
  ],
  /*
  ** Build configuration
  */
  axios: {
    // See https://github.com/nuxt-community/axios-module#options
    browserBaseURL: process.env.API_URL,
    baseURL: process.env.API_URL || 'http://localhost:8080/'
  }, 
  env: {
    api: process.env.API_URL
  }, 
  build: {
    /*
    ** You can extend webpack config here
    */
    extend(config, ctx) {}
  }
}
