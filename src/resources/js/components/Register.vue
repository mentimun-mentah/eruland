<template>
  <div>
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-8">
          <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <a
                class="nav-link active"
                id="nav-home-tab"
                data-toggle="tab"
                href="#nav-home"
                role="tab" aria-controls="nav-home"
                aria-selected="true"
              >
                Pengguna
              </a>
              <a
                class="nav-link"
                id="nav-profile-tab"
                data-toggle="tab"
                href="#nav-profile"
                role="tab"
                aria-controls="nav-profile"
                aria-selected="false"
              >
                Penjual
              </a>
            </div>
          </nav>

          <div class="tab-content" id="nav-tabContent">
            <div
              class="tab-pane fade show active"
              id="nav-home"
              role="tabpanel"
              aria-labelledby="nav-home-tab"
            >
              <div class="card">
                <div class="card-body">
                  <div class="form-group row">
                    <label
                      for="name"
                      class="col-md-4 col-form-label text-md-right"
                      >Username</label
                    >
                    <div class="col-md-7">
                      <input
                        id="name"
                        type="text"
                        class="form-control"
                        name="name"
                        required
                        autofocus
                        v-model="register_pengguna.name"
                        :class="[errors.name ? 'is-invalid' : '']"
                      />
                      <small class="text-danger" v-if="errors.name">
                        {{ errors.name[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->

                  <div class="form-group row">
                    <label
                      for="email"
                      class="col-md-4 col-form-label text-md-right"
                      >E-Mail Address</label
                    >
                    <div class="col-md-7">
                      <input
                        id="email"
                        type="email"
                        class="form-control"
                        name="email"
                        required
                        v-model="register_pengguna.email"
                        :class="[errors.email ? 'is-invalid' : '']"
                      />
                      <small class="text-danger" v-if="errors.email">
                        {{ errors.email[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->

                  <div class="form-group row">
                    <label
                      for="password"
                      class="col-md-4 col-form-label text-md-right"
                      >Password</label
                    >
                    <div class="col-md-7">
                      <input
                        id="password"
                        type="password"
                        class="form-control"
                        name="password"
                        required
                        v-model="register_pengguna.password"
                        :class="[errors.password ? 'is-invalid' : '']"
                      />
                      <small class="text-danger" v-if="errors.password">
                        {{ errors.password[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->

                  <div class="form-group row">
                    <label
                      for="confirm_password"
                      class="col-md-4 col-form-label text-md-right"
                      >Confirm Password</label
                    >
                    <div class="col-md-7">
                      <input
                        id="confirm_password"
                        type="password"
                        class="form-control"
                        name="confirm_password"
                        required
                        v-model="register_pengguna.password_confirmation"
                      />
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->

                  <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                      <button
                        type="submit"
                        class="btn btn-primary"
                        @click="registerPengguna"
                      >
                        Register
                      </button>
                    </div>
                  </div>
                </div>
                <!-- /card-body -->
              </div>
              <!-- /card -->
            </div>
            <!-- /tab-pane -->

            <div
              class="tab-pane fade"
              id="nav-profile"
              role="tabpanel"
              aria-labelledby="nav-profile-tab"
            >
              <div class="card">
                <div class="card-body">
                  <div class="form-group row">
                    <label
                      for="name"
                      class="col-md-4 col-form-label text-md-right"
                      >Username</label
                    >
                    <div class="col-md-7">
                      <input
                        id="name"
                        type="text"
                        class="form-control"
                        name="name"
                        required
                        autofocus
                        v-model="register_penjual.name"
                        :class="[errors_2.name ? 'is-invalid' : '']"
                      />
                      <small class="text-danger" v-if="errors_2.name">
                        {{ errors_2.name[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->

                  <div class="form-group row">
                    <label
                      for="email"
                      class="col-md-4 col-form-label text-md-right"
                      >E-Mail Address</label
                    >
                    <div class="col-md-7">
                      <input
                        id="email"
                        type="email"
                        class="form-control"
                        name="email"
                        required
                        v-model="register_penjual.email"
                        :class="[errors_2.email ? 'is-invalid' : '']"
                      />
                      <small class="text-danger" v-if="errors_2.email">
                        {{ errors_2.email[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->

                  <div class="form-group row">
                    <label
                      for="password"
                      class="col-md-4 col-form-label text-md-right"
                      >Password</label
                    >
                    <div class="col-md-7">
                      <input
                        id="password"
                        type="password"
                        class="form-control"
                        name="password"
                        required
                        v-model="register_penjual.password"
                        :class="[errors_2.password ? 'is-invalid' : '']"
                      />
                      <small class="text-danger" v-if="errors_2.password">
                        {{ errors_2.password[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->

                  <div class="form-group row">
                    <label
                      for="confirm_password"
                      class="col-md-4 col-form-label text-md-right"
                      >Confirm Password</label
                    >
                    <div class="col-md-7">
                      <input
                        id="confirm_password"
                        type="password"
                        class="form-control"
                        name="confirm_password"
                        required
                        v-model="register_penjual.password_confirmation"
                      />
                    </div>
                    <!-- /col-md-6 -->
                  </div>

                  <div class="form-group row">
                    <label
                      for="no_rek"
                      class="col-md-4 col-form-label text-md-right"
                      >No. Rekening</label
                    >
                    <div class="col-md-7">
                      <input
                        id="no_rek"
                        type="number"
                        class="form-control"
                        name="no_rek"
                        required
                        v-model="register_penjual.no_rek"
                        :class="[errors_2.no_rek ? 'is-invalid' : '']"
                      />
                      <small class="text-danger" v-if="errors_2.no_rek">
                        {{ errors_2.no_rek[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>

                  <div class="form-group row">
                    <label
                      for="a_n"
                      class="col-md-4 col-form-label text-md-right"
                      >Atas Nama</label
                    >
                    <div class="col-md-7">
                      <input
                        id="a_n"
                        type="text"
                        class="form-control"
                        name="a_n"
                        required
                        v-model="register_penjual.a_n"
                        :class="[errors_2.a_n ? 'is-invalid' : '']"
                      />
                      <small class="text-danger" v-if="errors_2.a_n">
                        {{ errors_2.a_n[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>

                  <div class="form-group row">
                    <label
                      for="ktp"
                      class="col-md-4 col-form-label text-md-right"
                      >Kartu Tanda Penduduk</label
                    >
                    <div class="col-md-7">
                      <div class="input-group custom-file-upload">
                        <input
                          type="text"
                          name="filename2"
                          class="form-control bg-white"
                          accept="image/*"
                          :placeholder="
                            register_penjual.ktp
                              ? register_penjual.ktp.name
                              : 'No file selected'
                          "
                          readonly
                          required
                          :class="[errors_2.ktp ? 'is-invalid' : '']"
                        />
                        <span class="input-group-btn">
                          <div class="btn btn-secondary custom-file-uploader">
                            <input
                              type="file"
                              name="file"
                              @change="changeKTP"
                              accept="image/*"
                              required
                            />
                            Choose file
                          </div>
                        </span>
                      </div>
                      <small class="text-danger" v-if="errors_2.ktp">
                        {{ errors_2.ktp[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>

                  <div class="form-group row">
                    <label
                      for="city_district"
                      class="col-md-4 col-form-label text-md-right"
                      >Kota / Kecamatan</label
                    >
                    <div class="col-md-7">
                      <v-select
                          label="name"
                          :filterable="false"
                          :options="options"
                          @search="onSearch"
                          v-model="shipping_district"
                          :class="[errors_2.shipping_subdistrict_id ? 'is-invalid' : '']"
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
                      <small class="text-danger" v-if="errors_2.shipping_subdistrict_id">
                        {{ errors_2.shipping_subdistrict_id[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>

                  <div class="form-group row">
                    <label
                      for="kode pos"
                      class="col-md-4 col-form-label text-md-right"
                      >Kode Pos</label
                    >
                    <div class="col-md-7">
                      <input 
                        type="number" 
                        class="form-control" 
                        placeholder="Kode Pos" 
                        v-model="register_penjual.code_pos"
                        :class="[errors_2.code_pos ? 'is-invalid' : '']"
                      >
                      <small class="text-danger" v-if="errors_2.code_pos">
                        {{ errors_2.code_pos[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->

                  <div class="form-group row">
                    <label
                      for="alamat"
                      class="col-md-4 col-form-label text-md-right"
                      >Alamat</label
                    >
                    <div class="col-md-7">
                      <textarea
                          class="form-control"
                          placeholder="Alamat"
                          rows="2"
                          v-model="register_penjual.address"
                          :class="[errors_2.address ? 'is-invalid' : '']"
                      ></textarea>
                      <small class="text-danger" v-if="errors_2.address">
                        {{ errors_2.address[0] }}
                      </small>
                    </div>
                    <!-- /col-md-6 -->
                  </div>
                  <!-- /form-group -->


                  <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                      <button
                        type="submit"
                        class="btn btn-primary"
                        @click="registerPenjual"
                      >
                        Register
                      </button>
                    </div>
                  </div>
                </div>
                <!-- /card-body -->
              </div>
              <!-- /card -->
            </div>
            <!-- /tab-pane -->
          </div>
          <!-- /tab-content -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import "vue-select/dist/vue-select.css";

export default {
  props: ["home"],
  data() {
    return {
      register_pengguna: {
        name: "",
        email: "",
        password: "",
        password_confirmation: ""
      },
      register_penjual: {
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
        ktp: null,
        no_rek: "",
        a_n: "",
        address: "",
        code_pos: null,
      },
      shipping_district: {},
      options: [],
      errors: [],
      errors_2: []
    };
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
    changeKTP(e) {
      this.register_penjual.ktp = e.target.files[0];
    },
    registerPengguna() {
      axios.post("/register", this.register_pengguna)
        .then(res => {
          window.location = this.home;
        })
        .catch(err => {
          this.errors = err.response.data.errors;
        });
    },
    registerPenjual() {
      const config = {
        headers: { "content-type": "multipart/form-data" }
      };
      let formData = new FormData();
      formData.append("name", this.register_penjual.name);
      formData.append("email", this.register_penjual.email);
      formData.append("password", this.register_penjual.password);
      formData.append(
        "password_confirmation",
        this.register_penjual.password_confirmation
      );
      formData.append("ktp", this.register_penjual.ktp);
      formData.append("no_rek", this.register_penjual.no_rek);
      formData.append("a_n", this.register_penjual.a_n);
      formData.append("shipping_subdistrict_id", this.shipping_district.shipping_subdistricts_id)
      formData.append("address", this.register_penjual.address)
      formData.append('code_pos', this.register_penjual.code_pos)
      axios
        .post("/register/penjual", formData, config)
        .then(res => {
          window.location = this.home;
        })
        .catch(err => {
          this.errors_2 = err.response.data.errors;
        });
    }
  }
};
</script>

<style>
.hover-pointer:hover {
  cursor: pointer !important;
}
.custom-file-uploader {
  position: relative;
  border-bottom-left-radius: 0px;
  border-top-left-radius: 0px;
  color: #495057;
  border-left-width: 0px;
  border-color: #ced4da;
  background-color: #e9ecef;
  cursor: pointer;
}
.custom-file-uploader:hover,
.custom-file-uploader:focus,
.custom-file-uploader:active {
  cursor: pointer;
  color: #495057 !important;
  border-color: #ced4da !important;
  background-color: #e9ecef !important;
}
.custom-file-uploader input[type="file"] {
  display: block;
  position: absolute;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 5;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: default;
}
.custom-file-upload .form-control.bg-white {
  background-color: white !important;
}
</style>
