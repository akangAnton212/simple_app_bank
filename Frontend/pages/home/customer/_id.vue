<template>
  <div>
    <div class="col-lg-12">
      <div class="row">
        <div class="col-lg-12">
          <div class="form-group row">
            <label 
              class="col-lg-4 col-form-label fonts-12" 
              for="NIK">
              Account Number
            </label>
            <div 
              class="col-lg-8">
              <label 
                class="col-lg-4 col-form-label fonts-12" 
                for="NIK">
                {{ account_number }}
              </label>
            </div>   
          </div>
          <div class="form-group row">
            <label 
              class="col-lg-4 col-form-label fonts-12" 
              for="NIK">
              Card Number
            </label>
            <div 
              class="col-lg-8">
              <label 
                class="col-lg-4 col-form-label fonts-12" 
                for="NIK">
                {{ card_number }}
              </label>
            </div>   
          </div>
          <div class="form-group row">
            <label 
              class="col-lg-4 col-form-label fonts-12" 
              for="NIK">
              Balance
            </label>
            <div 
              class="col-lg-8">
              <label 
                class="col-lg-4 col-form-label fonts-12" 
                for="NIK">
                {{ moneyFormat(balance) }}
              </label>
            </div>   
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-12 mt-2">
      <!-- Nav tabs -->
      <ul class="nav nav-tabs" role="tablist">
        <li
          class="active">
          <a
            href="#history_transfer"
            role="tab"
            data-toggle="tab"
            @click="setTabActive(1)">
            <span class="fonts-12">History Transfer</span>
          </a>
        </li>
        <li>
          <a
            href="#mutation"
            role="tab"
            data-toggle="tab"
            @click="setTabActive(2)">
            <span class="fonts-12">Mutation Account</span>
          </a>
        </li>
      </ul>

      <!-- Tab panes -->
      <div class="tab-content">
        <div
          class="tab-pane active"
          id="history_transfer">
          <div class="form-group mt-3">
            <span class="fonts-12">Trans Date</span>
            <date-picker v-model="trans_date" range :lang="'en'"></date-picker>
          </div>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col"><span class="fonts-12">#</span></th>
                <th scope="col"><span class="fonts-12">Trans Code</span></th>
                <th scope="col"><span class="fonts-12">Sender Name</span></th>
                <th scope="col"><span class="fonts-12">Receiver Name</span></th>
                <th scope="col"><span class="fonts-12">Nominal</span></th>
                <th scope="col"><span class="fonts-12">Trans Date</span></th>
                <th scope="col"><span class="fonts-12">Description</span></th>
              </tr>
            </thead>
            <tbody v-if="history_transfer.data !== undefined">
              <tr
                v-for="(val, i) in history_transfer.data.data"
                :key="i">
                <td><span class="fonts-12">{{ i+=1 }}</span></td>
                <td><span class="fonts-12">{{ val.trans_code }}</span></td>
                <td><span class="fonts-12">{{ val.sender_by }}</span></td>
                <td><span class="fonts-12">{{ val.receive_by }}</span></td>
                <td><span class="fonts-12">{{ moneyFormat(val.nominal) }}</span></td>
                <td><span class="fonts-12">{{ val.trans_date }}</span></td>
                <td><span class="fonts-12">{{ val.description }}</span></td>
              </tr>
            </tbody>
          </table>
          <div class="col-lg-12">
            <b-pagination
              v-model="currentPage"
              :total-rows="rows"
              :per-page="perPage"
              first-text="First"
              prev-text="Prev"
              next-text="Next"
              last-text="Last"></b-pagination>
          </div>
        </div>
        <div
          class="tab-pane"
          id="mutation">
          <div class="form-group mt-3">
            <span class="fonts-12">Trans Date</span>
            <date-picker v-model="trans_date" range :lang="'en'"></date-picker>
          </div>
          <table class="table table-sm">
            <thead>
              <tr>
                <th scope="col"><span class="fonts-12">#</span></th>
                <th scope="col"><span class="fonts-12">Trans Date</span></th>
                <th scope="col"><span class="fonts-12">Description</span></th>
                <th scope="col"><span class="fonts-12">Debet</span></th>
                <th scope="col"><span class="fonts-12">Credit</span></th>
                <th scope="col"><span class="fonts-12">Final Balance</span></th>
              </tr>
            </thead>
            <tbody v-if="mutation_list.data !== undefined">
              <tr
                v-for="(val, i) in mutation_list.data.data"
                :key="i">
                <td><span class="fonts-12">{{ i+=1 }}</span></td>
                <td><span class="fonts-12">{{ val.mutation_date }}</span></td>
                <td><span class="fonts-12">{{ val.description }}</span></td>
                <td><span class="fonts-12">{{ val.nominal_debet !== "-" ? moneyFormat(val.nominal_debet) : val.nominal_debet }}</span></td>
                <td><span class="fonts-12">{{ val.nominal_credit !== "-" ? moneyFormat(val.nominal_credit) : val.nominal_credit }}</span></td>
                <td><span class="fonts-12">{{ moneyFormat(val.balance) }}</span></td>
              </tr>
            </tbody>
          </table>
          <div class="col-lg-12">
            <b-pagination
              v-model="currentPage"
              :total-rows="rows"
              :per-page="perPage"
              first-text="First"
              prev-text="Prev"
              next-text="Next"
              last-text="Last"></b-pagination>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
