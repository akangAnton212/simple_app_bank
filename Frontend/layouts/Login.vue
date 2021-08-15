<template>
  <div class="limiter">
    <div 
      class="container-login100">
        <div class="wrap-login100">
          <form class="login100-form validate-form">
            <div 
              class="wrap-input100 validate-input" 
              data-validate = "Enter username">
              <input 
                class="input100" 
                type="text" name="username" 
                placeholder="Username/Account Number"
                v-model="txusername">
              <span 
                class="focus-input100" 
                data-placeholder="&#xf207;" />
            </div>

            <div 
              class="wrap-input100 validate-input" 
              data-validate="Enter password">
              <input 
                class="input100" 
                type="password" 
                name="pass" 
                placeholder="Password"
                v-model="txpassword">
              <span 
                class="focus-input100" 
                data-placeholder="&#xf191;" />
            </div>

            <div 
              class="contact100-form-checkbox">
              <input 
                class="input-checkbox100" 
                id="ckb1" type="checkbox" 
                name="remember-me">
              <label 
                class="label-checkbox100" 
                for="ckb1">
                Remember me
              </label>
            </div>

            <div 
              class="container-login100-form-btn">
              <button 
                class="login100-form-btn"
                @click="masuk($event)">
                Login
              </button>
            </div>

            <div 
              style="margin-bottom:20px;margin-top:30px;text-align: center;">
              <a 
                class="txt1" 
                href="#">
                Forgot Password?
              </a>
            </div>
          </form>
        </div>
    </div>
  </div>
</template>
<script>
  export default {
    head() {
      return {
        title: "Login",
      };
    },
    data() {
      return {
        url: process.env.API_URL,
        txusername:'',
        txpassword:''
      };
    },
    methods: {
      masuk(e) {
        e.preventDefault()
        let endpoint_api = "";
        let home_page = "";
        if(isNaN(Number(this.txusername))){
          endpoint_api = "/login";
          home_page = "/home/admin";
        }else{
          endpoint_api = "/customer/login";
          home_page = "/home/customer";
        }

        this.$axios.$post(location.protocol + '//' + location.host + endpoint_api, {
          username: this.txusername,
          password: this.txpassword
        }).then((response) => {
          this.$store.commit('SET_USER', response.user)
          this.$store.commit('SET_TOKEN', response.access_token)
          window.location.href = location.protocol+'//'+location.host+home_page;
        }).catch(error => {
          this.errorHandle(error)
        })
      },

      notifikasiPopUp(type, message){
        const successNotification = window.createNotification({
          theme: type,
          showDuration: 3000,
          closeOnClick: true,
        });

        successNotification({
          title: 'Notification',
          message: message 
        });
      },

      errorHandle(error) {
        if (error.response) {
          let status_code = error.response.status
          console.log("STATUS "+ error.response.data.description)
          switch (status_code) {
            case 422:
                this.notifikasiPopUp('errorx',`Please Complete the Data First!`)
              return
              break;
            case 401:
              this.notifikasiPopUp('errorx','Session is up, please re-login')
              setTimeout(() => {
                this.$store.commit('SET_USER', null)
                this.$store.commit('SET_TOKEN', null)
                window.location.href = location.protocol+'//'+location.host+'/';
              }, 1000);
              break;
          
            default:
              return this.notifikasiPopUp('errorx', error.response.data.description)
              break;
          }
        }
      },
    }
  };
</script>