<template>
  <div>
    <!-- Header -->
    <Toasts></Toasts>
    <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center justify-content-between py-4">
            <div class="col-lg-6 col-7">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="#"><i class="far fa-sack-dollar"></i></a></li>
                  <li class="breadcrumb-item"><a href="#">Transaksi</a></li>
                </ol>
              </nav>
            </div><!-- /col -->
          </div><!-- /row -->

        </div><!-- /header-body -->
      </div><!-- /container-fluid -->
    </div><!-- /header -->

    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="header-body">
        <div class="row" v-if="transactionData && transactionData.data && transactionData.data.length">

          <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 m-b-20" v-for="(item, index) in transactionData.data" :key="index">
            <div class="card">
              <img :src="storage +'/ktp/' + item.users_ktp" class="card-img-top img-admin-trx" alt="penjual">
              <div class="card-body">
                <h5 class="card-title h4 mb-0">{{item.users_name}}</h5>

                <table class="table table-sm table-responsive table-fit">
                  <tr>
                    <td>No. Rek</td>
                    <td>:</td>
                    <td style="white-space: normal;">{{item.users_no_rek}}</td>
                  </tr>
                  <tr>
                    <td>A.n</td>
                    <td>:</td>
                    <td style="white-space: normal;">{{item.users_a_n}}</td>
                  </tr>
                  <tr>
                    <td>Alamat</td>
                    <td>:</td>
                    <td style="white-space: normal;">{{item.users_address}}, {{item.district[0]}}, {{item.users_code_pos}}</td>
                  </tr>
                  <tr>
                    <td>Balance</td>
                    <td>:</td>
                    <td class="lead text-danger mt-0 mb-0" style="white-space: normal;">Rp.{{formatNumber(item.balance)}}</td>
                  </tr>
                </table>

                <div class="text-center">
                  <button class="btn btn-primary btn-sm" data-toggle="modal" :data-target="'#staticBackdrop' + index">Lihat Detail</button>
                </div>
              </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" :id="'staticBackdrop' + index" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
              <div class="modal-dialog modal-dialog-centered modal-xl">
                <div class="modal-content">
                  <div class="modal-body">
                    <div class="text-center">
                      <h5 class="card-title h3 mb-0">{{item.users_name}}</h5>
                      <p class="mb-0">Balance</p>
                      <p class="lead text-danger mt-0 mb-0">Rp.{{formatNumber(item.balance)}}</p>
                    </div>
                    <p class="lead text-center mt-3">Riwayat Penarikan</p>
                    <table class="table table-striped table-history mb-0">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Nominal</th>
                          <th scope="col">Status</th>
                          <th scope="col">Tanggal</th>
                          <th scope="col">Aksi</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(data, idx) in item.withdrawals" :key="idx">
                          <th scope="row">{{idx+1}}</th>
                          <td>Rp.{{formatNumber(data.withdrawals_nominal)}}</td>
                          <td class="text-capitalize">{{data.withdrawals_status}}</td>
                          <td>{{momentDate(data.withdrawals_updated_at)}}</td>
                          <td>
                            <button 
                              class="btn btn-sm btn-primary" 
                              :disabled="data.withdrawals_status === 'success'"
                              @click="data.withdrawals_status === 'pending' && setTransactionSuccess(data.withdrawals_id)"
                            >
                              Success
                            </button>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <center class="p-2">
                    <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                  </center>
                </div>
              </div>
            </div>
            <!-- Modal -->

          </div><!--/col-xl-3-->

        </div><!-- /row -->

        <div class="row justify-content-md-center mt-5" v-else>
          <div class="col-12">
            <div class="card shadow-none text-center pt-5 pb-5" style="background-color:transparent;">
              <div class="card-body mt-5 text-gray">
                <i class="fal fa-box-open fa-3x mt-5"></i>
                <p class="font-weight-bold mt-1">Data tidak tersedia.</p>
              </div>
            </div>
          </div>
        </div><!-- /row -->


        <pagination
          :limit="2"
          class="mt-3"
          :align="'center'"
          :data="transactionData"
          @pagination-change-page="getTransaction"
        ></pagination>

      </div><!-- /header-body -->

    </div><!-- /container-fluid -->
  </div>
</template>

<script>
import moment from 'moment'
export default{
  props:['storage'],
  data(){
    return{
      transactionData: {},
    }
  },
  methods: {
    getTransaction(page = 1){
      axios.get(`/admin/get-transaction-penjual?page=${page}`)
        .then(res => {
          let tmp_transaction = []
          for(let [key, val] of Object.entries(res.data.data)){
            let tmp_dis = {
              ...val,
              district: []
            }
            axios.get(`/account/get-city-district/${val.users_shipping_subdistrict_id}`)
              .then(res => {
                tmp_dis.district.push(res.data[0].shipping_provinces_name +', '+res.data[0].shipping_cities_type +' '+ res.data[0].shipping_cities_name +', '+ res.data[0].shipping_subdistricts_name)
              })

            tmp_transaction.push(tmp_dis)
          }

          let data = {
            ...res.data,
            data: tmp_transaction
          }

          this.transactionData = data
        })
    },
    setTransactionSuccess(id){
      axios.put(`/admin/set-withdrawals-success/${id}`)
        .then(res => {
          console.log(res.data)
          this.getTransaction()
          this.$toast.success(res.data.status);
        })
        .catch(err => {
          console.log(err.response)
          this.$toast.error(err.response);
        })
    },
    formatNumber(val) {
      return new Intl.NumberFormat(['ban', 'id']).format(val);
    },
    momentDate(val) {
      moment.locale('id')
      return moment(val).format('lll')
    },
  },
  mounted() {
    this.getTransaction()
  }
}
</script>
<style>
.table-history.table td, .table-history.table th{
  padding: .5rem;
  vertical-align: initial;
}
.card .table-fit.table td, .card .table-fit.table th {
  padding-right: .5rem;
  padding-left: 0rem;
}
.img-admin-trx{
  height: 230px;
  width: auto;
  object-fit: cover;
}
</style>
