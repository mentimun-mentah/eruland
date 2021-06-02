<template>
  <div>
    <div class="card-container card">
      <Toasts></Toasts>
        <div class="bg-transparent border-bottom card-header">
            <h5 class="mt-1 mb-0 font-weight-bold">Akun Saya</h5>
            <p class="mb-0">
                Kelola informasi profil Anda untuk mengontrol dan melindungi
                akun anda
            </p>
        </div>
        <!--/card-header-->
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-8 border-right">
                <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <label for="inputEmail4">Nama Lengkap</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="inputEmail4"
                                    placeholder="Nama Lengkap"
                                    v-model="user.fullname"
                                    :class="[errors.fullname ? 'is-invalid' : '']"
                                />
                                <small class="text-danger" v-if="errors.fullname">
                                  {{ errors.fullname[0] }}
                                </small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Username</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    id="inputEmail4"
                                    placeholder="Username"
                                    v-model="user.name"
                                    :class="[errors.name ? 'is-invalid' : '']"
                                />
                                <small class="text-danger" v-if="errors.name">
                                  {{ errors.name[0] }}
                                </small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputEmail4">Email</label>
                                <input
                                    disabled
                                    type="email"
                                    class="form-control"
                                    id="inputEmail4"
                                    :placeholder="user.email"
                                    :class="[errors.email ? 'is-invalid' : '']"
                                />
                                <small class="text-danger" v-if="errors.email">
                                  {{ errors.email[0] }}
                                </small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4"
                                    >Nomor Telepon</label
                                >
                                <input
                                    type="text"
                                    class="form-control"
                                    id="inputPassword4"
                                    placeholder="Nomor Telepon"
                                    v-model="user.phone"
                                    :class="[errors.phone ? 'is-invalid' : '']"
                                />
                                <small class="text-danger" v-if="errors.phone">
                                  {{ errors.phone[0] }}
                                </small>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="inputPassword4"
                                    >Jenis Kelamin</label
                                >
                                <select id="inputState" class="form-control" v-model="user.gender" :class="[errors.gender ? 'is-invalid' : '']">
                                    <option disabled value="">Please select one</option>
                                    <option value="laki-laki">Laki-laki</option>
                                    <option value="perempuan">Perempuan</option>
                                </select>
                                <small class="text-danger" v-if="errors.gender">
                                  {{ errors.gender[0] }}
                                </small>
                            </div>

                            <div class="form-row col-12">
                              <div class="form-group col-md-8">
                                <label>Kota / Distrik</label>
                                <v-select
                                    label="name"
                                    :filterable="false"
                                    :options="options"
                                    @search="onSearch"
                                    v-model="shipping_district"
                                    :class="[errors.shipping_subdistrict_id ? 'is-invalid' : '']"
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
                                <small class="text-danger" v-if="errors.shipping_subdistrict_id">
                                  {{ errors.shipping_subdistrict_id[0] }}
                                </small>
                              </div><!--/form-group-->

                              <div class="form-group col-md-4">
                                <label>Kode Pos</label>
                                <input 
                                  type="number" class="form-control" placeholder="Kode Pos" v-model="user.code_pos"
                                  :class="[errors.code_pos ? 'is-invalid' : '']"
                                >
                                <small class="text-danger" v-if="errors.code_pos">
                                  {{ errors.code_pos[0] }}
                                </small>
                              </div><!--/form-group-->
                            </div><!--/form-row-->

                            <div class="form-group col-md-12 select-custom">
                            </div>

                            <div class="form-group col-md-12">
                                <label for="inputEmail4">Alamat</label>
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
                            </div>
                        </div>
                        <!--/form-row-->

                        <button class="btn btn-danger" @click="updateUser">
                            Simpan
                        </button>
                </div>
                <!--/card-body-->
            </div>
            <!--/col-->

            <div class="col-sm-12 col-md-12 col-lg-4 pl-0">
                <div class="card-body text-center">
                    <img
                        :src="storage + '/avatar/' + user.avatar"
                        width="100"
                        height="100"
                        class="img-thumbnail rounded-circle mx-auto d-block img-square-100"
                        alt="profile"
                    />
                    <div class="file-btn btn btn-sm button border mt-2 left-0">
                        Pilih Foto
                        <input
                            type="file"
                            class="fileupload"
                            name="file"
                            accept="image/*"
                            @change="updateAvatar"
                        />
                    </div>

                    <p class="fs-12 mb-0 mt-2 text-secondary mt-0">
                        Ukuran gambar: maks. 4 MB
                    </p>
                    <p class="fs-12 mb-0 text-secondary mt-0">
                        Format gambar: .JPEG, .JPG, .PNG
                    </p>
                </div>
                <!--/card-body-->
            </div>
            <!--/col-->
        </div>
        <!--/row-->
    </div>
    <!--/card-container-->

    <div class="card-container card mt-3" v-if="user.role == 'penjual'">
        <div class="bg-transparent border-bottom card-header">
            <h5 class="mt-1 mb-0 font-weight-bold">Ubah Rekening</h5>
            <p class="mb-0">
                Kelola Rekening Anda untuk mengontrol keuangan penjualan anda
            </p>
        </div>
        <!--/card-header-->
        <div class="row">
            <div class="col-sm-12 col-md-12 col-lg-12">
                <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6 col-sm-12">
                                <label>Atas Nama</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Atas Nama"
                                    v-model="user.a_n"
                                    :class="[errors_2.a_n ? 'is-invalid' : '']"
                                />
                                <small class="text-danger" v-if="errors_2.a_n">
                                  {{ errors_2.a_n[0] }}
                                </small>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label>No. Rekening</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    placeholder="No. Rekening"
                                    v-model="user.no_rek"
                                    :class="[errors_2.no_rek ? 'is-invalid' : '']"
                                />
                                <small class="text-danger" v-if="errors_2.no_rek">
                                  {{ errors_2.no_rek[0] }}
                                </small>
                            </div>
                        </div>
                        <!--/form-row-->

                        <button class="btn btn-danger" @click="updateRekening">
                            Simpan
                        </button>
                </div>
                <!--/card-body-->
            </div>
            <!--/col-->
        </div>
        <!--/row-->
    </div>
    <!--/card-container-->
  </div>
