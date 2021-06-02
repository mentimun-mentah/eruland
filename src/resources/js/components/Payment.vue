<template>
  <div>
    <Toasts></Toasts>
    <div class="container">
      <div class="row">
        <div class="col-lg-7">
          <div class="card shadow-sm">
            <div class="card-body">

              <div class="form-group">
                <label for="inputAddress">Nama Lengkap</label>
                <input 
                  type="text" 
                  class="form-control" 
                  placeholder="Nama Lengkap" 
                  v-model="user.fullname"
                  :class="[errors.fullname ? 'is-invalid' : '']"
                />
                <small class="text-danger" v-if="errors.fullname">
                  {{ errors.fullname[0] }}
                </small>
              </div><!--/form-group-->

              <div class="form-row">
                <div class="form-group col-md-6">
                  <label>Email Pembeli</label>
                  <input disabled type="email" class="form-control" placeholder="Email" v-model="user.email">
                </div>
                <div class="form-group col-md-6">
                  <label for="inputPassword4">Nomor Telepon</label>
                  <input 
                    type="text" 
                    class="form-control" 
                    placeholder="Nomor Telepon" 
                    v-model="user.phone"
                    :class="[errors.phone ? 'is-invalid' : '']"
                  >
                  <small class="text-danger" v-if="errors.phone">
                    {{ errors.phone[0] }}
                  </small>
                </div>
              </div><!--/form-row-->

              <div class="form-row">
                <div class="form-group col-md-8">
                  <label>Kota / Distrik</label>
                  <v-select
                    label="name"
                    :filterable="false"
                    :options="options"
                    @search="onSearch"
                    v-model="shipping_district"
                  >
                    <template slot="no-options">
                      Data tidak ditemukan...
                    </template>
                    <template slot="option" slot-scope="option">
                      <div class="d-center">
                        {{ option.shipping_provinces_name }}, {{option.shipping_cities_type}} {{option.shipping_cities_name}}, {{option.shipping_subdistricts_name}}
                      </div>
                    </template>
                    <template 
                      slot="selected-option" 
                      slot-scope="option" 
                      v-if="option.shipping_provinces_name && option.shipping_cities_type && option.shipping_cities_name && option.shipping_subdistricts_name"
                    >
                      <div class="selected d-center">
                        {{ option.shipping_provinces_name }}, {{option.shipping_cities_type}} {{option.shipping_cities_name}}, {{option.shipping_subdistricts_name}}
                      </div>
                    </template>
                  </v-select>
                </div>
                <div class="form-group col-md-4">
                  <label>Kode Pos</label>
                  <input 
                    type="number" 
                    class="form-control" 
                    placeholder="Kode Pos" 
                    v-model="user.code_pos"
                    :class="[errors.code_pos ? 'is-invalid' : '']"
                  >
                  <small class="text-danger" v-if="errors.code_pos">
                    {{ errors.code_pos[0] }}
                  </small>
                </div>
              </div><!--/form-row-->

              <div class="form-group">
                <label>Alamat</label>
                <textarea
                  class="form-control"
                  placeholder="Alamat"
                  rows="2"
                  v-model="user.address"
                  :class="[errors.address ? 'is-invalid' : '']"
                ></textarea>
                <small class="text-danger" v-if="errors.address">
                  {{ errors.address[0] }}
                </small>
              </div><!--/form-group-->

            </div><!--/card-body-->
          </div><!--/card-->

          <br /> <br />

          <div class="mb-3 card" v-for="cart in carts" :key="cart.user_id">
            <div class="card-body">
              <div class="media mb-3" v-for="item in cart.items" :key="item.id">
                <div class="media-body">
                  <div class="row">
                    <div class="col-md-2 col-lg-2 col-xl-2">
                      <div class="rounded mb-3 mb-md-0">
                        <img
                          class="img-fluid w-100 image-product-cart image-product-payment"
                          :src="storage + '/' + item.options.image"
                          alt="Sample"
                        >
                      </div>
                    </div>
                    <div class="col-md-10 col-lg-10 col-xl-10">
                      <div class="d-flex justify-content-between">
                        <div>
                          <a class="h5 text-decoration-none text-reset hover-pointer">
                            {{item.name}}
                          </a>
                          <p class="mb-0 text-muted">
                            Rp.{{formatNumber(item.price)}}/{{item.options.weight}}gr
                          </p>
                          <p class="mb-2 text-muted">
                            Jumlah {{item.qty}}
                          </p>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div><!--/media-->

              <select class="selectpicker w-100 d-block btn btn-light" style="display: block!important;"
                v-model="cart.ongkir" @change="changeOngkir">
                <option 
                  v-for="shipping in cart.shipping_list" 
                  :value="shipping"
                  v-if="shipping.code && shipping.service && shipping.etd && shipping.value"
                >
                  <div class="d-flex justify-content-between w-100">
                    <div class="align-self-center" style="width: calc(100%/2);">
                      <p class="mb-0 text-uppercase text-truncate">
                      <span class="text-uppercase va-super">{{ shipping.code || "-"}} {{ shipping.service }}</span>
                      </p>
                    </div>
                    <div class="align-self-center">
                      <p class="mb-0 text-truncate"> {{shipping.etd && shipping.etd.split(" ")[0] || 0}} hari </p>
                    </div>
                    <div class="align-self-center mr-2">
                      <p class="mb-0 text-truncate"> Rp.{{formatNumber(shipping.value)}}</p>
                    </div>
                  </div>
                </option>
              </select>
            </div><!--/card-body-->
          </div><!--/card-->

        </div><!--/col-lg-7-->

        <div class="col-lg-5">
          <div class="card card-sticky">
            <div class="card-body">
              <p class="subtitle h5 font-weight-bold">
                <i class="far fa-archive"></i>&nbsp; Ringkasan Belanja
              </p>
              <hr class="mt-3" />

              <div v-for="cart in carts" :key="cart.user_id">
                <div v-for="item in cart.items" :key="item.id">
                  <p class="mb-1">
                    {{item.name}}<span class="text-muted"> ({{item.qty}})</span>
                    <span class="float-right">Rp.{{formatNumber(item.subtotal)}}</span>
                  </p>
                </div>
              </div>

              <br />

              <p class="text-gray h6">
                Total Pengiriman
                <span class="float-right text-dark font-weight-bold h6"><b>Rp.{{total_cost_shipping ? formatNumber(total_cost_shipping) : 0}}</b></span>
              </p>

              <hr />

              <div>
                <div class="float-left text-gray h5">Total</div>
                <div class="float-right text-dark font-weight-bold h5">
                  <b>Rp.{{(total + total_cost_shipping) ? formatNumber(total + total_cost_shipping) : 0}}</b>
                </div>
              </div>
              <br /> <br />

              <button class="btn btn-dark btn-block" @click="payItem">Bayar</button>

            </div><!--/card-body-->
          </div><!--/card-->
        </div><!--/col-lg-5-->

      </div><!--/row-->
    </div><!--/container-->
  </div>
