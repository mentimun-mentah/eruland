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

    
    <!-- Modal -->
    <div class="modal-backdrop fade show" v-if="infoModal"></div>
    <div v-if="infoModal" class="modal fade show d-block">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Rumput laut</h5>
            <button
              type="button"
              class="close"
              data-dismiss="modal"
              aria-label="Close"
              @click="infoModal = !infoModal"
            >
              <span aria-hidden="true">&times;</span>
            </button>
          </div>

          <div class="modal-body">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
              <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
              <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="https://bit.ly/3ynvJQd" class="d-block w-100 img-slider-modal" alt="...">
                <p class="mt-3">
                  Rumput laut bisa dikatakan sebagai arti tumbuhan tingkat rendah yang tidak mempunyai akar, batang, serta daun sejati. Sehingga tanaman ini pada umumnya melekat pada substrat yang berbentuk thallus. Rumput laut juga merupakan gabungan dari beberapa tanaman yang tidak bervaskular dan memiliki pigmen klorofil untuk melakukan proses fotosintesis.
                </p>
                <p>
                Disisi lainnya, rumput laut memiliki struktur perkembangbiakan vegetatif yang tidak sama dengan tanaman tingkat tinggi. Struktur vegetatif rumput laut tidak bisa dibedakan antara daun, jenis batang serta akar. Struktur yang tidak dapat dibedakan ini dikenal dengan sebutan talus.
                </p>
                <p>
                Prihal ini misalnya saja talus pada rumput laut ialah multisel serta terdiri dari bentuk dan ukuran yang berbeda. Sehingga talus dapat dibedakan menjadi dua bentuk yang umum yakni filamen dan sifon. Kedua bentuk talus ini dapat bervariasi yang nantinya akan mendapatkan bentuk talus yang lebih kompleks. Hal ini juga termasuk filamen ringkas sampai pada bentuk filamen yang lebih besar yang bisa dibedakan antara kepala pelekap, stip serta lamina.
                </p>
              </div>

              <div class="carousel-item">
                <img src="https://bit.ly/2SLg4uS" class="d-block w-100 img-slider-modal" alt="...">
                <h4 class="mt-3 font-weight-bold">Manfaat Rumput Laut</h4>
                <ol>
                  <li>
                    <h6>Menurunkan Berat Badan</h6>
                    <p>
                      Sebagian jenis rumput laut seperti rumput laut cokelat terdapat kandungan pigmen fucoxanthin, yang bisa membantu metabolisme tubuh sehingga lemak dapat berubah menjadi energi.
                    </p>
                    <p>
                      Pernyataan ini diperkuat oleh sebuah penelitian yang telah dipublikasikan oleh Food Chemistry, bahwasanya ditemukan pada alginat atau serat alami yang ada pada rumput laut cokelat bisa membantu kurang lebih 75% untuk menghalangi penyerapan lemak diusus.
                    </p>
                  </li>

                  <li>
                    <h6>Menurunkan Berat Badan</h6>
                    <p>
                      Manfaat rumput laut selanjutnya ialah membantu dalam proses penyembuhan luka. Rumput laut mengandung banyak vitamin K, dan vitamin ini dapat bekerjasama dengan trombosit atau jenis sel yang mampu membentuk gumpalan atau proses pembekuan darah. Vitamin K dapat mengirim sinyal kimia sehingga trombosit akan melakukan penggumpulan darah dan pembekuan, sehingga ketika sedang terluka maka luka tersebut akan cepat berhenti mengalir.
                    </p>
                  </li>

                  <li>
                    <h6>Menguatkan Tulang dan Gigi</h6>
                    <p>
                      Rumput laut juga banyak terdapat kalsium. Apabila seseorang mengalami alergi terhadap susu sapi, maka harus dipertimbangkan makanan yang banyak mengandung kalsium. Pada rumput laut dan wakame terdapat lebih dari 60 mg kalsium, atau kurang lebih 6 % dari kebutuhan kalsium harian manusia.
                    </p>
                    <p>
                      Kurangnya kalsium bisa berdampak negative dalam pembentukan otot dan sel yang bekerjasama dengan sistem saraf.
                    </p>
                  </li>
                  
                  <li>
                    <h6>Meningkatkan Energi</h6>
                    <p>
                      Kandungan lain yang ada pada rumput laut ialah zat besi. Manfaat yang bisa didapatkan dari zat besi yakni sebagai alat untuk memproduksi energi yang dibutuhkan untuk proses pembakaran dalam tubuh saat melakukan berbagai kegiatan harian.
                    </p>
                    <p>
                      Kurangnya zat besi bisa menyebabkan anemia sehingga seseorang bisa menjadi mudah lemas, letih, lemah dan lesu. Dengan begitu maka dapat dilakukan cara menambah asupan zat besi dengan banyak mengkonsumsi rumput laut. Dalam semangkuk rumput laut terdapat 1,1  dan 0,8 mg zat besi.
                    </p>
                    <p>
                      Dengan memasukkan rumput laut ke dalam makanan yang dikonsumsi sehari-hari bisa membantu seseorang untuk mencukupi kebutuhan 8 mg zat besi harian terutama pada laki-laki, dan 18 mg zat besi pada perempuan.
                    </p>
                  </li>

                  <li>
                    <h6>Baik untuk Penderita Diabetes dan Kolesterol</h6>
                    <p>
                      Dalam rumput laut juga terdapat kandungan omega 3 dan asam lemak. Dalam satu lembar rumput laut mempunyai kandungan omega 3 dan asam lemak yang mana kandungannya setara dengan dua alpukat. Manfaat dari omega 3 asam lemak yaitu untuk meningkatkan kolesterol yang baik atau HDL, dan mengurangi kolesterol yang jahat atau LDL.
                    </p>
                  </li>

                  <li>
                    <h6>Terhindar dari Pembengkakan Kelenjar Tiroid</h6>
                    <p>
                      Kandungan yodium yang terdapat pada rumput laut juga merupakan senyawa yang cukup jarang ditemukan pada jenis makanan lain. Dengan rutin mengkonsumsi yodium maka akan bermanfaat untuk mempertahankan tiroid untuk kesehatan tubuh manusia.
                    </p>
                    <p>
                      Tiroid yang mengalami masalah bisa menyebabkan sebagian gejala penyebab penyakit seperti rasa lemah disertai dengan otot yang ikut melemah, kolesterol naik, bahkan ada juga kasus yang lebih parah yakni dapat menyebabkan masalah kesehatan yang lebih aserius seperti gondokan, jantung sering berdebar-debar, bahkan sampai memori yang terganggu.
                    </p>
                  </li>

                </ol>
              </div>
            </div>

            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="sr-only">Next</span>
            </a>
          </div>
          </div><!--/modal-body-->

        </div><!--/modal-content-->
      </div><!--/modal-dialog-->
    </div>
    <!--/Modal -->

  </div>
</template>

<script>
import StarRating from 'vue-star-rating'

export default {
  props: ['query','storage','home'],
  data(){
    return {
      infoModal: false,
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
    setTimeout(() => {
      this.infoModal = true;
    }, 500);
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

.img-slider-modal {
  height: 400px;
  width: 100%;
  object-fit: cover;
}
.carousel-control-prev, .carousel-control-next {
  top: 180px;
  align-items: flex-start;
}
</style>
