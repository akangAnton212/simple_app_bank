<template>
  <div>
    <div class="col-lg-12">
      <div class="row>">
        <div class="col-lg-8">
          <div class="form-group">
            <div class="input-group">
              <input 
                type="text" 
                class="form-control fonts-12"
                placeholder="Search" 
                id="inputGroup"
                v-model="txtCari"/>
              <span 
                class="input-group-addon"
                style="width:40px;">
                <i 
                  class="fa fa-search"
                  style="margin-top:5px; margin-right:15px;"></i>
              </span>
            </div>
          </div>
        </div>
        <div class="col-lg-4 mt-3">
          <button
            class="btn btn-sm btn-primary"
            data-toggle="modal" 
            data-target="#modal_add"
            @click="openModals(1, '')">
            <span class="fonts-12">Tambah Data</span>  
          </button>
        </div>
      </div>
      <div 
        class="col-lg-12 mt-2">
        <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col"><span class="fonts-12">#</span></th>
            <th scope="col"><span class="fonts-12">Name</span></th>
            <th scope="col"><span class="fonts-12">Place, Birthday</span></th>
            <th scope="col"><span class="fonts-12">Email</span></th>
            <th scope="col"><span class="fonts-12">Address</span></th>
            <th scope="col"><span class="fonts-12">Action</span></th>
          </tr>
        </thead>
        <tbody v-if="listCustomer.data !== undefined">
          <tr
            v-for="(val, i) in listCustomer.data.data"
            :key="i">
            <td><span class="fonts-12">{{ i+=1 }}</span></td>
            <td><span class="fonts-12">{{ val.name }}</span></td>
            <td><span class="fonts-12">{{ val.POB }}, {{ val.DOB }}</span></td>
            <td><span class="fonts-12">{{ val.email }}</span></td>
            <td><span class="fonts-12">{{ val.address }}</span></td>
            <td>
              <button 
                class="btn btn-small btn-success"
                data-toggle="modal" 
                data-target="#modal_add"
                @click="openModals(2, val)">
                <i class="fa fa-info-circle"/>
              </button>
              <button
                class="btn btn-small btn-warning"
                data-toggle="modal" 
                data-target="#modal_add"
                @click="openModals(3,val)">
                <i class="fa fa-pencil"/>
              </button>
              <button
                class="btn btn-small btn-danger"
                @click="deleteCustomer(val)">
                <i class="fa fa-trash"/>
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      </div>
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
     <!-- MODAL -->
    <div
      id="modal_add" 
      class="modal fade" 
      tabindex="-1" 
      role="dialog" 
      aria-labelledby="myLargeModalLabel" 
      aria-hidden="true"
      @click.self="resetModalCustomer">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">
                <span class="fonts-14">{{ modals.header }}</span>
              </h5>
              <button 
                type="button" 
                class="close" 
                data-dismiss="modal" 
                aria-label="Close"
                @click="resetModalCustomer">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              <form v-if="openTabAccount === false">
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group row">
                      <label 
                        class="col-lg-4 col-form-label fonts-12" 
                        for="NIK">
                        NIK
                      </label>
                      <div 
                        class="col-lg-8">
                        <input
                          id="nik"
                          class="form-control fonts-12"
                          v-model="nik"
                          name="nik"
                          data-vv-as="NIK"
                          placeholder="NIK"
                          type="text">
                      </div>   
                    </div>
                    <div class="form-group row">
                      <label 
                        class="col-lg-4 col-form-label fonts-12" 
                        for="name">
                        Name
                      </label>
                      <div 
                        class="col-lg-8">
                        <input
                          id="name"
                          class="form-control fonts-12"
                          v-model="name"
                          name="name"
                          data-vv-as="name"
                          placeholder="Name"
                          type="text">
                      </div>   
                    </div>
                    <div class="form-group row">
                      <label 
                        class="col-lg-4 col-form-label fonts-12" 
                        for="dob">
                        Place and Date Of Birth
                      </label>
                      <div 
                        class="col-lg-8">
                        <div class="row">
                          <div class="col-lg-6">
                            <input
                              id="pob"
                              class="form-control fonts-12"
                              v-model="pob"
                              name="name"
                              data-vv-as="pob"
                              placeholder="Place Of Birth"
                              type="text">
                          </div>
                          <div class="col-lg-6">
                            <date-picker
                              placeholder="YYYY-MM-DD"
                              format="YYYY-MM-DD"
                              :lang="'en'"
                              v-model="dob" />
                          </div>
                        </div>
                      </div>   
                    </div>
                    <div class="form-group row">
                      <label 
                        class="col-lg-4 col-form-label fonts-12" 
                        for="email">
                        Email
                      </label>
                      <div 
                        class="col-lg-8">
                        <input
                          id="email"
                          class="form-control fonts-12"
                          v-model="email"
                          name="email"
                          data-vv-as="email"
                          placeholder="Email"
                          type="text">
                      </div>   
                    </div>
                    <div class="form-group row">
                      <label 
                        class="col-lg-4 col-form-label fonts-12" 
                        for="address">
                        Address
                      </label>
                      <div 
                        class="col-lg-8">
                        <textarea
                          id="address"
                          name="address"
                          class="form-control fonts-12"
                          rows="4"
                          cols="50"
                          v-model="address">
                          </textarea>
                      </div>   
                    </div>
                    <div class="form-group row">
                      <label 
                        class="col-lg-4 col-form-label fonts-12" 
                        for="province">
                        Province
                      </label>
                      <div 
                        class="col-lg-8">
                        <input
                          id="province"
                          class="form-control fonts-12"
                          v-model="province"
                          name="province"
                          data-vv-as="province"
                          placeholder="Province"
                          type="text">
                      </div>   
                    </div>
                    <div class="form-group row">
                      <label 
                        class="col-lg-4 col-form-label fonts-12" 
                        for="city">
                        City
                      </label>
                      <div 
                        class="col-lg-8">
                        <input
                          id="city"
                          class="form-control fonts-12"
                          v-model="city"
                          name="city"
                          data-vv-as="city"
                          placeholder="City"
                          type="text">
                      </div>   
                    </div>
                    <div class="form-group row">
                      <label 
                        class="col-lg-4 col-form-label fonts-12" 
                        for="postal_code">
                        Postal Code
                      </label>
                      <div 
                        class="col-lg-8">
                        <input
                          id="postal_code"
                          class="form-control fonts-12"
                          v-model="postal_code"
                          name="postal_code"
                          data-vv-as="postal_code"
                          placeholder="Postal Code"
                          type="text">
                      </div>   
                    </div>
                  </div>
                </div>
              </form>
              <form v-else>
                <button 
                  type="button" 
                  class="btn btn-primary btn-small-text"
                  @click="backToCustomerForm($event)">
                  <span class="fonts-12">Back</span>
                </button>
                <div class="form-group row">
                  <label 
                    class="col-lg-4 col-form-label fonts-12" 
                    for="number_account">
                    Account Number
                  </label>
                  <div 
                    class="col-lg-8">
                    <input
                      id="account_number"
                      class="form-control fonts-12"
                      v-model="account.account_number"
                      name="account_number"
                      data-vv-as="account_number"
                      placeholder="Account Number"
                      type="number">
                  </div>   
                </div>
                <div class="form-group row">
                  <label 
                    class="col-lg-4 col-form-label fonts-12" 
                    for="password">
                    Password
                  </label>
                  <div 
                    class="col-lg-8">
                    <input
                      id="password"
                      class="form-control fonts-12"
                      v-model="account.password"
                      name="password"
                      data-vv-as="password"
                      placeholder="Password"
                      type="password">
                  </div>
                </div>
                <div class="form-group row">
                  <label 
                    class="col-lg-4 col-form-label fonts-12" 
                    for="card_number">
                    Card Number
                  </label>
                  <div 
                    class="col-lg-8">
                    <input
                      id="card_number"
                      class="form-control fonts-12"
                      v-model="account.card_number"
                      name="card_number"
                      data-vv-as="card_number"
                      placeholder="Card Number"
                      type="number">
                  </div>
                </div>
                <div class="form-group row">
                  <label 
                    class="col-lg-4 col-form-label fonts-12" 
                    for="balance">
                    Initial Balance
                  </label>
                  <div 
                    class="col-lg-8">
                    <input
                      id="balance"
                      class="form-control fonts-12"
                      v-model="account.balance"
                      name="balance"
                      data-vv-as="balance"
                      placeholder="Initial Balance"
                      type="number">
                  </div>
                </div>
              </form>
              <button
                v-if="openTabAccount === false"
                type="button"
                class="btn btn-small-text btn-primary mt-2"
                :disabled="uid === '' ? true : false"
                @click="addAccount($event)">
                <span class="font-12">Add Account</span>
              </button>
              <table
                v-if="openTabAccount === false"
                class="table table-sm">
                <thead>
                  <tr>
                    <th scope="col"><span class="fonts-12">#</span></th>
                    <th scope="col"><span class="fonts-12">Accont Number</span></th>
                    <th scope="col"><span class="fonts-12">Card Number</span></th>
                    <th scope="col"><span class="fonts-12">Balance</span></th>
                    <th scope="col"><span class="fonts-12">Action</span></th>
                  </tr>
                </thead>
                <tbody v-if="list_account.length > 0">
                  <tr
                    v-for="(val, i) in list_account"
                    :key="i">
                    <td><span class="fonts-12">{{ i+=1 }}</span></td>
                    <td><span class="fonts-12">{{ val.account_number }}</span></td>
                    <td><span class="fonts-12">{{ val.card_number }}</span></td>
                    <td><span class="fonts-12">{{ val.balance }}</span></td>
                    <td>
                      <button 
                        class="btn btn-small btn-warning"
                        @click="editAccount(val)">
                        <i class="fa fa-pencil"/>
                      </button>
                      <button
                        class="btn btn-small btn-danger"
                        @click="deleteAccount(val)">
                        <i class="fa fa-trash"/>
                      </button>
                    </td>
                  </tr>
                </tbody>
                <tbody v-else>
                  <span class="fonts-12">Account Not Found!</span>
                </tbody>
              </table>
            </div>
            <div
              v-if="openTabAccount === false"
              class="modal-footer">
              <button
                id="tutup" 
                type="button" 
                class="btn btn-warning btn-small-text" 
                data-dismiss="modal"
                @click="resetModalCustomer">
                <span class="fonts-12">Close</span>
              </button>
              <button
                type="button" 
                class="btn btn-success btn-small-text"
                :disabled="modals.type === 2 ? true : false"
                @click="simpan($event)">
                <span class="fonts-12">Save</span>
              </button>
            </div>
            <div
              v-else
              class="modal-footer">
              <button
                id="tutup" 
                type="button" 
                class="btn btn-warning btn-small-text" 
                data-dismiss="modal"
                @click="resetModalAccount">
                <span class="fonts-12">Close</span>
              </button>
              <button 
                type="button" 
                class="btn btn-success btn-small-text"
                :disabled="modals.type === 2 ? true : false"
                @click="simpanAkun($event)">
                <span class="fonts-12">Save</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
      <!-- END MODAL -->
  </div>
