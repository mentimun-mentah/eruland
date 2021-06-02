<template>
    <div class="card-container card">
          <Toasts></Toasts>
        <div class="bg-transparent border-bottom card-header">
            <h5 class="mt-1 mb-0 font-weight-bold">
                Atur Kata Sandi
            </h5>
            <p class="mb-0">
                Untuk keamanan akun Anda, mohon untuk tidak menyebarkan password
                Anda ke orang lain.
            </p>
        </div>
        <!--/card-header-->

        <div class="card-body">
            <div class="form-row">
                <div class="form-group col-12">
                    <label for="inputEmail4">Password Saat Ini</label>
                    <input
                        type="password"
                        class="form-control"
                        id="inputEmail4"
                        placeholder="Password Saat Ini"
                        v-model="update_password.old_password"
                        :class="[errors.old_password ? 'is-invalid' : '']"
                    />
                    <small class="text-danger" v-if="errors.old_password">
                      {{ errors.old_password[0] }}
                    </small>
                </div>
                <div class="form-group col-12">
                    <label for="inputEmail4">Password Yang Baru</label>
                    <input
                        type="password"
                        class="form-control"
                        id="inputEmail4"
                        placeholder="Password Yang Baru"
                        v-model="update_password.password"
                        :class="[errors.password ? 'is-invalid' : '']"
                    />
                    <small class="text-danger" v-if="errors.password">
                      {{ errors.password[0] }}
                    </small>
                </div>
                <div class="form-group col-12">
                    <label for="inputEmail4">Konfirmasi Password</label>
                    <input
                        type="password"
                        class="form-control"
                        id="inputEmail4"
                        placeholder="Konfirmasi Password"
                        v-model="update_password.password_confirmation"
                        :class="[errors.password_confirmation ? 'is-invalid' : '']"
                    />
                    <small class="text-danger" v-if="errors.password_confirmation">
                      {{ errors.password_confirmation[0] }}
                    </small>
                </div>
            </div>
            <!--/form-row-->

            <button class="btn btn-danger" @click="updatePassword"> 
                Simpan
            </button>
        </div>
        <!--/card-body-->
    </div>
    <!--/card-container-->
</template>

<script>
export default {
    data() {
        return {
            update_password: {
                old_password: "",
                password: "",
                password_confirmation: ""
            },
            errors: []
        };
    },
  methods: {
    updatePassword(){
      axios.put('/account/update-password',this.update_password).then(res => {
          this.errors = [];
          this.update_password.old_password = "";
          this.update_password.password = "";
          this.update_password.password_confirmation = "";
          this.$toast.success(res.data.status);
      }).catch(err => {
        this.errors = err.response.data.errors;
      })
    }
  }
};
</script>
