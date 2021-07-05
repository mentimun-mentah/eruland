<template>
  <div>
    <Toasts></Toasts>
    <div class="container mt-2">
      <div class="row">
        <div class="col-5">
          <img :src="storage + '/products/' + product.image" class="rounded img-fluid image-product image-product-detail" alt="...">
          <p class="text-center">{{product.user.name}}</p>
        </div><!--/col-5-->

        <div class="col-7">
          <h2 class="mb-0">{{product.name}}</h2>
          <p>
            <star-rating 
              :inline="true"
              :star-size="18"
              :read-only="true"
              :show-rating="false"
              :increment="0.5" 
              v-model="score_value"
              active-color="#ffb700" 
              /> 
              <span class="text-muted va-sub">({{score_count}})</span>
          </p>

          <div class="row">

            <div class="col-12">
              <div class="media info-product">
                <h5 class="mr-3 info-product-left">Harga</h5>
                <div class="media-body">
                  <h5 class="mt-0 info-product-body-price">Rp.{{formatNumber(product.price)}}</h5>
                </div>
              </div>
            </div><!--/col-12-->

            <div class="col-12">
              <div class="media info-product">
                <h5 class="mr-3 info-product-left">Jumlah</h5>
                <div class="media-body">
                  <div class="number">
                    <span class="btn minus" @click="jumlahMin">-</span>
                    <input type="text" v-model="jumlah" />
                    <span class="btn plus" @click="jumlahAdd">+</span>
                  </div>
                  <span>Tersedia {{product.stock}} pcs</span>
                </div>
              </div>
            </div><!--/col-12-->

            <div class="col-12">
              <div class="media info-product">
                <h5 class="mr-3 info-product-left">Info <br/>Produk</h5>
                <div class="media-body">
                  <div class="d-flex">
                    <div class="info-item">
                      <p>Berat</p>
                      <p>{{product.weight}}gr</p>
                    </div>

                    <div class="info-item">
                      <p>Kategori</p>
                      <p class="text-capitalize">{{product.category}}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div><!--/col-12-->

            <div class="col-12 mb-2">
              <div class="media info-product">
                <h5 class="mr-3 info-product-left">Ongkos <br/>Kirim</h5>
                <div class="media-body">
                  <div class="fs-14 text-secondary mb-2">
                    <p class="mb-0 font-weight-light">
                      Ke
                      <a class="text-dark ml-1 text-reset text-decoration-none"
                         style="border-bottom: 1px dashed #343a40; font-weight: 500; cursor: pointer;"
                         v-if="shipping_district.shipping_provinces_name"
                         @click="handleClick"
                        >
                        {{shipping_district && shipping_district.shipping_provinces_name}},
                        {{shipping_district && shipping_district.shipping_cities_type}} 
                        {{shipping_district && shipping_district.shipping_cities_name}}, 
                        {{shipping_district && shipping_district.shipping_subdistricts_name}}
                      </a>
                      <a class="text-dark ml-1 text-reset text-decoration-none"
                         style="border-bottom: 1px dashed #343a40; font-weight: 500; cursor: pointer;"
                         @click="handleClick"
                         v-else
                        >
                        Pilih lokasi untuk lihat ongkos kirim
                      </a>
                    </p>
                    <p class="mb-0 font-weight-light">
                      Dari<span class="text-dark ml-1">{{ongkir_from}}</span>
                    </p>
                  </div>


                  <v-select
                    label="name"
                    :filterable="false"
                    :options="costs_shipping"
                    @search="getCost"
                    v-model="selected_shipping"
                    v-if="shipping_district.shipping_provinces_name"
                  >
                    <template 
                      slot="option"
                      slot-scope="option"
                      v-if="option.code && option.service && option.etd && option.value"
                    >
                      <div class="d-flex justify-content-between w-100">
                        <div class="align-self-center" style="width: calc(100%/2);">
                          <p class="mb-0 text-uppercase text-truncate"> 
                            <span class="va-super">{{ option.code || "-"}} {{ option.service }}</span>
                          </p>
                        </div>
                        <div class="align-self-center">
                          <p class="mb-0 text-truncate"> {{ option.etd && option.etd.split(" ")[0] || 0}} hari </p>
                        </div>
                        <div class="align-self-center mr-2">
                          <p class="mb-0 text-truncate"> Rp.{{ option.value ? formatNumber(option.value) : 0 }} </p>
                        </div>
                      </div>
                    </template>
                    <template 
                      slot="option"
                      slot-scope="option"
                      v-else
                    >
                      <div class="d-none"></div>
                    </template>

                    <template 
                      slot="selected-option" 
                      slot-scope="option" 
                    >
                      <div class="d-flex justify-content-between w-100" style="margin-top: 3px;">
                        <div class="align-self-center" style="width: calc(100%/2);">
                          <p class="mb-0 text-uppercase text-truncate"> 
                            <span class="va-super">{{ option.code || "-"}} {{ option.service }}</span>
                          </p>
                        </div>
                        <div class="align-self-center">
                          <p class="mb-0 text-truncate"> {{ option.etd && option.etd.split(" ")[0] || 0}} hari </p>
                        </div>
                        <div class="align-self-center mr-2">
                          <p class="mb-0 text-truncate"> Rp.{{ option.value ? formatNumber(option.value) : 0 }} </p>
                        </div>
                      </div>
                    </template>
                  </v-select>


                  <v-select
                    label="name"
                    :filterable="false"
                    :options="options"
                    @search="onSearch"
                    @input="setSelectedShipping"
                    v-model="shipping_district"
                    ref="mySelect"
                    v-else
                  >
                    <template slot="no-options">
                      Data tidak ditemukan...
                    </template>
                    <template 
                      slot="option"
                      slot-scope="option"
                      v-if="option.shipping_provinces_name && option.shipping_cities_type && 
                            option.shipping_cities_name && option.shipping_subdistricts_name"
                    >
                      <div class="d-center">
                        {{ option.shipping_provinces_name }}, {{option.shipping_cities_type}} 
                        {{option.shipping_cities_name}}, {{option.shipping_subdistricts_name}}
                      </div>
                    </template>
                    <template 
                      slot="selected-option" 
                      slot-scope="option" 
                      v-if="option.shipping_provinces_name && option.shipping_cities_type && 
                            option.shipping_cities_name && option.shipping_subdistricts_name"
                    >
                      <div class="selected d-center">
                        {{ option.shipping_provinces_name }}, {{option.shipping_cities_type}} 
                        {{option.shipping_cities_name}}, {{option.shipping_subdistricts_name}}
                      </div>
                    </template>
                  </v-select>
                </div>
              </div>
            </div><!--/col-12-->


            <div class="col-12 mb-2">
              <div class="row">
                <div class="col-6">
                  <button class="btn btn-block btn-danger" @click="payNow">
                    Beli Sekarang
                  </button>
                </div><!--/col-6-->
                <div class="col-6">
                  <button class="btn btn-block btn-dark" @click="storeCart">
                    <i class="far fa-cart-plus mr-2"></i> Tambah Ke Keranjang
                  </button>
                </div><!--/col-6-->
              </div>
            </div><!--/col-12-->


          </div><!--/row-->

        </div><!--/col-7-->
      </div><!--/row-->


      <div class="row mt-5">
        <ul class="nav nav-tabs mb-3 w-100" id="pills-tab" role="tablist">
          <li class="nav-item" role="presentation">
            <a 
              class="nav-link active" 
              id="pills-home-tab" 
              data-toggle="pill" 
              href="#pills-home" 
              role="tab" 
              aria-controls="pills-home" 
              aria-selected="true"
              @click="selected = 'desc'"
            >
              Deskripsi
            </a>
          </li>
          <li class="nav-item" role="presentation">
            <a 
              class="nav-link" 
              id="pills-profile-tab" 
              data-toggle="pill" 
              href="#pills-profile" 
              role="tab" 
              aria-controls="pills-profile" 
              aria-selected="false"
              @click="selected = 'review'"
            >
              Ulasan ({{score_count}})
            </a>
          </li>
        </ul>

        <div class="tab-content w-100" id="pills-tabContent">
          <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
            <p class="ws-preline">{{product.desc}}</p>
          </div>

          <div class="tab-pane fade w-100" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
            <div class="container" v-if="reviewData && reviewData.data && reviewData.data.length > 0">
              <div class="media mb-2" v-for="review in reviewData.data" :key="review.order_items_id">
                <img 
                  :src="storage + '/avatar/' + review.users_avatar" 
                  class="mr-3" alt="profile" style="width: 30px; height: 30px;"
                >
                <div class="media-body rating-ulasan">
                  <h6 class="mt-0 mb-0 font-weight-bold">
                    {{review.users_name}} <small class="text-muted ml-1">{{momentDate(order_items_updated_at)}}</small>
                  </h6>
                  <star-rating
                    :star-size="16"
                    :increment="0.5"
                    :show-rating="false"
                    :read-only="true"
                    v-model="review.order_items_rating"
                  />
                    <p> {{review.order_items_review}} </p>
                </div><!--/media-body-->
              </div><!--/media-->

              <pagination
                :data="reviewData"
                :limit="2"
                :align="'center'"
                @pagination-change-page="getReview"
              >
              </pagination>

            </div><!--/container-->

            <div class="container" v-else>
              <div class="text-center py-5 text-muted">
                <i class="fad fa-comments-alt fa-4x"></i>
                <p class="mt-2 mb-0">Tidak ada data</p>
              </div>
            </div>

          </div>
        </div><!--/tab-content-->

      </div><!--/row-->
    </div><!--/container-->
  </div>
