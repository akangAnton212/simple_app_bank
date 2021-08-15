<template>
  <div>
     <div class="col-lg-12">
      <form>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label 
                class="col-lg-4 col-form-label fonts-12" 
                for="old_password">
                Old Passoword
              </label>
              <div 
                class="col-lg-8">
                <input
                  id="old_password"
                  class="form-control fonts-12"
                  v-model="old_password"
                  name="old_password"
                  data-vv-as="old_password"
                  placeholder="Old Passoword"
                  type="password">
              </div>   
            </div>
            <div class="form-group row">
              <label 
                class="col-lg-4 col-form-label fonts-12" 
                for="new_password">
                New Password
              </label>
              <div 
                class="col-lg-8">
                <input
                  id="new_password"
                  class="form-control fonts-12"
                  v-model="new_password"
                  name="new_password"
                  data-vv-as="new_password"
                  placeholder="New Password"
                  type="password">
              </div>   
            </div>
            <div class="form-group row">
              <label 
                class="col-lg-4 col-form-label fonts-12" 
                for="confirm_new_password">
                Confirm New Password
              </label>
              <div 
                class="col-lg-8">
                <input
                  id="confirm_new_password"
                  class="form-control fonts-12"
                  v-model="confirm_new_password"
                  name="confirm_new_password"
                  data-vv-as="confirm_new_password"
                  placeholder="Confirm New Password"
                  type="password">
              </div>   
            </div>
          </div>
          <div class="col-lg-12 mt-2">
            <button
              id="tutup" 
              type="button" 
              class="btn btn-warning btn-small-text" 
              style="float: right;"
              @click="resetFormChangePin">
              <span class="fonts-12">Cancel</span>
            </button>
            <button 
              type="button" 
              class="btn btn-success btn-small-text mr-2"
              style="float: right;"
              @click="simpan($event)">
              <span class="fonts-12">Change Pin</span>
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
<script>

export default {
  middleware: 'auth',
  layout: "home_customer",


  head() {
    return {
      title: "Change Pin Page",
      link: [
        { rel: 'stylesheet', type: 'text/css', href: '/css/vue-multiselect.min.css' }
      ]
    };
  },

  data() {
    return {
      old_password:'',
      new_password:'',
      confirm_new_password:''
    };
  },

  created(){
    
  },
  
  watch:{
   
  },

  computed: {
    
  },

  methods: {
    resetFormChangePin(){
      this.old_password = ''
      this.new_password = ''
      this.confirm_new_password = ''
    },

    simpan(){
      if(this.new_password !== this.confirm_new_password){
        return this.notifikasiPopUp('errorx', 'New password and confirm password not same!!');
      }

      if(this.new_password.length < 6 || this.confirm_new_password.length < 6){
        return this.notifikasiPopUp('errorx', 'New password or confirm password at least 6 characters!!');
      }

      const fd = new FormData();
      fd.set("old_password", this.old_password);
      fd.set("new_password", this.new_password);
      fd.set("confirm_new_password", this.confirm_new_password);

      return this.$swal({
        title: 'Change Pin',
        text: 'Are You Sure?',
        icon: 'warning',
        buttons: true,
      }).then(async ok => {
        if (ok) {
          try {
            let change_pin = await this.$axios.$post("/api/v1/transaction/changePasswordAccount", fd);
            if(change_pin.status === true){
              this.notifikasiPopUp('success', change_pin.message);
              this.resetFormChangePin()
              setTimeout(() => {
                this.$store.commit('SET_USER', null)
                this.$store.commit('SET_TOKEN', null)
                window.location.href = location.protocol+'//'+location.host+"/";
              }, 1000);
            }else{
              this.notifikasiPopUp('errorx', change_pin.message);
            }
          } catch (error) {
            this.errorHandle(error)
          }
        }
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
            return this.notifikasiPopUp('errorx', 'Internal Server Error!')
            break;
        }
      }
    },
  }
};
</script>
<style scoped>
  .fonts-12 {
    font-size: 12px;
    font-family: 'Roboto';
  }
  .fonts-14 {
    font-size: 14px;
    font-family: 'Roboto';
  }
  .btn-small {
    height: 25px;
    width: 25px;
    padding: 1px 3px;
    font-size: 12px;
    line-height: 1;
    border-radius: 3px;
  }
  .btn-small-text {
    height: 25px;
    width: auto;
    padding: 1px 3px;
    font-size: 12px;
    line-height: 1;
    border-radius: 3px;
  }
</style>