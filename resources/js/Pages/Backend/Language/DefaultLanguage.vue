<template>
    <section class="row section">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body pt-4">
                    <h5 class="card-title">
                        {{ translate("Default Language") }}
                    </h5>

                    <!-- Floating Labels Form -->
                    <form @submit.prevent="submit">
                        <div class="row g-3">
                            <div class="col-12 col-md-12">
                                <Multiselect
                                    class="form-select"
                                    :options="code_options"
                                    :searchable="true"
                                    :class="{ 'invalid-bg': formErrors.status }"
                                    placeholder="Choose Default Language"
                                    v-model="form.default_language"
                                />
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.code"
                                >
                                    {{ formErrors.code[0] }}
                                </div>
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <button type="submit" class="btn btn-success mt-3">
                                Save
                            </button>
                        </div>
                    </form>
                    <!-- End floating Labels Form -->
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import Multiselect from "@vueform/multiselect";
export default {
    components: {
        Multiselect,
    },
    created() {
        this.fetchLanguages();
        this.fatchDefaultLanguage();
    },
     
    data() {
        return {
            form: {
                default_language: this.$page.props.user_language ?? "",
            },
            code_options: {},
            formErrors: [],
        };
    },
    methods: {
        fetchLanguages() {
            axios
                .get(route("api.languages.data"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.code_options = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },

        fatchDefaultLanguage() {
            axios
                .get(route("api.fetch.default.language"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.form.default_language = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },

        submit() {
            axios
                .post(route("api.make.default.language"), this.form, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    toastr.success("Default Language Changed Successfully.");
                    // this.$inertia.get(route('admin.languages'));
                    this.$inertia.reload();
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

<style></style>
