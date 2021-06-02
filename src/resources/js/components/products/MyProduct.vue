<template>
  <div>
    <Toasts></Toasts>
    <div class="card-container card">
      <div class="bg-transparent border-bottom card-header">
        <h5 class="mt-1 mb-0 font-weight-bold">Kelola Produk</h5>
      </div>
      <!--/card-header-->
      <div class="row">
        <div class="col-sm-12 col-md-12 col-lg-12">
          <div class="card-body">
            <div class="form-row">
              <div class="form-group col-md-6 col-sm-12 mb-0">
                <input
                  type="text"
                  class="form-control"
                  placeholder="Cari produk"
                  v-model="search"
                />
              </div>
              <div class="form-group col-md-6 col-sm-12 mb-0">
                <select class="custom-select" v-model="selected">
                  <option v-for="option in options" :value="option.value">{{option.text}}</option>
                </select>
              </div>
            </div>
            <!--/form-row-->
          </div>
        </div>
      </div>
      <!--/row-->
    
      <div class="container">
        <div class="row" v-if="productData && productData.data && productData.data.length">
          <div class="col-sm-12 col-md-6 col-lg-4 mb-3" v-for="product in productData.data" :key="product.id">
            <div class="card">
              <img :src="storage + '/products/' + product.image" class="card-img-top image-product" alt="produk">
              <div class="card-body">
                <h5 class="card-title mb-1 text-truncate">
                  <a :href="home + '/products/detail-product/' + product.slug + '/' + product.user_id" class="text-reset hover-pointer">{{product.name}}</a>
                </h5>
                <p class="card-text mb-1">Rp.{{formatNumber(product.price)}}</p>
                <p class="card-text mb-0">Stok ({{product.stock}})</p>
                <span>
                  <star-rating 
                    :inline="true"
                    :star-size="15"
                    :read-only="true"
                    :show-rating="false"
                    :increment="0.5" 
                    v-model="+product.score[0].score_value"
                    class="va-bottom"
                    active-color="#ffb700" 
                  />
                  <span class="text-muted va-sub" style="font-size: 12px">
                    {{product.score[0].score_count}}
                  </span>
                </span>
              </div>

              <div class="card-footer">
                <div class="row">
                  <div class="col-6 text-center">
                    <a :href="home + '/products/change-my-product/' + product.id" type="button" class="btn btn-primary btn-block btn-sm">Ubah</a>
                  </div><!--/col-6-->
                  <div class="col-6 text-center">
                    <button type="button" class="btn btn-danger btn-block btn-sm" @click="deleteProduct(product.id)">Hapus</button>
                  </div><!--/col-6-->
                </div><!--/row-->
              </div><!--/card-footer-->

            </div><!--/card-->
          </div><!--/col-->
        </div><!--/row-->

        <div class="text-center py-5 text-muted" v-else>
          <i class="fad fa-boxes-alt fa-4x"></i>
          <p class="mt-2 mb-0">Tidak ada data</p>
        </div>

      <pagination
        :data="productData"
        :limit="2"
        :align="'center'"
        @pagination-change-page="getResults"
      ></pagination>
      </div><!--/container-->

    </div>
  </div>
</template>

<script>
import StarRating from 'vue-star-rating'

export default {
  props: ['home','storage'],
  data(){
    return {
      url: "/products/get-my-product",
      search: "",
      selected: "newest",
      productData: {},
      options: [
        {text: 'Terbaru', value: 'newest'},
        {text: 'Terlama', value: 'oldest'},
        {text: 'Harga Tertinggi', value: 'high_price'},
        {text: 'Harga Terendah', value: 'low_price'}
      ]
    }
  },
  methods:{
    getResults(page = 1) {
      axios.get(`${this.url}?page=${page}&q=${this.search}&order_by=${this.selected}`).then(res => {
        this.productData = res.data;
      });
    },
    deleteProduct(id){
      axios.delete(`/products/delete-product/${id}`).then(res => {
        this.$toast.success(res.data.status);
        this.getResults()
      })
    },
    formatNumber(val) {
      return new Intl.NumberFormat(['ban', 'id']).format(val);
    },
  },
  watch:{
    search(val){
      if(val.length > 2){
        this.getResults()
      } else this.getResults()
    },
    selected(val){
      this.getResults()
    }
  },
  components: {
    StarRating
  },
  mounted() {
    this.getResults();
  }
}
</script>

<style>
.va-bottom {
  vertical-align: bottom;
}
</style>
