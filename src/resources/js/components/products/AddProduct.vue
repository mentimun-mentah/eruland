<template>
    <div>
      <Toasts></Toasts>
        <div class="card-container card">
            <div class="bg-transparent border-bottom card-header">
                <h5 class="mt-1 mb-0 font-weight-bold">Tambah Produk</h5>
            </div>
            <!--/card-header-->
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-12">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-2 col-sm-8">
                                <label>Foto Produk</label>
                                <img
                                    id="image-preview"
                                    class="card-img-top img-fit upload-img p-t-4 p-r-4 p-b-4 p-l-4 border"
                                    alt="Card image cap"
                                    :src="
                                        url_image
                                            ? url_image
                                            : 'https://socialistmodernism.com/wp-content/uploads/2017/07/placeholder-image.png'
                                    "
                                />
                                <div
                                    class="file-btn btn btn-sm button border mt-2"
                                >
                                    Upload
                                    <input
                                        type="file"
                                        class="fileupload"
                                        name="file"
                                        accept="image/*"
                                        @change="changeProduct"
                                    />
                                </div>
                            </div>
                            <div class="col-12">
                              <small class="text-danger text-left" v-if="errors.image">
                                {{ errors.image[0] }}
                              </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Nama Produk</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Nama Produk"
                                    v-model="add_product.name"
                                    :class="[errors.name ? 'is-invalid' : '']"
                                />
                                <small class="text-danger text-left" v-if="errors.name">
                                  {{ errors.name[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Deskripsi</label>
                                <textarea
                                    class="form-control"
                                    placeholder="Deskripsi"
                                    rows="3"
                                    v-model="add_product.desc"
                                    :class="[errors.desc ? 'is-invalid' : '']"
                                ></textarea>
                                <small class="text-danger text-left" v-if="errors.desc">
                                  {{ errors.desc[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Kategori</label>
                                <select class="form-control" v-model="add_product.category" :class="[errors.category ? 'is-invalid' : '']">
                                    <option disabled value=""
                                        >Please select one</option
                                    >
                                    <option value="basah">Basah</option>
                                    <option value="kering">Kering</option>
                                </select>
                                <small class="text-danger text-left" v-if="errors.category">
                                  {{ errors.category[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Stok Produk</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    placeholder="Stok Produk"
                                    v-model="add_product.stock"
                                    :class="[errors.stock ? 'is-invalid' : '']"
                                />
                                <small class="text-danger text-left" v-if="errors.stock">
                                  {{ errors.stock[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Harga Produk</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span
                                            class="input-group-text"
                                            id="basic-addon1"
                                            >Rp</span
                                        >
                                    </div>
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="Harga Produk"
                                        v-model="add_product.price"
                                        :class="[errors.price ? 'is-invalid' : '']"
                                    />
                                </div>
                                <small class="text-danger text-left" v-if="errors.price">
                                  {{ errors.price[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Berat Produk</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span
                                            class="input-group-text"
                                            id="basic-addon1"
                                            >gr</span
                                        >
                                    </div>
                                    <input
                                        type="number"
                                        class="form-control"
                                        placeholder="Berat Produk"
                                        v-model="add_product.weight"
                                        :class="[errors.weight ? 'is-invalid' : '']"
                                    />
                                </div>
                                <small class="text-danger text-left" v-if="errors.weight">
                                  {{ errors.weight[0] }}
                                </small>
                            </div>
                        </div>
                        <!--/form-row-->

                        <button class="btn btn-danger" @click="addProduct">
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
export default {
    data() {
        return {
            url_image: null,
            add_product: {
                image: null,
                name: "",
                desc: "",
                category: "",
                stock: 0,
                price: 0,
                weight: 0,
            },
            errors: []
        };
    },
    methods: {
        changeProduct(e) {
            this.add_product.image = e.target.files[0];
            this.url_image = URL.createObjectURL(this.add_product.image);
        },
        addProduct() {
            let formData = new FormData();
            formData.append("image", this.add_product.image);
            formData.append("name", this.add_product.name);
            formData.append("desc", this.add_product.desc);
            formData.append("category", this.add_product.category);
            formData.append("stock", this.add_product.stock);
            formData.append("price", this.add_product.price);
            formData.append("weight", this.add_product.weight);

            const config = {
                headers: { "content-type": "multipart/form-data" }
            };

          axios.post('/products/create-product',formData,config).then(res => {
            this.$toast.success(res.data.status);
            this.errors = [];
            this.url_image = null
            this.add_product.image = null
            this.add_product.name = ""
            this.add_product.desc = ""
            this.add_product.category = ""
            this.add_product.stock = 0
            this.add_product.price = 0
            this.add_product.weight = 0
          }).catch(err => {
            this.errors = err.response.data.errors;
          })
        }
    }
};
</script>

<style>
.file-btn {
    position: relative;
    overflow: hidden;
    cursor: pointer;
    text-align: center;
    margin: 0 auto;
    left: 21%;
}
.fileupload {
    cursor: pointer;
    position: absolute;
    font-size: 50px;
    opacity: 0;
    right: 0;
    top: 0;
}
</style>
