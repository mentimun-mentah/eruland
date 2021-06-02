<template>
  <div>
    <Toasts></Toasts>
    <div class="container">
      <div class="row">

        <div class="col-lg-8">
          <div class="row justify-content-between border-bottom pb-2">
            <div class="col-12">
              <a href="#!" type="button" class="text-dark text-uppercase mr-3 float-right" @click="deleteAllCart">
                <i class="fas fa-trash-alt mr-1"></i> Hapus Semua
              </a>
            </div>
          </div>

          <!-- Card -->
          <div class="mb-3">
            <div class="pt-4 wish-list" v-if="carts && carts.length > 0">
              <div class="mb-3" v-for="cart in carts" :key="cart.id">
                <div class="media mb-3">
                  <div class="media-body">
                    <div class="row">
                      <div class="col-md-2 col-lg-2 col-xl-2">
                        <div class="rounded mb-3 mb-md-0">
                          <img 
                            class="img-fluid w-100 image-product image-product-cart"
                            :src="storage + '/products/' + cart.options.image"
                            alt="Sample"
                          >
                        </div>
                      </div>
                      <div class="col-md-10 col-lg-10 col-xl-10">
                        <div>
                          <div class="d-flex justify-content-between">
                            <div>
                              <a :href="home + '/products/detail-product/' + cart.options.slug + '/' + cart.options.user_id" class="h5 text-decoration-none text-reset hover-pointer">
                                {{cart.name}}
                              </a>
                              <p class="mb-2 text-muted">
                              Rp.{{formatNumber(cart.price)}}/{{cart.options.weight}}gr
                              </p>
                            </div>
                          </div>
                          <div class="d-flex justify-content-between align-items-center">
                            <div>
                              <div class="number">
                                <span class="btn minus" @click="decreaseCart(cart.rowId,cart.qty)">-</span>
                                <input type="text" v-model="cart.qty" @keyup="inputCart($event,cart.rowId)"/>
                                <span class="btn plus" @click="increaseCart(cart.rowId,cart.qty)">+</span>
                              </div>
                            </div>
                            <a href="#!" type="button" class="card-link-secondary text-uppercase mr-3" @click="deleteCart(cart.rowId)">
                              <i class="fas fa-trash-alt mr-1"></i> Hapus
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div><!--/media-->
                <hr class="mb-4">
              </div>
            </div><!--/wish-list-->

            <div class="text-center py-5 text-muted" v-else>
              <i class="fad fa-boxes-alt fa-4x"></i>
              <p class="mt-2 mb-0">Tidak ada data</p>
            </div>

          </div>

        </div><!--/col-lg-8-->
        
        <div class="col-lg-4">
          <div class="card card-sticky">
            <div class="card-body">
              <p class="subtitle h5 font-weight-bold">
                <i class="far fa-archive"></i>&nbsp; Ringkasan Pesanan
              </p>
              <hr />

                <div v-for="cart in carts" :key="cart.id">
                  <p class="mb-1">
                    {{cart.name}}<span class="text-muted"> ({{cart.qty}})</span>
                    <span class="float-right">Rp.{{formatNumber(cart.subtotal)}}</span>
                  </p>
                </div>

                <hr />

                <div>
                  <div class="float-left text-gray h5">Total</div>
                  <div class="float-right text-dark font-weight-bold h5">
                    <b>Rp.{{total}}</b>
                  </div>
                </div>
                <br /> <br />

                <button class="btn btn-dark btn-block" @click="toPayment">Buat Pesanan</button>

              </div>
            </div><!--/card-body-->
          </div><!--/card-->
        </div><!--/col-lg-4-->


      </div><!--/row-->
    </div><!--/container-->
  </div>
</template>

<script>
export default {
  props: ['home','storage'],
  data(){
    return {
      carts: [],
      total: 0
    }
  },
  methods: {
    increaseCart(id,qty){
      axios.put(`/carts/increase-cart/${id}`,{qty:qty}).then(res => {
        this.getCart()
      }).catch(err => {
        let first = Object.keys(err.response.data.errors)
        this.$toast.error(err.response.data.errors[first][0]);
      })
    },
    decreaseCart(id,qty){
      axios.put(`/carts/decrease-cart/${id}`,{qty:qty}).then(res => {
        this.getCart()
      }).catch(err => {
        let first = Object.keys(err.response.data.errors)
        this.$toast.error(err.response.data.errors[first][0]);
      })
    },
    inputCart(event,id){
      if(event.keyCode > 47 && event.keyCode < 58){
        let qty = event.target.value
        axios.put(`/carts/input-cart/${id}`,{qty:qty}).then(res => {
          this.getCart()
        }).catch(err => {
          let first = Object.keys(err.response.data.errors)
          this.$toast.error(err.response.data.errors[first][0]);
        })
      }
    },
    deleteAllCart(){
      axios.delete('/carts/delete-all-cart').then(res => {
        this.getCart()
      })
    },
    deleteCart(id){
      axios.delete(`/carts/delete-cart/${id}`).then(res => {
        this.getCart()
      })
    },
    getCart(){
      let tmp = []
      axios.get('/carts/get-my-cart').then(res => {
        for(let [key, val] of Object.entries(res.data)){
          tmp.push(val)
        }
      })
      axios.get('/carts/get-total-cart').then(res => {
        this.total = res.data
      })
      this.carts = tmp
    },
    toPayment(){
      window.location.href = this.home + '/payments'
    },
    formatNumber(val) {
      return new Intl.NumberFormat(['ban', 'id']).format(val);
    },
  },
  mounted(){
    this.getCart()
  }
}
</script>

<style>
.skin-light .wish-list a.card-link-secondary>.fas.fa-heart {
  color: #6c757d !important;
  -webkit-transition: all .2s ease-in-out;
  transition: all .2s ease-in-out
}

.skin-light .wish-list a.card-link-secondary:hover>.fas.fa-heart {
  color: #ff3d71 !important;
  -webkit-transition: all .2s ease-in-out;
  transition: all .2s ease-in-out
}

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
.image-product-cart {
  height: 100px;
}
</style>