</template>

<script>
import StarRating from 'vue-star-rating'
import moment from 'moment'

export default {
  props:['home','score','product','storage'],
  data() {
    return {
      score_value: null,
      score_count: null,
      user: null,
      ongkir_from: null,
      ongkir_from_id: null,
      shipping_district: {},
      selected_shipping: {},
      options: [],
      costs_shipping: [],
      reviewData: {},
      jumlah: 1,
      selected: "desc"
    };
  },
  methods: {
    getCost(search,loading){
      loading(true);
    
      let query = {
        origin: this.ongkir_from_id,
        originType: 'subdistrict',
        destination: this.shipping_district.shipping_subdistricts_id,
        destinationType: 'subdistrict',
        weight: this.product.weight * this.jumlah,
        courier: 'jne:pos:tiki:lion'
      }

      axios.post(`/products/get-cost`,query).then(res => {
        this.costs_shipping = res.data
        this.setSelectedShipping()
        loading(false)
      })

    },
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
    storeCart(){
      if(this.user && this.product.user_id == this.user.id){
        this.$toast.error("Upps, penjual tidak bisa membeli barang sendiri :)")
        return
      }

      let req = {
        id: this.product.id,
        qty: this.jumlah,
      }

      axios.post('/carts/store-cart',req).then(res => {
        if(res.data.status == "Success add product to cart") location.reload()
      }).catch(err => {
        if(err.response.status === 401){
          location.replace(this.home + '/login');
        }else{
          let first = Object.keys(err.response.data.errors)
          this.$toast.error(err.response.data.errors[first][0]);
        }
      })
    },
    payNow(){
      if(this.user && this.product.user_id == this.user.id){
        this.$toast.error("Upps, penjual tidak bisa membeli barang sendiri :)")
        return
      }

      let req = {
        id: this.product.id,
        qty: this.jumlah,
      }
      axios.post('/carts/pay-now',req).then(res => {
        if(res.data.status == "Success add product to cart") location.replace(this.home + '/payments');
      }).catch(err => {
        if(err.response.status === 401){
          location.replace(this.home + '/login');
        }
        else{
          let first = Object.keys(err.response.data.errors)
          this.$toast.error(err.response.data.errors[first][0]);
        }
      })
    },
    getUser(){
      axios.get('/user').then(res => {
        this.user = res.data;          
        if(this.user.shipping_subdistrict_id){
          axios.get(`/account/get-city-district/${this.user.shipping_subdistrict_id}`).then(res => {
            this.shipping_district = res.data[0]
            this.getCost(() => {}, () => {})
          })
        }
      })
    },
    getReview(page = 1) {
      axios.get(`/products/review-product/${this.product.id}?page=${page}`).then(res => {
        this.reviewData = res.data;
      });
    },
    handleClick() {
      this.shipping_district = {};
      this.$refs.mySelect.$refs.search.focus();
    },
    setSelectedShipping(val) {
      this.selected_shipping = this.costs_shipping[0]
    },
    formatNumber(val) {
      return new Intl.NumberFormat(['ban', 'id']).format(val);
    },
    momentDate(val) {
      moment.locale('id')
      return moment(val).add(1, 'days').format('lll')
    },
    jumlahAdd() {
      if(this.jumlah + 1 <= this.product.stock) {
        this.jumlah += 1
      }
    },
    jumlahMin() {
      if(this.jumlah - 1 >= 1) {
        this.jumlah -= 1
      }
    },
  },
  watch:{
    jumlah(val){
      let query = {
        origin: this.ongkir_from_id,
        originType: 'subdistrict',
        destination: this.shipping_district.shipping_subdistricts_id,
        destinationType: 'subdistrict',
        weight: this.product.weight * this.jumlah,
        courier: 'jne:pos:tiki:lion'
      }

      axios.post(`/products/get-cost`,query).then(res => {
        this.costs_shipping = res.data
        this.setSelectedShipping()
      })
    },
    selected(val){
      if(this.selected === "review"){
        this.getReview()
      }
    }
  },
  mounted(){
    axios.get(`/account/get-city-district/${this.product.user.shipping_subdistrict_id}`).then(res => {
      let data = res.data[0]
      this.ongkir_from = `${data.shipping_provinces_name}, ${data.shipping_cities_type} ${data.shipping_cities_name}, ${data.shipping_subdistricts_name}`
      this.ongkir_from_id = data.shipping_subdistricts_id
    })

    this.getUser()

    this.score_value = Number(this.score[0].score_value)
    this.score_count = Number(this.score[0].score_count)
  },
  components: {
    StarRating
  }
}
</script>

