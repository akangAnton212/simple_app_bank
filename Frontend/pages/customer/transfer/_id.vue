<template>
  <div>
    <div class="col-lg-12">
      <form>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group row">
              <label 
                class="col-lg-4 col-form-label fonts-12" 
                for="account_destination">
                Account Destination
              </label>
              <div 
                class="col-lg-8">
                <input
                  id="account_destination"
                  class="form-control fonts-12"
                  v-model="account_destination"
                  name="account_destination"
                  data-vv-as="account_destination"
                  placeholder="Account Destination"
                  type="text">
              </div>   
            </div>
            <div class="form-group row">
              <label 
                class="col-lg-4 col-form-label fonts-12" 
                for="nominal">
                Nominal
              </label>
              <div 
                class="col-lg-8">
                <input
                  id="nominal"
                  class="form-control fonts-12"
                  v-model="nominal"
                  name="nominal"
                  data-vv-as="nominal"
                  placeholder="Nominal"
                  type="text">
              </div>   
            </div>
          </div>
          <div class="col-lg-12 mt-2">
            <button
              id="tutup" 
              type="button" 
              class="btn btn-warning btn-small-text" 
              style="float: right;"
              @click="resetFormTansfer">
              <span class="fonts-12">Cancel</span>
            </button>
            <button 
              type="button" 
              class="btn btn-success btn-small-text mr-2"
              style="float: right;"
              @click="transfer($event)">
              <span class="fonts-12">Transfer..</span>
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
      title: "Transfer Page",
      link: [
        { rel: 'stylesheet', type: 'text/css', href: '/css/vue-multiselect.min.css' }
      ]
    };
  },

  data() {
    return {
      account_destination:'',
      nominal:0
    };
  },

  created(){
   
  },
  
  watch:{
   
  },

  computed: {
    
  },

  methods: {
    async transfer() {
      let account_destinations 
      try {
        account_destinations = await this.$axios.$get(`/api/v1/transaction/getCheckingAccount/${this.account_destination}/${this.nominal}`);
        if(account_destinations.status === true){
          let text = `Transfer to the account number ${this.account_destination} in the name of ${account_destinations.data.name} the amount of money is ${this.moneyFormat(this.nominal)} Are You Sure?`
          return this.$swal({
            title: 'Transfer Confirmation',
            text: text,
            icon: 'warning',
            buttons: true,
          }).then(async ok => {
            if (ok) {
              const fd = new FormData();
              fd.set("account_destination", this.account_destination);
              fd.set("nominal", this.nominal);
              let transfer = await this.$axios.$post("/api/v1/transaction/transfer", fd);
              if(transfer.status === true){
                this.notifikasiPopUp('success', transfer.message);
                this.resetFormTansfer()
                window.location.href = location.protocol+'//'+location.host+ "/home/customer";
              }else{
                this.notifikasiPopUp('errorx', transfer.message);
              }
            }
          })
        }else{
          this.notifikasiPopUp('errorx', account_destinations.message);
        }
      } catch (error) {
        this.errorHandle(error)
      }
    },

    resetFormTansfer() {
      this.account_destination = ""
      this.nominal = 0
    },

    moneyFormat (val) {
      if (val) {
        const value = parseInt(val)
        return 'Rp. ' + value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1.')
      }
      return 'Rp. 0'
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