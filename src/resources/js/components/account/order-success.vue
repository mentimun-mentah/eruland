<template>
  <div>
    <div class="card mt-3" v-for="(order, index) in orderData.data" :key="index">
      <div class="card-body">

        <div class="row">
          <div class="col col-lg-8">
            <div class="payment-card-row">
              <div class="payment-card-total-text">Total:</div>
              <div class="payment-card-total-amount"><b>Rp.{{formatNumber(order.orders_gross_amount)}}</b></div>
              <div class="payment-card-total-text">Tanggal Pembelian</div>
              <div class="payment-card-transaction-date"><b>{{momentPaymentDate(order.orders_created_at)}}</b></div>
            </div>
            <div class="payment-card-row">
              <div class="alert alert-secondary w-100 alert-payment mb-0 p-2" role="alert">
                <i class="far fa-lightbulb-on mr-2"></i> Pesanan telah selesai
              </div>
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
          <div class="card-footer border-bottom bg-white px-0 pb-0" v-for="item in order.items" :key="item.penjual_id">
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
                      No.Resi
                    </div>
                  </div>
                  <div class="detail-item-price">{{item.no_resi ? item.no_resi  : '-'}}</div>
                </div>
                <div class="detail-item mb-2 mt-1">
                  <div class="detail-item-left">
                    <div class="font-weight-bold">{{item.shipping}}</div>
                  </div>
                  <div class="font-weight-bold">Rp.{{formatNumber(item.shipping_cost)}}</div>
                </div>
                <div class="detail-item mb-2 mt-1">
                  <div class="detail-item-left">
                    <div class="font-weight-bold">Subtotal</div>
                  </div>
                  <div class="font-weight-bold">Rp.{{formatNumber(sumTotalArr(item.subtotal) + item.shipping_cost)}}</div>
                </div>
              </div>
            </div><!--/col-->
          </div><!--/card-footer-->

          <div class="bg-white border-top-5 mt-3">
            <div class="detail-box-content">
              <div class="detail-item">
                <div class="detail-item-left">
                  <div class="font-weight-bold">Total Harga</div>
                </div>
                <div class="font-weight-bold">Rp.{{formatNumber(order.orders_gross_amount - order.orders_total_cost_shipping)}}</div>
              </div>
              <div class="detail-item">
                <div class="detail-item-left">
                  <div class="detail-item-name">Total Pengiriman</div>
                </div>
                <div class="detail-item-price">Rp.{{formatNumber(order.orders_total_cost_shipping)}}</div>
              </div>
            </div>

            <div class="detail-box-content">
              <div class="detail-item">
                <div class="detail-item-left">
                  <div class="fs-14 text-black font-weight-bold">Total Bayar</div>
                  <div class="detail-item-info detail-main-gateway text-capitalize">{{order.orders_bank}}</div>
                </div>
                <div class="detail__tagihan-amount align-self-center">
                  Rp.{{formatNumber(order.orders_gross_amount)}}
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
  props:['orderData', 'storage', 'productphoto'],
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
    }
  }
}
</script>
