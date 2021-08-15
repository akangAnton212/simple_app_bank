<template>
  <div>
    <div class="col-lg-12">
      <div class="row>">
        <div class="col-lg-8">
          <div class="form-group">
            <div class="input-group">
              <input 
                type="text" 
                class="form-control"
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
            class="btn btn-md btn-primary"
            data-toggle="modal" 
            data-target="#modal_add">Tambah Data</button>
        </div>
      </div>
      <div 
        class="col-lg-12 mt-2">
        <table class="table table-striped">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Item Code</th>
            <th scope="col">Item Name</th>
            <th scope="col">Unit</th>
            <th scope="col">Alias Code</th>
            <th scope="col">Alias Name</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody v-if="listInvItem.data !== undefined">
          <tr
            v-for="(val, i) in listInvItem.data.data"
            :key="i">
            <td>{{ i+=1 }}</td>
            <td>{{ val.item_code }}</td>
            <td>{{ val.name }}</td>
            <td>{{ val.unit }}</td>
            <td>{{ val.alias_code }}</td>
            <td>{{ val.alias_name }}</td>
            <td>
              <button class="btn btn-sm btn-warning">
                <i class="fa fa-pencil"/>
              </button>
              <button class="btn btn-sm btn-danger">
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
  </div>
</template>
<script>

export default {
  middleware: 'auth',
  layout: "dashboard",


  head() {
    return {
      title: "Inventory Item",
      link: [
        { rel: 'stylesheet', type: 'text/css', href: '/css/vue-multiselect.min.css' }
      ]
    };
  },
  async asyncData({ app, route: { params }, store, error }) {
    try {
      const listInvItem = await app.$axios.$get('inv_item/list')  
    
      return {
        listInvItem,
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
    };
  },
  
  computed: {
    currentPage: {
      get() {
        if (this.listInvItem.data && this.listInvItem.data.current_page) {
          return this.listInvItem.data.current_page
        }
        return 0
      },
      set(val) {
        this.listInvItem.data.current_page = val
      }
    },
    rows: {
      get() {
        if (this.listInvItem.data && this.listInvItem.data.total) {
          return this.listInvItem.data.total
        }
        return 0
      },
      set(val) {
        this.listInvItem.data.total = val
      }
    },
    perPage: {
      get() {
        if (this.listInvItem.data && this.listInvItem.data.per_page) {
          return this.listInvItem.data.per_page
        }
        return 0
      },
      set(val) {
        this.listInvItem.data.per_page = val
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

      return this.$axios.$get('inv_item/list', {
        params: paramsFiler
      })
        .then((res) => {
          if (res.status) {
            this.listInvItem = res
          } else {
            this.listInvItem = []
            // this.$swal("Error", res.message, "error")
          }
        })
        .catch((error) => {
          console.log(error)
        })
    },
  }
};
</script>