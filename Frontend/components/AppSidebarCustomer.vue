<template>
  <nav 
    class="main-menu">
	<ul>
	  <li>
	    <nuxt-link :to="'/home/customer'">
		  <i 
		    class="fa fa-home nav-icon"
			aria-hidden="true" />
			<span class="nav-text">
			  Home
			</span>
		</nuxt-link>
	  </li>
	  <li class="has-subnav">
		<a href="javascript:;">
		  <i class="fa fa-check-square-o nav_icon"></i>
		  <span class="nav-text">
		    Transaksi
		  </span>
		  <i class="icon-angle-right"></i><i class="icon-angle-down"></i>
		</a>
		<ul>
		  <li>
			<nuxt-link :to="{ name: 'customer-transfer-id' }">
			  <i
				aria-hidden="true" />
				<span class="nav-text">
				  Transfer
				</span>
			</nuxt-link>
		  </li>
		  <li>
			<nuxt-link :to="{ name: 'customer-changepin-id' }">
			  <i
				aria-hidden="true" />
				<span class="nav-text">
				  Change Pin
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