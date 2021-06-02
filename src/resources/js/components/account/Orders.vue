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
              @click="selected = 'pending'"
            >
              Belum Bayar
            </a>
            <a 
              class="nav-link" 
              id="nav-home-tab" 
              data-toggle="tab" 
              href="#nav-home" 
              role="tab" 
              aria-controls="nav-home" 
              aria-selected="true"
              @click="selected = 'success'"
            >
              Selesai
            </a>
            <a 
              class="nav-link" 
              id="nav-profile-tab" 
              data-toggle="tab" 
              href="#nav-profile" 
              role="tab" 
              aria-controls="nav-profile" 
              aria-selected="false"
              @click="selected = 'expired'"
            >
              Kadaluarsa
            </a>
            <a 
              class="nav-link" 
              id="nav-canceled-tab" 
              data-toggle="tab" 
              href="#nav-canceled" 
              role="tab" 
              aria-controls="nav-canceled" 
              aria-selected="false"
              @click="selected = 'failed'"
            >
              Dibatalkan
            </a>
          </div>
        </nav>

        <div class="tab-content" id="nav-tabContent">

          <div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">
            <div v-if="pendingOrderData && pendingOrderData.data && pendingOrderData.data.length">
              <PendingComponent 
                :storage="storage"
                :orderData="pendingOrderData"
                :onCancelOrder="onCancelOrder"
                :productphoto="product"
              >
              </PendingComponent>
            </div>
            
            <div class="text-center pt-5 text-muted" v-else>
              <i class="fad fa-boxes-alt fa-4x"></i>
              <p class="mt-2 mb-0">Tidak ada data</p>
            </div>

            <div class="mt-4">
              <pagination
                :data="pendingOrderData"
                :limit="2"
                :align="'center'"
                @pagination-change-page="getOrders"
              ></pagination>
            </div>

          </div><!--/tab-pane-->

          <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">

            <div v-if="successOrderData && successOrderData.data && successOrderData.data.length">
              <SuccessComponent 
                :orderData="successOrderData" 
                :storage="storage"
                :productphoto="product"
              >
              </SuccessComponent>
            </div>

            <div class="text-center pt-5 text-muted" v-else>
              <i class="fad fa-boxes-alt fa-4x"></i>
              <p class="mt-2 mb-0">Tidak ada data</p>
            </div>

            <div class="mt-4">
              <pagination
                :data="successOrderData"
                :limit="2"
                :align="'center'"
                @pagination-change-page="getOrders"
              ></pagination>
            </div>

          </div><!--/tab-pane-->

          <div class="tab-pane fade" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab">
            <div v-if="expiredOrderData && expiredOrderData.data && expiredOrderData.data.length">
              <ExpiredComponent 
                :orderData="expiredOrderData" 
                :storage="storage"
                :productphoto="product"
              >
              </ExpiredComponent>
            </div>

            <div class="text-center pt-5 text-muted" v-else>
              <i class="fad fa-boxes-alt fa-4x"></i>
              <p class="mt-2 mb-0">Tidak ada data</p>
            </div>

            <div class="mt-4">
              <pagination
                :data="expiredOrderData"
                :limit="2"
                :align="'center'"
                @pagination-change-page="getOrders"
              ></pagination>
            </div>

          </div><!--/tab-pane-->


          <div class="tab-pane fade" id="nav-canceled" role="tabpanel" aria-labelledby="nav-canceled-tab">
            <div v-if="canceledOrderData && canceledOrderData.data && canceledOrderData.data.length">
              <CanceledComponent 
                :orderData="canceledOrderData" 
                :storage="storage"
                :productphoto="product"
              >
              </CanceledComponent>
            </div>

            <div class="text-center pt-5 text-muted" v-else>
              <i class="fad fa-boxes-alt fa-4x"></i>
              <p class="mt-2 mb-0">Tidak ada data</p>
            </div>

            <div class="mt-4">
              <pagination
                :data="canceledOrderData"
                :limit="2"
                :align="'center'"
                @pagination-change-page="getOrders"
              ></pagination>
            </div>

          </div><!--/tab-pane-->

        </div>

      </div><!--/card-body-->

    </div><!--/card-->
  </div>
</template>

<script>
import moment from "moment";
import PendingComponent from "./order-pending.vue"
import SuccessComponent from "./order-success.vue"
import ExpiredComponent from "./order-expired.vue"
import CanceledComponent from "./order-canceled.vue"

export default {
  props:['storage', 'product'],
  data(){
    return{
      selected: "pending",
      pendingOrderData: {},
      successOrderData: {},
      expiredOrderData: {},
      canceledOrderData: {},
    }
  },
  methods: {
    getOrders(page = 1, status = this.selected){
      axios.get(`/account/get-order-user?status=${status}&page=${page}`).then(res => {
        let tmp_order = []
        for(let [key, val] of Object.entries(res.data.data)){
          let data = {
            ...val,
            items: []
          }
          for(let item of val.order_items){
            data.items.push({ penjual_id: item.order_items_penjual_id, shipping: "", shipping_cost: 0, order_items: [], subtotal: [] })
          }

          data.items = _.uniqBy(data.items, 'penjual_id')

          for(let item of val.order_items){
            for(let id of data.items){
              let tmp = val.order_items
              tmp = _.uniqBy(tmp, 'penjual_id')
              if(item.order_items_penjual_id === id.penjual_id){
                id.subtotal.push(item.order_items_price*item.order_items_qty)
                id.order_items.push(item)
                id.no_resi = item.order_items_no_resi
                id.shipping_cost = item.order_items_cost_shipping
                id.shipping = item.order_items_code_shipping.toUpperCase() + ' - ' + item.order_items_service_shipping + ' (Estimasi tiba ' + item.order_items_etd_shipping + ' hari)'
              }
            }
          }

          tmp_order.push(data)
        }

        tmp_order = tmp_order.map(x => {
          delete x.order_items
          return x
        })

        if(this.selected === "pending") {
          this.pendingOrderData = {
            ...res.data,
            data: tmp_order
          }
        }
        if(this.selected === "success") {
          this.successOrderData = {
            ...res.data,
            data: tmp_order
          }
        }
        if(this.selected === "expired") {
          this.expiredOrderData = {
            ...res.data,
            data: tmp_order
          }
        }
        if(this.selected === "failed") {
          this.canceledOrderData = {
            ...res.data,
            data: tmp_order
          }
        }
      })
    },
    onCancelOrder(id) {
      axios.delete(`/payments/cancel-order/${id}`)
        .then(res => {
          this.$toast.success(res.data.status);
          setTimeout(() => {
            this.getOrders(_, this.selected)
            location.reload()
          }, 2000)
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
  },
  watch: {
    selected(val){
      this.getOrders(_, this.selected)
    }
  },
  components: {
    PendingComponent,
    SuccessComponent,
    ExpiredComponent,
    CanceledComponent,
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
