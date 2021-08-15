export const state = () => ({
  user: null,
  token: null,
})
export const strict = false

export const mutations = {
  SET_USER (state, data) {
    state.user = data
  },
  SET_TOKEN (state, token) {
    state.token = token
  },
}

export const actions = {
  // nuxtServerInit  dijalankan oleh Nuxt.js sebelum server-rendering setiap page
  async nuxtServerInit ({ commit, dispatch }, { app, req }) {
    // Cek apakah ada session dari  express js atau tidak
    if (req.session && req.session.auth) {
      const token = req.session.auth.access_token
      const type_user = req.session.type
      
      app.$axios.setToken(token, 'Bearer')
      let endpoint_profile_user = "api/v1/user/profile";
      if(type_user === 'customer'){
        endpoint_profile_user = "api/v1/customer/info";
      }
      await app.$axios.$get(endpoint_profile_user)
        .then((response) => {
          if (response) {
            commit('SET_USER', response)
            commit('SET_TOKEN', token)
          } else {
            commit('SET_USER', null)
            commit('SET_TOKEN', null)
            delete req.session.auth
          }
        })
        .catch(() => {
          commit('SET_USER', null)
          commit('SET_TOKEN', null)
          delete req.session.auth
        })
    } else {
      delete req.session.auth
      commit('SET_USER', null)
      commit('SET_TOKEN', null)
    }
  },
  nuxtClientInit ({ commit, dispatch }, { req, app }) {
    // Cek apakah ada session dari  express js atau tidak
    const token2 = req.session.auth.access_token
    if (!token2) {
      
      commit('SET_USER', null)
      commit('SET_TOKEN', null)
      app.router.push('/')
    }
  }
}