<style>
.minus, .plus{
  background:#f2f2f2;
  border-radius:4px;
  padding:5px;
  border:1px solid #ddd;
  display: inline-block;
  vertical-align: middle;
  text-align: center;
  width: 34px;
  height: 34px;
}
.number input{
  height:34px;
  width: 100px;
  text-align: center;
  font-size: 16px;
  border:1px solid #ddd;
  border-radius:4px;
  display: inline-block;
  vertical-align: middle;
}
.info-item{
  padding: 0px 16px;
  border-right: 1px solid rgb(229, 231, 233);
}
.info-item:last-child{
  border-right: 0;
}
.info-item:first-of-type{
  padding-left: 0;
}
.info-item p{
  margin-bottom: 0;
  font-size: 14px;
}
.info-item p:first-of-type{
  color: #828282;
  font-weight: 300;
}
.ws-preline{
  white-space: pre-line;
}

.rating {
margin-right: 5%;
text-align: center;
}
.rating-num {
color: #333333;
font-size: 72px;
font-weight: 50;
line-height: 1em;
}
.rating-stars .active {
color: #737373;
}
.rating-users {
font-size: 14px;
margin-top:8px;
}

.vue-star-rating {
  justify-content: center;
}
.info-product-left{
  font-size: 14px;
  text-transform: uppercase;
  color: #6c757d; 
  width: 70px;
}
.info-product{
  padding: 20px 0px;
  border-top: 1px solid rgb(229, 231, 233);
}
.info-product-body-price{
    font-weight: 700;
    font-size: 22px;
    color: rgb(214, 48, 49);
  }

  .rating-ulasan .vue-star-rating{
    justify-content: flex-start;
  }

.courier-img {
  height: 35px;
  margin-left: -5px;
}

.vs__selected {
  width: 100%;
}
.image-product-detail {
  height: 430px;
}
</style>
