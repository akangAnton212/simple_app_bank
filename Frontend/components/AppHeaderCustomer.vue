<template>
  <div>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto" />
        <form class="form-inline my-2 my-lg-0">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              {{ user }}
            </a>
            <div 
              class="dropdown-menu" 
              aria-labelledby="navbarDropdown">
              <div class="dropdown-divider"></div>
              <a 
                class="dropdown-item" 
                @click="logout">Logout</a>
            </div>
          </li>
        </form>
      </div>
    </nav>
  </div>
</template>
<script>

export default {
  computed: {
    user() {
      if (this.$store.state.user) {
        return this.$store.state.user.data.customer.name
      }
      return {}
    }
  },
  methods:{
    logout() {
      this.$store.commit('SET_USER', null)
      this.$store.commit('SET_TOKEN', null)
      window.location.href = location.protocol+'//'+location.host+"/";
    }
  }
}
</script>