<script>

export default {
  middleware: 'auth',
  layout: "home_customer",

  async asyncData({ app, route: { params }, store, error }) {
    try {
      const history_transfer = await app.$axios.$get(`/api/v1/transaction/getTransferHistoryOut/${store.state.user.data.uid}`)  
      const mutation_list = await app.$axios.$get(`/api/v1/transaction/getMutation/${store.state.user.data.uid}`)  
      return {
        history_transfer,
        mutation_list
      }

    } catch (err) {
      if (err && err.response.status === 404) {
        return error({ statusCode: 404, message:'Halaman tidak ditemukan'})
      }   
      return error({ statusCode: 500, message: 'Terjadi kesalahan pada server kami' })
    }
  },

  head() {
    return {
      title: "Customer Page",
      link: [
        { rel: 'stylesheet', type: 'text/css', href: '/css/vue-multiselect.min.css' }
      ]
    };
  },

  data() {
    return {
      trans_date:[],
      tab_active: 1,
    };
  },

  computed: {
    account_number() {
      if (this.$store.state.user) {
        return this.$store.state.user.data.account_number
      }
      return {}
    },
    uid_account(){
      if (this.$store.state.user) {
        return this.$store.state.user.data.uid
      }
      return {}
    },
    card_number() {
      if (this.$store.state.user) {
        return this.$store.state.user.data.card_number
      }
      return {}
    },
    balance() {
      if (this.$store.state.user) {
        return this.$store.state.user.data.balance
      }
      return {}
    },
    currentPage: {
      get() {
        if(this.tab_active === 1){
          if (this.history_transfer.data && this.history_transfer.data.current_page) {
            return this.history_transfer.data.current_page
          }
          return 0
        }else{
          if (this.mutation_list.data && this.mutation_list.data.current_page) {
            return this.mutation_list.data.current_page
          }
          return 0
        }
      },
      set(val) {
        if(this.tab_active === 1){
          this.history_transfer.data.current_page = val
        }else{
          this.mutation_list.data.current_page = val
        }
      }
    },
    rows: {
      get() {
        if(this.tab_active === 1){
          if (this.history_transfer.data && this.history_transfer.data.total) {
            return this.history_transfer.data.total
          }
          return 0
        }else{
          if (this.mutation_list.data && this.mutation_list.data.total) {
            return this.mutation_list.data.total
          }
          return 0
        }
      },
      set(val) {
        if(this.tab_active === 1){
          this.history_transfer.data.total = val
        }else{
          this.mutation_list.data.total = val
        }
      }
    },
    perPage: {
      get() {
        if(this.tab_active === 1){
          if (this.history_transfer.data && this.history_transfer.data.per_page) {
            return this.history_transfer.data.per_page
          }
          return 0
        }else{
          if (this.mutation_list.data && this.mutation_list.data.per_page) {
            return this.mutation_list.data.per_page
          }
          return 0
        }
       
      },
      set(val) {
        if(this.tab_active === 1){
          this.history_transfer.data.per_page = val
        }else{
          this.mutation_list.data.per_page = val
        }
      }
    },
  },

  created(){
   
  },
  
  watch:{
   currentPage:'loadData',
   trans_date:'loadData'
  },

  methods: {
    setTabActive(code){
      this.tab_active = code
      this.trans_date = []
    },

    moneyFormat (val) {
      if (val) {
        const value = parseInt(val)
        return 'Rp. ' + value.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1.')
      }
      return 'Rp. 0'
    },

    loadHistoryTransfer(){
      let paramsFiler = {}
      if(this.trans_date.length > 0){
        paramsFiler = {
          page: this.currentPage,
          start_date: this.$moment(this.trans_date[0]).format('YYYY-MM-DD'),
          end_date: this.$moment(this.trans_date[1]).format('YYYY-MM-DD')
        }
      }else{
        paramsFiler = {
          page: this.currentPage,
        }
      }
      
      return this.$axios.$get(`/api/v1/transaction/getTransferHistoryOut/${this.uid_account}`, {
        params: paramsFiler
      })
      .then((res) => {
        if (res.status) {
          this.history_transfer = res
        } else {
          this.history_transfer = []
        }
      })
      .catch((error) => {
        this.errorHandle(error)
      })
    },

    loadData(){
      let paramsFiler = {}
      if(this.trans_date.length > 0){
        paramsFiler = {
          page: this.currentPage,
          start_date: this.$moment(this.trans_date[0]).format('YYYY-MM-DD 00:00:00'),
          end_date: this.$moment(this.trans_date[1]).format('YYYY-MM-DD 23:59:59')
        }
      }else{
        paramsFiler = {
          page: this.currentPage,
        }
      }

      if(this.tab_active === 1){
        return this.$axios.$get(`/api/v1/transaction/getTransferHistoryOut/${this.uid_account}`, {
          params: paramsFiler
        })
        .then((res) => {
          if (res.status) {
            this.history_transfer = res
          } else {
            this.history_transfer = []
          }
        })
        .catch((error) => {
          this.errorHandle(error)
        })
      }else{
        return this.$axios.$get(`/api/v1/transaction/getMutation/${this.uid_account}`, {
          params: paramsFiler
        })
        .then((res) => {
          if (res.status) {
            this.mutation_list = res
          } else {
            this.mutation_list = []
          }
        })
        .catch((error) => {
          this.errorHandle(error)
        })
      }
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