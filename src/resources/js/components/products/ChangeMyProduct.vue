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
                            <div class="form-group col-md-2 col-sm-8">
                                <label>Foto Produk</label>
                                <img
                                    id="image-preview"
                                    class="card-img-top img-fit upload-img p-t-4 p-r-4 p-b-4 p-l-4 border"
                                    alt="Card image cap"
                                    :src="
                                        url_image
                                            ? url_image
                                            : storage +
                                              '/' +
                                              update_product.image
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
                                <small
                                    class="text-danger text-left"
                                    v-if="errors.image"
                                >
                                    {{ errors.image[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Nama Produk</label>
                                <input
                                    type="text"
                                    class="form-control"
                                    placeholder="Nama Produk"
                                    v-model="update_product.name"
                                    :class="[errors.name ? 'is-invalid' : '']"
                                />
                                <small
                                    class="text-danger text-left"
                                    v-if="errors.name"
                                >
                                    {{ errors.name[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Deskripsi</label>
                                <textarea
                                    class="form-control"
                                    placeholder="Deskripsi"
                                    rows="3"
                                    v-model="update_product.desc"
                                    :class="[errors.desc ? 'is-invalid' : '']"
                                ></textarea>
                                <small
                                    class="text-danger text-left"
                                    v-if="errors.desc"
                                >
                                    {{ errors.desc[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Kategori</label>
                                <select
                                    class="form-control"
                                    v-model="update_product.category"
                                    :class="[
                                        errors.category ? 'is-invalid' : ''
                                    ]"
                                >
                                    <option disabled value=""
                                        >Please select one</option
                                    >
                                    <option value="basah">Basah</option>
                                    <option value="kering">Kering</option>
                                </select>
                                <small
                                    class="text-danger text-left"
                                    v-if="errors.category"
                                >
                                    {{ errors.category[0] }}
                                </small>
                            </div>

                            <div class="form-group col-md-12 col-sm-12">
                                <label>Stok Produk</label>
                                <input
                                    type="number"
                                    class="form-control"
                                    placeholder="Stok Produk"
                                    v-model="update_product.stock"
                                    :class="[errors.stock ? 'is-invalid' : '']"
                                />
                                <small
                                    class="text-danger text-left"
                                    v-if="errors.stock"
                                >
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
                                        v-model="update_product.price"
                                        :class="[
                                            errors.price ? 'is-invalid' : ''
                                        ]"
                                    />
                                </div>
                                <small
                                    class="text-danger text-left"
                                    v-if="errors.price"
                                >
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
                                        v-model="update_product.weight"
                                        :class="[
                                            errors.weight ? 'is-invalid' : ''
                                        ]"
                                    />
                                </div>
                                <small
                                    class="text-danger text-left"
                                    v-if="errors.weight"
                                >
                                    {{ errors.weight[0] }}
                                </small>
                            </div>
                        </div>
                        <!--/form-row-->

                        <button class="btn btn-danger" @click="updateProduct">
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
    props: ["product", "storage"],
    data() {
        return {
            url_image: null,
            update_product: {
                image: null,
                name: null,
                desc: null,
                category: null,
                stock: null,
                price: null,
                weight: null
            },
            errors: []
        };
    },
    methods: {
        changeProduct(e) {
            this.update_product.image = e.target.files[0];
            this.url_image = URL.createObjectURL(this.update_product.image);
        },
      updateProduct(){
            let formData = new FormData();
            if(typeof this.update_product.image !== 'string') formData.append("image", this.update_product.image);

            formData.append("id", this.product.id)
            formData.append("name", this.update_product.name);
            formData.append("desc", this.update_product.desc);
            formData.append("category", this.update_product.category);
            formData.append("stock", this.update_product.stock);
            formData.append("price", this.update_product.price);
            formData.append("weight", this.update_product.weight);

            const config = {
                headers: { "content-type": "multipart/form-data" }
            };

        axios.post("/products/update-product", formData, config).then(res => {
            location.reload();
        }).catch(err => {
            this.errors = err.response.data.errors;
        })

      }
    },
    mounted() {
      this.update_product.image = this.product.image;
      this.update_product.name = this.product.name
      this.update_product.desc = this.product.desc
      this.update_product.category = this.product.category
      this.update_product.stock = this.product.stock
      this.update_product.price = this.product.price
      this.update_product.weight = this.product.weight
    }
};
</script>
