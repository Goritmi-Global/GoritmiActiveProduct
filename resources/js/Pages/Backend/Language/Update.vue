`
<template>
          <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1>{{ translate("Update Language") }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('dashboard')">{{
                                $page.props.website_name
                            }}</Link>
                        </li>
                        
                        <li class="breadcrumb-item">
                            {{ translate("Languages") }}
                        </li>
                        <li class="breadcrumb-item active">
                            {{ translate("Update") }}
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="mb-3 justify-content-end">
                <Link class="btn btn-success" :href="route('languages')"
                    >{{ translate("Languages") }}
                </Link>
            </div>
        </div>

        <!-- End Page Title -->
        <section class="section">
            <div class="card">
                <div class="card-body pt-4">
                    <h5 class="card-title">
                        {{ translate("Update Language") }}
                    </h5>

                    <!-- Floating Labels Form -->
                    <form @submit.prevent="submit">
                        <div class="row g-3">
                            <div
                                class="col-12 col-md-12"
                                
                            >
                                <label>{{ translate("Choose Status") }}</label>
                                <br />
                                <div class="d-inline-block me-2">
                                    {{ translate("OFF") }}
                                </div>
                                <div
                                    class="form-check form-switch d-inline-block"
                                >
                                    <input
                                        class="form-check-input"
                                        type="checkbox"
                                        :class="{
                                            'invalid-bg': formErrors.status,
                                        }"
                                        v-model="form.status"
                                        checked
                                        :disabled="form.name.toLowerCase() === 'english' && form.status == 1"  
                                    />
                                    <label class="form-check-label">{{
                                        translate("ON")
                                    }}</label>
                                </div>

                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.status"
                                >
                                    {{ formErrors.status[0] }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div>
                                    <label for="">{{
                                        translate("Name")
                                    }}</label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        :class="{
                                            'invalid-bg': formErrors.name,
                                        }"
                                        v-model="form.name"
                                    />
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.name"
                                >
                                    {{ formErrors.name[0] }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="">{{
                                    translate("Choose Country")
                                }}</label>
                                <Multiselect
                                    v-model="form.code"
                                    :options="code_options"
                                    :searchable="true"
                                    :class="{ 'invalid-bg': formErrors.status }"
                                    placeholder="Choose Country"
                                />
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.code"
                                >
                                    {{ formErrors.code[0] }}
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
        </section>
    </main>
    <!-- End #main -->
</template>

<script>
import Master from "@/Pages/Backend/Layout/Master.vue";
import Multiselect from "@vueform/multiselect";
export default {
    layout: Master,
    components: {
        Multiselect,
    },
    props: ["language_id"],
    created() {
        this.loadCountries();
        this.loadLanguage();
    },
    data() {
        return {
            form: {
                id: this.language_id,
                name: "",
                code: "",
                status: "",
            },
            code_options: [],
            formErrors: [],
        };
    },
    methods: {
        loadCountries() {
            axios
                .get(route("api.countries.pluck"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.code_options = response.data;
                    // toastr.success("Language added successfully.");
                    // this.$inertia.get(route('admin.languages'));
                });
        },
        loadLanguage() {
            axios
                .get(route("api.languages.show", this.language_id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.form.name = response.data.name;
                    this.form.code = response.data.code;
                    this.form.status = response.data.status;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        submit() {
            let formData = new FormData();

            formData.append("id", this.form.id);
            formData.append("name", this.form.name ?? "");
            formData.append("code", this.form.code ?? "");
            if (this.form.status == "") {
                formData.append("status", false);
            } else {
                formData.append("status", this.form.status);
            }
            axios
                .post(route("api.languages.update"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    toastr.success("Language added successfully.");
                    this.$inertia.get(route("languages"));
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
</style>
