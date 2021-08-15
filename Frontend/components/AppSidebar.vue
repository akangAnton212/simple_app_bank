<template>
  <nav 
    class="main-menu">
	<ul>
	  <li class="has-subnav">
		<a href="javascript:;">
		  <i class="fa fa-check-square-o nav_icon"></i>
		  <span class="nav-text">
		    Setting
		  </span>
		  <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
		</a>
		<ul>
		  <li>
			<nuxt-link :to="{ name: 'admin-setting-customer-id' }">
			  <i
				aria-hidden="true" />
				<span class="nav-text">
				  Customer
				</span>
			</nuxt-link>
		  </li>
		</ul>
	  </li>
	</ul>
	<ul class="logout">
	  <li>
		<a @click="logout">
		  <i class="icon-off nav-icon" />
		  <span class="nav-text">
		    Logout
		  </span>
		</a>
	  </li>
	</ul>
  </nav>
</template>
<script>
const Cookie = process.client ? require('js-cookie') : undefined
export default {
  computed: {
    user() {
      if (this.$store.state.user) {
        return this.$store.state.user
      }
      return {}
    }
  },
  methods:{
    logout() {
      Cookie.remove('access_token')
      Cookie.remove('name')
      this.$store.commit('SET_USER', null)
      this.$store.commit('SET_TOKEN', null)
      this.$nuxt.$router.replace({ path: '/' })
    }
  }
}
</script>