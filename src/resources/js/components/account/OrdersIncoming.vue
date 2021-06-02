<template>
  <div>
    <Toasts></Toasts>
    <div class="card-container card">
      <div class="card-body">

        <nav>
          <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <a 
              class="nav-link active" 
              id="nav-contact-tab" 
              data-toggle="tab" 
              href="#nav-contact" 
              role="tab" 
              aria-controls="nav-contact" 
              aria-selected="false"
              @click="selected = 'order'"
            >
              Pesanan
            </a>
            <a 
              class="nav-link" 
              id="nav-profile-tab" 
              data-toggle="tab" 
              href="#nav-profile" 
              role="tab" 
              aria-controls="nav-profile" 
              aria-selected="false"
              @click="selected = 'balance'"
            >
              Balance
            </a>
          </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">

          <div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <div v-if="orderData && orderData.data && orderData.data.length">
              <OrderinComponent
                :storage="storage"
                :orderData="orderData"
                :productphoto="product"
              >
              </OrderinComponent>
            </div>

            <div class="text-center pt-5 text-muted" v-else>
              <i class="fad fa-boxes-alt fa-4x"></i>
              <p class="mt-2 mb-0">Tidak ada data</p>
            </div>

            <div class="mt-4">
              <pagination
                :data="orderData"
                :limit="2"
                :align="'center'"
                @pagination-change-page="getOrders"
              ></pagination>
            </div>

          </div><!--/tab-pane-->

          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div class="pt-4 text-center">
              <p class="lead">Penghasilan kamu: <span class="h4 text-danger">Rp.{{formatNumber(balance)}}</span></p>
              <button type="button" class="btn btn-outline-dark px-5 mb-3" @click="drawnMyBalance()">Tarik</button>
              <p>
                <small class="text-muted">
                  NB: Minimal penarikan Rp.100.000, penarikan membutuhkan waktu 1-7 hari, penarikan dikenakan biaya Rp.10.000
                </small>
              </p>
            </div>

            <div class="mt-3 border-top-5">
              <p class="lead text-center mt-3">Riwayat Penarikan</p>
              <table class="table table-striped mb-0">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nominal</th>
                    <th scope="col">Status</th>
                    <th scope="col">Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in withdrawalHistory" :key="index">
                    <th scope="row">{{index+1}}</th>
                    <td>Rp.{{formatNumber(item.nominal)}}</td>
                    <td class="text-capitalize">{{item.status}}</td>
                    <td>{{momentExpiredDate(item.updated_at)}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div><!--/tab-pane-->

        </div>
      </div><!--/card-body-->
    </div><!--/card-->
  </div>
</template>

<script>
import moment from "moment";
import OrderinComponent from "./orderin-success.vue"

export default {
  props:['storage', 'product'],
  data(){
    return{
      selected: "order",
      orderData: {},
      balance: 0,
      withdrawalHistory: [],
    }
  },
  methods: {
    getOrders(page = 1){
      axios.get(`/account/get-order-penjual?page=${page}`).then(res => {
        let tmp_order = []
        let tmp_district = []
        for(let [key, val] of Object.entries(res.data.data)){
          let data = {
            ...val,
            items: [],
            total_cost: [],
            final_cost: []
          }
          for(let item of val.order_items){
            data.final_cost.push(item.order_items_price*item.order_items_qty)
            data.total_cost = [val.order_items[0].order_items_cost_shipping]
            data.items.push({ penjual_id: item.order_items_penjual_id, shipping: "", shipping_cost: 0, order_items: [], orders_drawn: [] })
          }

          data.items = _.uniqBy(data.items, 'penjual_id')

          for(let item of val.order_items){
            for(let id of data.items){
              let tmp = val.order_items
              tmp = _.uniqBy(tmp, 'penjual_id')
              if(item.order_items_penjual_id === id.penjual_id){
                id.order_items.push(item)
                id.orders_drawn.push(item.order_items_drawn)
                id.no_resi = item.order_items_no_resi
                id.shipping_cost = item.order_items_cost_shipping
                id.shipping = item.order_items_code_shipping.toUpperCase() + ' - ' + item.order_items_service_shipping + ' (Estimasi tiba ' + item.order_items_etd_shipping + ' hari)'
              }
            }
          }

          tmp_order.push(data)
          axios.get(`/account/get-city-district/${val.users_shipping_subdistrict_id}`)
            .then(res => {
              tmp_district.push(res.data[0].shipping_provinces_name +', '+res.data[0].shipping_cities_type +' '+ res.data[0].shipping_cities_name +', '+ res.data[0].shipping_subdistricts_name)
            })
        }

        tmp_order = tmp_order.map(x => {
          x['district'] = tmp_district
          delete x.order_items
          return x
        })


        this.orderData = {
          ...res.data,
          data: tmp_order
        }

      })
    },
    getMyBalance(){
      axios.get('/account/my-balance')
        .then(res => {
          this.balance = res.data
        })
    },
    drawnMyBalance(){
      axios.get('/account/drawn-my-balance')
        .then(res => {
          this.$toast.success(res.data.status);
          this.getMyBalance()
          this.getMyWithdrawals()
        })
        .catch(err => {
          this.$toast.error(err.response.data.errors.balance[0]);
        })
    },
    getMyWithdrawals(){
      axios.get('/account/my-withdrawals')
        .then(res => {
          this.withdrawalHistory = res.data
        })
    },
    momentPaymentDate(val) {
      moment.locale('id')
      return moment(val).format('ll')
    },
    momentExpiredDate(val) {
      moment.locale('id')
      return moment(val).add(1, 'days').format('lll')
    },
    formatNumber(val) {
      return new Intl.NumberFormat(['ban', 'id']).format(val);
    },
    sumTotalArr(arr) {
      return arr.reduce((a, b) => a + b, 0)
    }
  },
  mounted() {
    this.getOrders()
    this.getMyBalance()
    this.getMyWithdrawals()
  },
  watch: {
    selected(val){
      if(val === "order") this.getOrders()
      if(val === "balance") {
        this.getMyBalance()
        this.getMyWithdrawals()
      }
    }
  },
  components: {
    OrderinComponent
  }
}
</script>

<style>
.order-list:not(:last-of-type){
  margin-bottom: 10px; 
}
.payment-card-row:not(:last-of-type){
  padding-bottom: 10px;
}
.payment-card-row{
  display: flex;
  flex-direction: row;
  align-items: center;
}
.payment-card-column{
  display: flex;
  flex-direction: column;
}
.payment-card-total-text{
  color: rgba(0,0,0,.54);
  text-align: left;
  margin-right: 8px;
}
.payment-card-total-amount{
  color: #d63031;
  font-weight: 700;
  margin-right: 8px;
}
.payment-card-transaction-date{
  font-weight: 700;
  color: rgba(0,0,0,.80);
  text-align: left;
  margin-right: 8px;
}
.payment-information-left{
  color: rgba(0,0,0,.54);
  text-align: left;
}
.payment-information-center{
  color: rgba(0,0,0,.54);
  padding: 0 8px;
}
.payment-information-right{
  font-weight: 700;
  color: rgba(0,0,0,.54);
  text-align: left;
}
.payment-information-mobile > .title-payment-information-mobile{
  margin-bottom: 0px;
  color: rgba(0,0,0,.54);
}
.payment-information-mobile > .total-amount-mobile{
  color: #d63031;
  font-weight: 700;
}
.payment-information-mobile > .data-payment-information-mobile{
  color: rgba(0,0,0,.7);
}

.detail-box-content:not(:last-of-type){
  border-bottom: 1px dashed rgba(0,0,0,.125);
}
.detail-box-content{
  padding-top: 10px;
  padding-bottom: 10px;
}
.detail-item{
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  line-height: 18px;
}
.detail-item-name{
  color: rgba(0,0,0,.54);
}
.detail-item:not(:first-of-type){
  margin-top: 8px;
}
.detail-item-left{
  max-width: 65%;
}
.detail-item .detail-item-price{
  color: rgba(0,0,0,.6);
  font-weight: 700;
  max-width: 35%;
}
.detail-item .detail-item-info{
  color: rgba(0,0,0,.38);
  max-width: 100%;
  white-space: nowrap;
  text-overflow: ellipsis;
  overflow: hidden;
}
.detail__tagihan-amount{
  display: flex;
  justify-content: flex-start;
  color: #d63031;
  font-weight: 800;
}

.border-top-5 {
  border-top: 5px solid rgb(243,244,245);
}
.nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
  background-color: transparent;
  border-color: #dee2e6 #dee2e6 #ffffff;
}
</style>
