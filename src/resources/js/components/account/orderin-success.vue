<template>
  <div>
    <div class="card mt-3 overflow-hidden" v-for="(order, index) in orderData.data" :key="index">
      <div class="corner-ribbon top-right" v-if="order.items[0].orders_drawn[0] !== 'no'">Ditarik</div>
      <div class="card-body">

        <div class="row">
          <div class="col col-lg-8">
            <div class="payment-card-row">
              <div class="payment-card-total-text">Total:</div>
              <div class="payment-card-total-amount"><b>Rp.{{formatNumber(sumTotalArr([...order.final_cost, order.items[0].shipping_cost]))}}</b></div>
              <div class="payment-card-total-text">Tanggal Pembelian</div>
              <div class="payment-card-transaction-date"><b>{{momentPaymentDate(order.orders_created_at)}}</b></div>
            </div>
            <div class="payment-card-row text-truncate">
              <div class="payment-card-column">
                <div class="payment-information-left">Nomor Invoice</div>
                <div class="payment-information-left">Metode Pembayaran</div>
                <div class="payment-information-left">Nomor Virtual Account</div>
              </div>
              <div class="payment-card-column">
                <div class="payment-information-center">:</div>
                <div class="payment-information-center">:</div>
                <div class="payment-information-center">:</div>
              </div>
              <div class="payment-card-column text-truncate">
                <div class="payment-information-right text-truncate">{{order.orders_order_id}}</div>
                <div class="payment-information-right text-truncate text-capitalize">{{order.orders_bank}}</div>
                <div class="payment-information-right">{{order.orders_va_number}}</div>
              </div>
            </div>
          </Col>
          </div><!--/col-->

          <div class="col col-4 text-center align-self-center">
            <img class="payment-method-logo mb-3" :src="storage + '/' + order.orders_bank +'.png'" width="114" />
            <button 
              class="btn btn-block btn-primary btn-sm"
              data-toggle="collapse"
              :data-target="'#collapseExample2' + order.orders_id"
              aria-expanded="false"
            >
              Lihat Rincian
            </button>
          </div><!--/col-->
        </div><!--/row-->

        <div class="collapse mt-2 border-top" :id="'collapseExample2' + order.orders_id">
          <div class="card-footer bg-white px-0 pb-0" v-for="item in order.items" :key="item.penjual_id">
            <div class="row align-items-center border-bottom mb-3">
              <div class="col fs-12 col-12 border-bottom">
                <p class="font-weight-bold mb-0">{{order.users_fullname}}</p>
                <p class="text-secondary mb-0">{{order.users_email}}</p>
                <p class="text-secondary mb-0">{{order.users_phone}}</p>
                <p class="text-secondary mb-2">{{order.users_address}}, {{order.district[0]}}, {{order.users_code_pos}}</p>
              </div>
            </div>
            <div class="media mb-3" v-for="(product, idx) in item.order_items" :key="idx">
              <img :src="productphoto + '/' + product.product_images" class="mr-3" width="70" alt="eruland">
              <div class="media-body">
                <div class="d-flex justify-content-between">
                  <div class="d-flex flex-column align-content-around">
                    <a class="h5 mb-1 text-decoration-none text-reset hover-pointer">
                      {{product.products_name}}
                    </a>
                    <p class="mb-1 text-muted">
                      Rp.{{formatNumber(product.order_items_price)}}/{{product.products_weight}}gr
                    </p>
                    <p class="mb-0 text-muted">
                      Jumlah {{product.order_items_qty}}
                    </p>
                  </div>
                </div>
              </div>
            </div><!--/media-->

            <div class="col col-12 px-0">
              <div class="detail-box-content pt-0">
                <div class="detail-item mb-2">
                  <div class="detail-item-left">
                    <div class="detail-item-name">
                      <span>{{item.shipping}}</span>
                    </div>
                  </div>
                  <div class="detail-item-price">Rp.{{formatNumber(item.shipping_cost)}}</div>
                </div>
                <div class="detail-item mb-2 mt-1">
                  <div class="input-group mb-0">
                    <input 
                      type="text" 
                      class="form-control" 
                      placeholder="Masukkan resi" 
                      v-model="item.no_resi"
                    >
                    <div class="input-group-append">
                      <button class="btn btn-outline-secondary" type="button" @click="setResi(order.orders_id, item.no_resi)">Simpan</button>
                    </div>
                  </div>
                </div>
              </div>
            </div><!--/col-->
          </div><!--/card-footer-->

          <div class="bg-white border-top-5">
            <div class="detail-box-content">
              <div class="detail-item">
                <div class="detail-item-left">
                  <div class="font-weight-bold">Total Harga</div>
                </div>
                <div class="font-weight-bold">Rp.{{formatNumber(sumTotalArr(order.final_cost))}}</div>
              </div>
              <div class="detail-item">
                <div class="detail-item-left">
                  <div class="detail-item-name">Total Pengiriman</div>
                </div>
                <div class="detail-item-price">Rp.{{formatNumber(order.items[0].shipping_cost)}}</div>
              </div>
            </div>

            <div class="detail-box-content">
              <div class="detail-item">
                <div class="detail-item-left">
                  <div class="fs-14 text-black font-weight-bold">Total Bayar</div>
                  <div class="detail-item-info detail-main-gateway text-capitalize">{{order.orders_bank}}</div>
                </div>
                <div class="detail__tagihan-amount align-self-center">
                  Rp.{{formatNumber(sumTotalArr([...order.final_cost, order.items[0].shipping_cost]))}}
                </div>
              </div>
            </div>
          </div>

        </div><!--/collapse-->

      </div><!--/card-body-->
    </div><!--/card-->
  </div>
</template>

<script>
import moment from "moment";

export default{
  props:['orderData','onCancelOrder', 'storage', 'productphoto'],
  data(){
    return{
      noResi: "",
    }
  },
  methods: {
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
    },
    setResi(id,resi){
      let data = { no_resi: resi }
      axios.put(`/account/set-resi/${id}`, data)
        .then(res => {
          this.$toast.success(res.data.status)
          this.noRes = ""
        })
        .catch(err => {
          this.$toast.error("Terjadi kesalahan")
        })
    }
  }
}
</script>

<style>
.corner-ribbon{
  width: 164px;
  background: #e43;
  position: absolute;
  top: 25px;
  left: -50px;
  text-align: center;
  line-height: 38px;
  letter-spacing: 1px;
  color: #f0f0f0;
  z-index: 1;
  transform: rotate(-45deg);
  -webkit-transform: rotate(-45deg);
}

/* Custom styles */

.corner-ribbon.top-right{
  top: 15px;
  right: -50px;
  left: auto;
  transform: rotate(45deg);
  -webkit-transform: rotate(45deg);
}
</style>
