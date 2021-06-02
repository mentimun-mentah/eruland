<template>
  <div>
    <div class="container">
      <div class="row">

        <div class="col">
          <span class="text-secondary">
            Hasil pencarian dari "<span class="text-dark">{{query.q ? query.q : query.category ? query.category : 'Semua produk'}}</span>"
          </span>
        </div><!--/col-->

        <div class="col">
          <form class="form-inline float-right">
            <label class="form-check-label mr-2">
              Urutkan: 
            </label>
            <select class="custom-select" v-model="selected">
              <option v-for="option in options" :value="option.value">{{option.text}}</option>
            </select>
          </form>
        </div><!--/col-->

      </div><!--/row-->
    </div><!--/container-->

    <hr class="mb-4" />
    <div class="container">
      <div class="row" v-if="productData && productData.data && productData.data.length">
        <div class="col-sm-6 col-md-4 col-lg-3 mb-3" v-for="product in productData.data" :key="product.id">
          <div class="card">
            <img :src="storage + '/products/' + product.image" class="card-img-top image-product" alt="produk">
            <div class="card-body">
              <h5 class="card-title mb-1 text-truncate">
                <a :href="home + '/products/detail-product/' + product.slug + '/' + product.user_id" class="text-reset hover-pointer">{{product.name}}</a>
              </h5>
              <p class="card-text mb-0">Rp.{{formatNumber(product.price)}}</p>
              <star-rating 
                :inline="true"
                :star-size="16"
                :read-only="true"
                :show-rating="false"
                :increment="0.5" 
                v-model="+product.score[0].score_value"
                active-color="#ffb700" 
              />
              <span class="text-muted va-sub">({{product.score[0].score_count}})</span>
            </div>
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
</template>

<script>
import StarRating from 'vue-star-rating'

export default {
  props: ['query','storage','home'],
  data(){
    return {
      url: "/products/all-products",
      productData: {},
      selected: 'newest',
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
      let queryString = {}
      queryString["page"] = page
      queryString["order_by"] = this.selected
      if(this.query.q) queryString["q"] = this.query.q
      else delete queryString["q"]
      if(this.query.category) queryString["category"] = this.query.category
      else delete queryString["category"]

      axios.get(`${this.url}`, { params: queryString }).then(res => {
          this.productData = res.data;
      });
    },
    formatNumber(val) {
      return new Intl.NumberFormat(['ban', 'id']).format(val);
    },
  },
  watch:{
    selected(val){
      this.getResults()
    }
  },
  mounted(){
    this.getResults();
  },
  components: {
    StarRating
  }
}
</script>

<style>
.card-wishlist{
  position: absolute;
  display: flex;
  width: 40px;
  height: 40px;
  color: #ff4d4f;
  background-color: white;
  border-radius: 60% 4px 60% 60%;
  font-size: 16px !important;
  right: 0;
  align-items: center;
  justify-content: center;
}
.card-wishlist:hover{
  cursor: pointer;
}
.image-product {
  width: 100%;
  height: 200px;
  object-fit: cover;
}
.vue-star-rating {
  vertical-align: text-top;
}
.va-sub {
  vertical-align: sub;
}
</style>
