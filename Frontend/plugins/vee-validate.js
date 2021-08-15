import Vue from 'vue'
import VeeValidate from 'vee-validate'

// Vue.use(VeeValidate, {
//   inject: true,
//   errorBagName: 'vErrors'
// })

Vue.use(VeeValidate, {
  inject: true,
  fieldsBagName: 'vErrors'
});
