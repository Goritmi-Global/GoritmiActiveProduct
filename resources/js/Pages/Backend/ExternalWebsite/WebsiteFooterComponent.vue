<template>
    <div>
        <div class="card c-form-card">
            <div class="card-header c-form-card-header">
                {{ translate("Website Footer section") }}
            </div>
            <div class="card-body pt-4">
                <!-- <h5 class="card-title">Add New FAQ</h5> -->

                <!-- Floating Labels Form -->
                <form @submit.prevent="submit">
                    <div class="row g-3">
                        <div class="col-12 col-md-12">
                            <div >
                                <label for="description">{{
                                    translate("Footer information")
                                }}</label>
                                <textarea 
                                    class="form-control"
                                    id="website_footer"
                                    :placeholder="translate('Footer information')"
                                    :class="{
                                        'invalid-bg': formErrors.website_footer,
                                    }"
                                    v-model="form.website_footer"
                                    rows="6"
                                     
                                >
                                </textarea>
                                
                            </div>
                            <div
                                class="invalid-feedback animated fadeIn"
                                v-if="formErrors.website_footer"
                            >
                                {{ formErrors.website_footer[0] }}
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
    emits: ["refetchParentDetails"],
    created() {
        this.fetchFooterInformation();
    },
    data() {
        return {
            formStatus: "Yes",
            formErrors: [],

            form: {
                website_footer: "",
            },
        };
    },
    methods: {
        submit() {
            axios
                .post(route("api.website-footer.store"), this.form, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    toastr.success(
                        this.translate("Website footer section updated successfully.")
                    );
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);

                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },

        fetchFooterInformation() {
            axios
                .get(route("api.fetch.website-footer"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.form.website_footer = response.data.value;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
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
