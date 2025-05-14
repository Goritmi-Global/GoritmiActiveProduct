<template>
    <div>
        <div class="card c-form-card">
            <div class="card-header c-form-card-header">
                {{ translate("Global Seo Section") }}
            </div>
            <div class="card-body pt-4">
                <!-- Floating Labels Form -->
                <form @submit.prevent="submit">
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div>
                                <label for="title"
                                    >{{ translate("Title") }}
                                </label>
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
                                
                            </div>
                            <div
                                class="invalid-feedback animated fadeIn"
                                v-if="formErrors.title"
                            >
                                {{ formErrors.title[0] }}
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div>
                                <label for="keywords"
                                    >{{ translate("Keywords") }}
                                </label>
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
                                
                            </div>
                            <div
                                class="invalid-feedback animated fadeIn"
                                v-if="formErrors.keywords"
                            >
                                {{ formErrors.keywords[0] }}
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <div>
                                <label for="description">{{
                                    translate("Description")
                                }}</label>
                                <textarea
                                    class="form-control"
                                    id="description"
                                    placeholder="Answer"
                                    :class="{
                                        'invalid-bg': formErrors.description,
                                    }"
                                    v-model="form.description"
                                    rows="6"
                                    style="height: 100px"
                                >
                                </textarea>
                                
                            </div>
                            <div
                                class="invalid-feedback animated fadeIn"
                                v-if="formErrors.description"
                            >
                                {{ formErrors.description[0] }}
                            </div>
                        </div>

                        <div class="col-12 col-md-6">
                            <ImageZooming
                                v-if="exist_image"
                                :file="exist_image"
                                :width="100"
                            />
                            <br />

                            <input
                                class="form-control c-file-padding"
                                :class="{ 'invalid-bg': formErrors.image }"
                                type="file"
                                accept=".jpg, .jpeg, .png"
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
            Seo: "",
            exist_image: "",

            form: {
                title: "",
                keywords: "",
                description: "",
                image: "",
            },
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
                    this.form.keywords = response.data.keyword;
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
                    // this.$inertia.get(route("seo"));
                    this.fatchSeo();
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
