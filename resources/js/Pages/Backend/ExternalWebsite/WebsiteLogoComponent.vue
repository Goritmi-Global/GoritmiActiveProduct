<template>
    <div>
        <div class="card c-form-card">
            <div class="card-header c-form-card-header">
                {{ translate("Logo Section") }}
            </div>
            <div class="card-body pt-4">
                <!-- Floating Labels Form -->
                <form @submit.prevent="submit">
                    <div class="row g-3">
                        
                        <div class="col-12 col-md-6">
                            <ImageZooming
                                v-if="exist_image"
                                :file="exist_image"
                                :width="100"
                            />
                            <br>
                            
                            <input
                                class="form-control c-file-padding"
                                :class="{ 'invalid-bg': formErrors.logo }"
                                type="file"
                                @input="form.logo = $event.target.files[0]"
                                id="logo"
                            />

                            <div
                                class="invalid-feedback animated fadeIn"
                                v-if="formErrors.logo"
                            >
                                {{ formErrors.logo[0] }}
                            </div>
                             
                            
                        </div>
                    </div>

                    <button type="submit" class="btn btn-success mt-3">
                        {{ translate("Save") }}
                    </button>
                </form>
                <!-- End floating Labels Form -->
            </div>
        </div>
    </div>
</template>

<script>
// import Master from "@/Pages/Backend/Layouts/Master";
export default {
    // layout: Master,
    created() {
        this.fatchSeo();
    },
    data() {
        return {
            formStatus: "Yes",
            formErrors: [],
            // Seo: "",
            exist_image: "",

            form: { 
                logo: "",
            },
        };
    },
    methods: {
        fatchSeo() {
            axios
                .get(route("api.logo.feth"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => { 
                    this.exist_image = response.data.image; 
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },

        submit() {
            let formData = new FormData(); 
            formData.append("logo", this.form.logo);

            axios
                .post(route("api.logo.store"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    toastr.success(this.translate("Logo updated successfully."));
                    this.$inertia.get(route("external-website"));
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },
    },
};
</script>

<style>
@import "@vueform/multiselect/themes/default.css";

.c-file-padding {
    padding: 1rem 0.75rem !important;
}

.invalid-feedback {
    display: block !important;
}

/* .invalid-bg {
    border-color: #f8d4d4 !important;
    background-color: #f8d4d4 !important;
} */

#CodeSelect {
    min-height: 58px;
}

.card {
    overflow: visible;
}

.remove-status {
    margin-left: 15px !important;
    margin-top: 5px !important;
}
</style>