</template>
<script>

export default {
  middleware: 'auth',
  layout: "home_admin",


  async asyncData({ app, route: { params }, store, error }) {
    try {
      const listCustomer = await app.$axios.$get('api/v1/customer/list')  
    
      return {
        listCustomer,
      }

    } catch (err) {
      if (err && err.response.status === 404) {
        return error({ statusCode: 404, message:'Halaman tidak ditemukan'})
      }   
      return error({ statusCode: 500, message: 'Terjadi kesalahan pada server kami' })
    }
  },

  data() {
    return {
      txtCari:'',
      nik:'',
      name:'',
      pob:'',
      dob:'',
      email:'',
      address:'',
      province:'',
      city:'',
      postal_code:'',
      uid:'',
      modals:{
        header:'Add Customer',
        type: 1 //default 1 => add, 2 detail, 3 edit
      },
      openTabAccount: false,
      account:{
        account_number:'',
        password:'',
        card_number:'',
        balance:0,
        uid_account:'',
      },
      list_account: [],
      txtHeader:'',
    };
  },
  
  computed: {
    currentPage: {
      get() {
        if (this.listCustomer.data && this.listCustomer.data.current_page) {
          return this.listCustomer.data.current_page
        }
        return 0
      },
      set(val) {
        this.listCustomer.data.current_page = val
      }
    },
    rows: {
      get() {
        if (this.listCustomer.data && this.listCustomer.data.total) {
          return this.listCustomer.data.total
        }
        return 0
      },
      set(val) {
        this.listCustomer.data.total = val
      }
    },
    perPage: {
      get() {
        if (this.listCustomer.data && this.listCustomer.data.per_page) {
          return this.listCustomer.data.per_page
        }
        return 0
      },
      set(val) {
        this.listCustomer.data.per_page = val
      }
    },
  },

  created(){
   
  },
  
  watch:{
    currentPage:'loadInvItem',
    txtCari:'loadInvItem',
  },

  methods: {
    async loadInvItem(){
      let paramsFiler = {
        page: this.currentPage,
        search: this.txtCari,
      }

      return this.$axios.$get('api/v1/customer/list', {
        params: paramsFiler
      })
      .then((res) => {
        if (res.status) {
          this.listCustomer = res
        } else {
          this.listCustomer = []
        }
      })
      .catch((error) => {
        this.errorHandle(error)
      })
    },

    deleteCustomer(data){
      const fd = new FormData();
      fd.set("NIK", data.NIK);
      fd.set("name", data.name);
      fd.set("DOB", this.$moment(data.DOB).format('YYYY-MM-DD'));
      fd.set("POB", data.POB);
      fd.set("email", data.email);
      fd.set("address", data.address);
      fd.set("province", data.province);
      fd.set("city", data.city);
      fd.set("enabled", 0);
      fd.set("uid", data.uid);
      fd.set("postal_code", data.postal_code);

      return this.$swal({
        title: 'Delete Customer',
        text: 'Are You Sure?',
        icon: 'warning',
        buttons: true,
      }).then(async ok => {
        if (ok) {
          try {
            let post_customer = await this.$axios.$post("/api/v1/customer/add", fd);
            if(post_customer.status === true){
              this.notifikasiPopUp('success', post_customer.message);
              this.getCustomer()
            }else{
              this.notifikasiPopUp('errorx', post_customer.message);
            }
          } catch (error) {
            this.errorHandle(error)
          }
        }
      })
    },

    resetModalCustomer(){
      this.openTabAccount = false
      this.nik = ""
      this.name = ""
      this.pob = ""
      this.dob = ""
      this.email = ""
      this.address = ""
      this.province = ""
      this.city = ""
      this.postal_code= ""
      this.uid = ""
      this.list_account = []
    },

    editAccount(data){
      this.openTabAccount = true
      this.modals.header = "Edit Account " + this.name

      this.account.account_number = data.account_number
      this.account.password = ""
      this.account.card_number = data.card_number
      this.account.balance =data.balance
      this.account.uid_account = data.uid_customer_account
    },

    deleteAccount(data){
      const fd = new FormData();
      fd.set("account_number", data.account_number);
      fd.set("uid_customer", this.uid);
      fd.set("password", "");
      fd.set("card_number", data.card_number);
      fd.set("balance", data.balance);
      fd.set("enabled", 0);
      fd.set("uid",data.uid_customer_account);

      return this.$swal({
        title: 'Delete Account',
        text: 'Are You Sure?',
        icon: 'warning',
        buttons: true,
      }).then(async ok => {
        if (ok) {
          try {
            let post_account = await this.$axios.$post("/api/v1/customer/addCustomerAccount", fd);
            if(post_account.status === true){
              this.notifikasiPopUp('success', post_account.message);
              let indexData = this.list_account.findIndex(el => el.uid_account === data.uid_customer_account)
              this.list_account.splice(indexData, 1)
              this.resetModalAccount()
              this.backToCustomerForm()
              
            }else{
              this.notifikasiPopUp('errorx', post_account.message);
            }
          } catch (error) {
            this.errorHandle(error)
          }
        }
      })
    },

    backToCustomerForm(e){
      e.preventDefault()
      this.openTabAccount = false
      this.modals.header = this.txtHeader
    },

    simpan(e){
      const fd = new FormData();
      fd.set("NIK", this.nik);
      fd.set("name", this.name);
      fd.set("DOB", this.$moment(this.dob).format('YYYY-MM-DD'));
      fd.set("POB", this.pob);
      fd.set("email", this.email);
      fd.set("address", this.address);
      fd.set("province", this.province);
      fd.set("city", this.city);
      fd.set("enabled", 1);
      fd.set("uid", this.uid);
      fd.set("postal_code", this.postal_code);

      return this.$swal({
        title: 'Save Customer',
        text: 'Are You Sure?',
        icon: 'warning',
        buttons: true,
      }).then(async ok => {
        if (ok) {
          try {
            let post_customer = await this.$axios.$post("/api/v1/customer/add", fd);
            if(post_customer.status === true){
              this.notifikasiPopUp('success', post_customer.message);
              this.setData(post_customer.data)
              this.getCustomer()
            }else{
              this.notifikasiPopUp('errorx', post_customer.message);
            }
          } catch (error) {
             this.errorHandle(error)
          }
        }
      })
    },

    simpanAkun(e){
      const fd = new FormData();
      fd.set("account_number", this.account.account_number);
      fd.set("uid_customer", this.uid);
      fd.set("password", this.account.password);
      fd.set("card_number", this.account.card_number);
      fd.set("balance", this.account.balance);
      fd.set("enabled", 1);
      fd.set("uid", this.account.uid_account);

      return this.$swal({
        title: 'Save Account',
        text: 'Are You Sure?',
        icon: 'warning',
        buttons: true,
      }).then(async ok => {
        if (ok) {
          try {
            let post_account = await this.$axios.$post("/api/v1/customer/addCustomerAccount", fd);
            if(post_account.status === true){
              this.notifikasiPopUp('success', post_account.message);
              this.list_account.push({
                id_customer_account: post_account.data.id,
                account_number: post_account.data.account_number,
                card_number: post_account.data.card_number,
                balance: post_account.data.balance,
                uid_customer_account: post_account.data.uid
              })
              this.resetModalAccount()
              this.backToCustomerForm()
              
            }else{
              this.notifikasiPopUp('errorx', post_account.message);
            }
          } catch (error) {
            this.errorHandle(error)
          }
        }
      })
    },

    getCustomer(){
      return this.$axios.$get('api/v1/customer/list')
      .then((resp) => {
        this.listCustomer = []
        this.listCustomer = resp
      })
      .catch((error) => {
        this.errorHandle(error)
      })
    },

    resetModalAccount(){
      this.account.account_number = ""
      this.account.password = ""
      this.account.card_number = ""
      this.account.balance = 0
      this.account.uid_account = ""
      this.modals.header = ""
      this.modals.type = 1
      this.openTabAccount = false
    },

    openModals(type, data){
      this.modals.type = type
      switch (type) {
        case 1:
          this.modals.header = "Add Customer"
          break;
        case 2:
          this.modals.header = "Detail Customer "+ data.name
          this.setData(data)
          break;
        case 3:
          this.modals.header = "Edit Data " + data.name
          this.setData(data)
          break;
        default:

          break;
      }
      this.txtHeader = this.modals.header
    },

    setData(data){
      this.list_account = []
      this.nik = data.NIK
      this.name = data.name
      this.pob = data.POB
      this.dob = this.$moment(data.DOB).format('YYYY-MM-DD')
      this.email = data.email
      this.address = data.address
      this.province = data.province
      this.city = data.city
      this.postal_code= data.postal_code
      this.uid = data.uid
      this.list_account = data.customer_accounts ? data.customer_accounts : []
    },

    addAccount(e){
      e.preventDefault()
      this.openTabAccount = true
      this.modals.header = "Add Account"
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
}
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