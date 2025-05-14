<template>
    <main class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1>
                    {{ translate("Bussiness Settings updae") }}
                </h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('dashboard')">{{
                                $page.props.website_name
                            }}</Link>
                        </li>
                        <li class="breadcrumb-item">
                            <Link :href="route('dashboard')">Home</Link>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ translate("Bussiness Settings") }}
                        </li>
                    </ol>
                </nav>
            </div>
            <!-- <Link class="btn btn-primary" :href="route('admin.teams')">{{ translate('Add New Seo') }} </Link> -->
        </div>

        <!-- End Page Title -->
        <section class="section">
            <div class="card">
                <div class="card-body pt-4">
                    <!-- <h5 class="card-title">Add New FAQ</h5> -->

                    <!-- Floating Labels Form -->
                    <form @submit.prevent="submit">
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="title"
                                        placeholder="Title"
                                        :class="{
                                            'invalid-bg': formErrors.title,
                                        }"
                                        v-model="form.title"
                                    />
                                    <label for="title"
                                        >{{ translate("Title") }}
                                    </label>
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.title"
                                >
                                    {{ formErrors.title[0] }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div class="form-floating">
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="keywords"
                                        placeholder="Title"
                                        :class="{
                                            'invalid-bg': formErrors.keywords,
                                        }"
                                        v-model="form.keywords"
                                    />
                                    <label for="keywords"
                                        >{{ translate("Keywords") }}
                                    </label>
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.keywords"
                                >
                                    {{ formErrors.keywords[0] }}
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="form-floating">
                                    <textarea
                                        class="form-control"
                                        id="description"
                                        placeholder="Answer"
                                        :class="{
                                            'invalid-bg':
                                                formErrors.description,
                                        }"
                                        v-model="form.description"
                                        rows="6"
                                        style="height: 100px"
                                    >
                                    </textarea>
                                    <label for="description">{{
                                        translate("Description")
                                    }}</label>
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.description"
                                >
                                    {{ formErrors.description[0] }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <img
                                    :src="'/storage/' + exist_image"
                                    alt="Profile"
                                    class="img-fluid img-thumbnail rounded"
                                    @error="setAltImg"
                                    width="100"
                                />
                                <input
                                    class="form-control c-file-padding"
                                    :class="{ 'invalid-bg': formErrors.image }"
                                    type="file"
                                    @input="form.image = $event.target.files[0]"
                                    id="image"
                                />

                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.image"
                                >
                                    {{ formErrors.image[0] }}
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
import Master from "@/Pages/Backend/Layouts/Master";
import Multiselect from "@vueform/multiselect";
export default {
    layout: Master,
    props: ["records"],
    created() {
        this.fatchSeo();
    },
    components: {
        Multiselect,
    },
    data() {
        return {
            form: {
                title: "",
                keywords: "",
                description: "",
                image: "",
            },
            formErrors: [],
            Seo: "",
        };
    },
    methods: {
        fatchSeo() {
            axios
                .get(route("api.seo"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.form.title = response.data.title;
                    this.form.keyword = response.data.keyword;
                    this.form.description = response.data.description;
                    this.exist_image = response.data.image;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },

        submit() {
            let formData = new FormData();
            formData.append("title", this.form.title);
            formData.append("keywords", this.form.keywords);
            formData.append("description", this.form.description);
            formData.append("image", this.form.image);

            axios
                .post(route("api.seo.store"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    toastr.success(this.translate("Seo Updated successfully."));
                    this.$inertia.get(route("seo"));
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);

                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },

        //    fetchSeo(){
        //         axios.get(route('api.seo.show', this.faqId),{
        //             headers: {"Authorization" : 'Bearer ' + this.$page.props.auth_token}
        //         }).then((response) => {
        //             this.form.question = response.data.question;
        //             this.form.answer = response.data.answer;
        //             this.form.ordering = response.data.ordering;

        //         }).catch(error => {
        //             toastr.error(error.response.data.message);
        //         });
        //     },
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