</template>

<script>
import "vue-select/dist/vue-select.css";

export default {
    name: "app",
    props: ["home", "storage"],
    data() {
        return {
            user: null,
            options: [],
            shipping_district: {},
            errors: [],
            errors_2: []
        };
    },
    watch:{
      shipping_district(val){
        this.user.shipping_subdistrict_id = val.shipping_subdistricts_id
      }
    },
    methods: {
        onSearch(search, loading) {
            if (search.length > 2) {
                loading(true);
                axios
                    .get(`/account/get-city-district?q=${search}`)
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
      updateAvatar(e){
        let formData = new FormData();
        formData.append("avatar", e.target.files[0]);
        const config = {
          headers: { "content-type": "multipart/form-data" }
        };
        axios
          .post("/account/update-avatar", formData, config)
          .then(res => {
            location.reload();
          })
          .catch(err => {
            this.$toast.error(err.response.data.errors.avatar[0]);
          });
      },
      updateUser(){
        let user = {
          fullname: this.user.fullname,
          name: this.user.name,
          phone: this.user.phone,
          gender: this.user.gender,
          shipping_subdistrict_id: this.user.shipping_subdistrict_id ? this.user.shipping_subdistrict_id : this.shipping_district[0].shipping_subdistricts_id,
          address: this.user.address,
          code_pos: this.user.code_pos
        }

        axios.put("/account/update-profile", user).then(res => {
            this.$toast.success(res.data.status);
            this.errors = [];
        }).catch(err => {
            this.errors = err.response.data.errors;
        })
      },
      updateRekening(){
        let rekening = {
          no_rek: this.user.no_rek,
          a_n: this.user.a_n
        }

        axios.put("/account/update-rekening", rekening).then(res => {
          this.$toast.success(res.data.status);
          this.errors_2 = [];
        }).catch(err => {
          this.errors_2 = err.response.data.errors;
        })
      },
      getUser(){
        axios.get('/user').then(res => {
          this.user = res.data;          
          if(this.user.shipping_subdistrict_id) {
            axios.get(`/account/get-city-district/${this.user.shipping_subdistrict_id}`).then(res => {
              this.shipping_district = res.data
            })
          }
        })
      }
    },
  mounted(){
    this.getUser();
  }
};
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
.file-btn.left-0 {
  left: 0%;
}
</style>
