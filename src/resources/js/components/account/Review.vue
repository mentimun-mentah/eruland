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
              @click="selected = 'waiting'"
            >
              Menunggu Diulas
            </a>
            <a 
              class="nav-link"
              id="nav-home-tab" 
              data-toggle="tab" 
              href="#nav-home" 
              role="tab" 
              aria-controls="nav-home" 
              aria-selected="true"
              @click="selected = 'my'"
            >
              Ulasan Saya
            </a>
          </div>
        </nav>
        
        <div class="tab-content" id="nav-tabContent">

          <div class="tab-pane fade show active" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab">

            <div v-if="waitingReviewData && waitingReviewData.data && waitingReviewData.data.length">
              <div class="card mt-3" v-for="waiting_review in waitingReviewData.data" :key="waiting_review.order_items_id">
                <div class="card-header">
                  <div class="d-flex justify-content-between">
                    <span>{{waiting_review.orders_order_id}}</span>
                    <span class="text-secondary">Pesanan diterima: {{momentJs(waiting_review.orders_created_at)}}</span>
                  </div>
                </div><!--/card-header-->

                <div class="card-body">
                  <div class="media">
                    <img :src="storage + '/' + waiting_review.products_image" class="align-self-start mr-3 cart-item-img" alt="eruland" />
                    <div class="media-body">
                      <h5 class="mt-0 h6 text-truncate">
                        <b>
                          {{waiting_review.products_name}}
                        </b>
                      </h5>
                      <p class="text-secondary mb-0 fs-12-s d-none d-md-block">
                        Bagaimana kualitas produk ini secara keseluruhan?
                      </p>

                      <star-rating 
                        :inline="true"
                        :star-size="16"
                         :show-rating="false"
                         v-model="waiting_review.order_items_rating"
                        active-color="#ffb700" 
                      />

                      <div class="mt-2">
                        <div class="form-group">
                          <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" v-model="waiting_review.order_items_review"></textarea>
                        </div>
                        <button 
                          class="btn btn-danger mr-2"
                          @click="setReview(waiting_review.order_items_rating,waiting_review.order_items_review,waiting_review.order_items_id)
                        ">
                          Simpan
                      </button>
                      </div>

                    </div><!--/media-body-->
                  </div><!--/media-->
                </div><!--/card-body-->
              </div><!--/card-->
            </div>

            <div class="text-center py-5 text-muted" v-else>
              <i class="fad fa-boxes-alt fa-4x"></i>
              <p class="mt-2 mb-0">Tidak ada data</p>
            </div>

            <pagination
              :data="waitingReviewData"
              :limit="2"
              :align="'center'"
              @pagination-change-page="getWaitingReview"
            ></pagination>
          </div><!--/tab-pane-->

          <div class="tab-pane fade" id="nav-home" role="tabpanel" aria-labelledby="nav-home-tab">
            <div v-if="myReviewData && myReviewData.data && myReviewData.data.length">
              <div class="card mt-3" v-for="(review, idx) in myReviewData.data" :key="idx">
                <div class="card-header">
                  <div class="d-flex justify-content-between">
                    <span>{{review.orders_order_id}}</span>
                    <span class="text-secondary">Pesanan diterima: {{momentJs(review.orders_created_at)}}</span>
                  </div>
                </div><!--/card-header-->

                <div class="card-body">
                  <div class="media">
                    <img :src="storage + '/' + review.products_image" class="align-self-start mr-3 cart-item-img" alt="eruland" />
                    <div class="media-body">
                      <h5 class="mt-0 h6 text-truncate mb-0">
                        <b>
                          {{review.products_name}}
                        </b>
                      </h5>
                      <star-rating 
                        :inline="true"
                        :star-size="16"
                        :increment="0.5" 
                        :read-only="true"
                        :show-rating="false"
                        :rating="review.order_items_rating"
                        active-color="#ffb700" 
                      />

                      <p class="text-black-50 mb-0">
                        {{review.order_items_review}}
                      </p>
                      <small class="text-black-50">{{momentJs(review.order_items_updated_at)}}</small>

                    </div><!--/media-body-->
                  </div><!--/media-->
                </div><!--/card-body-->
              </div><!--/card-->
            </div>

            <div class="text-center py-5 text-muted" v-else>
              <i class="fad fa-boxes-alt fa-4x"></i>
              <p class="mt-2 mb-0">Tidak ada data</p>
            </div>

            <pagination
              :data="myReviewData"
              :limit="2"
              :align="'center'"
              @pagination-change-page="getMyReview"
            ></pagination>

          </div><!--/tab-pane-->

        </div>

      </div><!--/card-body-->

    </div><!--/card-->

  </div>
</template>

<script>
import moment from "moment";
import StarRating from 'vue-star-rating'

export default {
  props: ['storage'],
  data(){
    return{
      selected: "waiting",
      waitingReviewData: {},
      myReviewData: {},
      errors: []
    }
  },
  methods: {
    getWaitingReview(page = 1){
      axios.get(`/account/waiting-review?page=${page}`).then(res => {
        this.waitingReviewData = res.data;
      });
    },
    getMyReview(page = 1){
      axios.get(`/account/my-review?page=${page}`).then(res => {
        this.myReviewData = res.data;
      })
    },
    setReview(rating,review,id){
      if(rating && review && id){
        axios.put(`/account/set-review/${id}`,{rating:rating,review:review}).then(res => {
          this.$toast.success(res.data.status);
          this.errors = []
          this.getWaitingReview()
        }).catch(err => {
          this.errors = err.response.data.errors;
        })
      }
    },
    momentJs(val) {
      return moment(val).format("DD MMM YYYY hh:mm");
    }
  },
  components: {
    StarRating
  },
  mounted(){
    this.getWaitingReview()
  },
  watch:{
    selected(val){
      if(val === "waiting") this.getWaitingReview()
      if(val === "my") this.getMyReview()
    }
  },
}
</script>
<style>
.cart-item-img {
  width: 90px;
  height: 90px;
  object-fit: cover;
  border-radius: 0.2rem;
}
</style>