</template>

<script>
import _ from 'lodash'
import isIn from 'validator/lib/isIn'
import "vue-select/dist/vue-select.css";

export default{
  props: ['storage','home'],
  data() {
    return {
      user: null,
      total: 0,
      total_cost_shipping: 0,
      carts: [],
      shipping_district: {},
      options: [],
      tmp_cost_shipping: [],
      errors: []
    }
  },
  methods: {
    onSearch(search, loading) {
      if (search.length > 2) {
        loading(true);
        axios.get(`/account/get-city-district?q=${search}`)
          .then(res => {
            if (res.data.length == 0) {
              loading(false);
              this.options = [];
            }
            this.options = res.data;
            loading(false);
          });
      } else {
        loading(false);
        this.options = [];
      }
    },
    getCart(){
      axios.get('/carts/get-my-cart').then(res => {
        let tmp_cart = []
        for(let [key, val] of Object.entries(res.data)){
          tmp_cart.push(val)
        }
        let allCarts = tmp_cart.map(x => {
          return { 
            user_id: x.options.user_id,
            shipping_subdistrict_id: x.options.shipping_subdistrict_id,
            ongkir: {},
            items: [],
            total_weight: 0
          }
        })

        allCarts = _.uniqBy(allCarts, 'user_id')

        for(let val of tmp_cart) {
          for(let uid of allCarts) {
            if(val.options.user_id === uid.user_id) {
              uid.items.push(val)
              uid.total_weight += (val.qty * val.options.weight)
            }
          }
        }

        this.carts = allCarts
      })

      axios.get('/carts/get-total-cart').then(res => {
        this.total = Number(res.data.replace(",", ""))
      })
    },
    getUser(){
      axios.get('/user').then(res => {
        this.user = res.data;          
        if(this.user.shipping_subdistrict_id){
          setTimeout(() => {
            axios.get(`/account/get-city-district/${this.user.shipping_subdistrict_id}`).then(res => {
              this.shipping_district = res.data[0]
            })
          },500)
        }
      })
    },
    setShippingCost(){
      this.carts.map((x, i) => {
        let query = {
          origin: x.shipping_subdistrict_id,
          originType: 'subdistrict',
          destination: this.user.shipping_subdistrict_id,
          destinationType: 'subdistrict',
          weight: x.total_weight,
          courier: 'jne:pos:tiki:lion'
        }

        axios.post(`/products/get-cost`,query).then(res => {
          this.tmp_cost_shipping = {user_id:x.user_id,data:res.data}
        })
      })
    },
    payItem(){
      if(
        this.user.fullname &&
        this.user.phone &&
        this.user.shipping_subdistrict_id &&
        this.user.code_pos &&
        this.user.address && this.getOngkirValidation()
      ){
        let data = {
          fullname: this.user.fullname,
          phone: this.user.phone,
          shipping_subdistrict_id: this.user.shipping_subdistrict_id,
          code_pos: this.user.code_pos,
          address: this.user.address,
          total_cost_shipping: this.total_cost_shipping
        }

        axios.post('/payments/get-snap-token',data).then(res => {
          let order = {
            order_id: null,
            total_cost_shipping: this.total_cost_shipping,
            gross_amount: null,
            payment_type: null,
            transaction_status: null,
            bank: null,
            va_number: null,
            pdf_url: null,
          };

          let items = []
          for(let cart of this.carts){
            for(let item of cart.items){
              let cart_data = {
                qty: item.qty,
                price: item.price,
                penjual_id: item.options.user_id,
                product_id: item.id,
                code_shipping: cart.ongkir.code,
                service_shipping: cart.ongkir.service,
                cost_shipping: cart.ongkir.value,
                etd_shipping: cart.ongkir.etd
              }
                items.push(cart_data)
            }
          }

          snap.pay(res.data.snap_token, {
            onSuccess: result => {
              console.log("success",result)
            },
            onPending: result => {
              if(result.bill_key){
                order.bank = 'mandiri'
                order.va_number = result.biller_code + result.bill_key
              }else if(result.permata_va_number){
                order.bank = 'permata'
                order.va_number = result.permata_va_number
              }else{
                order.bank = result.va_numbers[0].bank
                order.va_number = result.va_numbers[0].va_number
              }
              order.gross_amount = Number(result.gross_amount)
              order.order_id = result.order_id
              order.payment_type = result.payment_type
              order.pdf_url = result.pdf_url
              order.transaction_status = result.transaction_status

              axios.post('/payments/save-order',{order:order,items:items})
                .then(res => {
                  location.replace(this.home + '/account/orders')
                })

            },
            onError: function (result) {
              location.reload();
            }
          })
        }).catch(err => {
          this.errors = err.response.data.errors
        })

      }else this.$toast.error('Upss lengkapi data data terlebih dahulu :)');
    },
    formatNumber(val) {
      return new Intl.NumberFormat(['ban', 'id']).format(val);
    },
    getOngkirValidation(){
      let isValid = []
      for(let cart of this.carts){
        if(cart.ongkir.value <= 0){
          isValid.push("false")
        }
        else isValid.push("true")
      }
      if(isIn("false", isValid)) return false
      else return true
    },
    changeOngkir(){
      this.getTotalCostShipping()
    },
    getTotalCostShipping(){
      let total = 0
      for(let val of this.carts){
        total += val.ongkir.value
      }
      this.total_cost_shipping = total
    }
  },
  watch:{
    carts(val){
      this.getTotalCostShipping()
    },
    shipping_district(val){
      this.user.shipping_subdistrict_id = val.shipping_subdistricts_id
      this.setShippingCost()
    },
    tmp_cost_shipping(val){
      let copyData = [...this.carts]
      for(let cart of copyData){
        if(cart.user_id === val.user_id){
          cart['shipping_list'] = val.data
          cart.ongkir = val.data[0]
        }
      }
      this.carts = copyData
    }
  },
  mounted(){
    this.getUser()
    setTimeout(() => {
      this.getCart()
    },500)
  }
}
</script>
<style>
.vs--searchable .vs__dropdown-toggle {
  height: calc(1.6em + 0.75rem + 2px);
}
.img-square-100 {
  width: 100px;
  height: 100px;
}
.v-select.is-invalid .vs__dropdown-toggle {
  border-color: #e3342f;
}
.card-sticky {
  position: sticky;
  top: 24px;
}
.vue-no-input .vs__search {
  display: none;
}
.image-product-payment {
  object-fit: cover;
  height: 80px!important;
}
</style>
