<template>
    <main class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1>{{ translate("Specialty") }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('dashboard')">{{
                                translate("Home")
                            }}</Link>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ translate("Specialty") }}
                        </li>
                    </ol>
                </nav>
            </div>
            <button
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#specialitieCreateUpdate"
                @click="clearAll"
            >
                <i class="bi bi-plus-lg"></i>
                {{ translate("Add new specialty") }}
            </button>
        </div>
        <!-- End Page Title -->
        <section class="section">
            <div class="card">
                <div class="card-body pt-4">
                    <div class="row g-3 p-3">
                        <div class="input-group">
                            <div class="form-outline col-md-5">
                                <input
                                    type="text"
                                    v-model="searchForm.speciality"
                                    class="form-control c-searchbox-radius"
                                    id="speciality"
                                    :placeholder="
                                        translate('Search by speciality')
                                    "
                                    :class="{
                                        'invalid-bg': formErrors.speciality,
                                    }"
                                />

                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.speciality"
                                >
                                    {{ formErrors.speciality[0] }}
                                </div>
                            </div>

                            <button
                                v-if="formStatus == 1"
                                @click="fetchSpacialities(null)"
                                class="btn btn-success"
                            >
                                <i class="bi bi-search"></i>
                            </button>

                            <button
                                v-else
                                class="btn btn-success"
                                type="button"
                            >
                                <span
                                    class="spinner-border spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                            </button>
                        </div>
                    </div>
                    <!-- <h5 class="card-title">Table with stripped rows</h5> -->

                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">
                                        {{ translate("Specialty") }}
                                    </th>
                                    <th scope="col">
                                        {{ translate("Image") }}
                                    </th>
                                    <th scope="col" class="text-center">
                                        {{ translate("Show") }}
                                    </th>
                                    <th scope="col" class="text-center">
                                        {{ translate("Status") }}
                                    </th>
                                    <th scope="col" class="text-center">
                                        {{ translate("Actions") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(speciality, index) in Spacialities"
                                    :key="speciality.id"
                                >
                                    <th class="text-center">{{ index + 1 }}</th>
                                    <td>{{ speciality.speciality }}</td>
                                    <td class="text-left">
                                        <ImageZooming
                                            :file="speciality.image"
                                            :width="100"
                                        ></ImageZooming>
                                    </td>
                                    <td>
                                        <div
                                            class="d-flex justify-content-center"
                                        >
                                            <div
                                                class="form-check form-switch d-inline-block"
                                            >
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    v-model="
                                                        speciality.show_speciality
                                                    "
                                                    @change="
                                                        showHideSpecialty(
                                                            speciality.id,
                                                            speciality.show_speciality
                                                        )
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div
                                            class="d-flex justify-content-center"
                                        >
                                            <div
                                                class="form-check form-switch d-inline-block"
                                            >
                                                <input
                                                    class="form-check-input"
                                                    type="checkbox"
                                                    v-model="speciality.status"
                                                    @change="
                                                        changeStatus(
                                                            speciality.id,
                                                            speciality.status
                                                        )
                                                    "
                                                />
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <Link
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                :title="translate('Edit')"
                                                data-bs-toggle="modal"
                                                data-bs-target="#specialitieCreateUpdate"
                                                @click="
                                                    EditSpeciality(
                                                        speciality.id
                                                    ),
                                                        clearAll()
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </Link>

                                            <DeleteModal
                                                :deleteId="speciality.id"
                                                @deleteThis="deleteThis"
                                            ></DeleteModal>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center p-2">
                            <button
                                v-if="pagination.next_page_url"
                                @click="
                                    fetchSpacialities(pagination.next_page_url)
                                "
                                class="btn btn-success"
                            >
                                {{ translate("Load more") }}
                                <span
                                    v-if="loadMoreStatus == 0"
                                    class="spinner-border text-white spinner-border-sm"
                                    role="status"
                                    aria-hidden="true"
                                ></span>
                            </button>
                        </div>
                    </div>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </section>
    </main>
    <!-- End #main -->

    <!--  Modal -->
    <div class="modal fade" id="specialitieCreateUpdate" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header c-bg-theme-purple">
                    <h1 v-if="process == 'create'" class="modal-title fs-5">
                        {{ translate("New specialty") }}
                    </h1>
                    <h1 v-else class="modal-title fs-5">
                        {{ translate("Update specialty") }}
                    </h1>
                </div>
                <div class="modal-body">
                    <div class="block-content">
                        <!-- Floating Labels Form -->

                        <div class="row g-3">
                            <div class="col-12 col-md-12">
                                <input
                                    type="hidden"
                                    v-model="form.speciality_id"
                                />
                                <div>
                                    <label for="specialty"
                                        >{{ translate("Specialty") }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="speciality"
                                        :class="{
                                            'invalid-bg': formErrors.speciality,
                                        }"
                                        v-model="form.speciality"
                                    />
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.speciality"
                                >
                                    {{ formErrors.speciality[0] }}
                                </div>
                            </div>

                            <div>
                                <CropperOffCanvas
                                    :ratio="1"
                                    @croppedImg="croppedImgSubmit"
                                    :width="100"
                                />
                                <br />
                                <img
                                    v-if="existingImage"
                                    :src="existingImage"
                                    @error="setAltImg"
                                />
                            </div>
                            <b for="section-title"
                                        >{{ translate("Search Engine Optimization Details") }}
                        </b>
                            <div class="col-12 col-md-12">
                                <div>
                                    <label for="seo_title"
                                        >{{ translate("Seo Title") }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="seo_title"
                                        :class="{
                                            'invalid-bg': formErrors.seo_title,
                                        }"
                                        v-model="form.seo_title"
                                    />
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.seo_title"
                                >
                                    {{ formErrors.seo_title[0] }}
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div>
                                    <label for="keywords"
                                        >{{ translate("Keywords") }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="seo_keywords"
                                        :class="{
                                            'invalid-bg':
                                                formErrors.seo_keywords,
                                        }"
                                        v-model="form.seo_keywords"
                                    />
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.seo_keywords"
                                >
                                    {{ formErrors.seo_keywords[0] }}
                                </div>
                            </div>

                            <div class="col-12 col-md-12">
                                <div>
                                    <label for="description"
                                        >{{ translate("Seo description") }}
                                    </label>
                                    <textarea
                                        class="form-control"
                                        id="seo_description"
                                        :class="{
                                            'invalid-bg':
                                                formErrors.seo_description,
                                        }"
                                        v-model="form.seo_description"
                                        rows="6"
                                        style="height: 100px"
                                    >
                                    </textarea>
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.seo_description"
                                >
                                    {{ formErrors.seo_description[0] }}
                                </div>
                            </div>

                            <div class="mt-2">
                                <button
                                    type="button"
                                    @click="submit"
                                    class="btn btn-success btn-block"
                                    v-if="formStatus == 1"
                                >
                                    {{ translate("Save") }}
                                </button>
                                <button
                                    class="btn btn-success btn-block"
                                    type="button"
                                    disabled
                                    v-else
                                >
                                    {{ translate("Save") }}
                                    <span
                                        class="spinner-border spinner-border-sm"
                                        role="status"
                                        aria-hidden="true"
                                    ></span>
                                </button>
                            </div>
                        </div>

                        <button
                            hidden="hidden"
                            data-bs-toggle="modal"
                            data-bs-target="#specialitieCreateUpdate"
                            :title="translate('Create')"
                            ref="closeModal"
                        ></button>

                        <!-- End floating Labels Form -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Master from "@/Pages/Backend/Layouts/Master";
export default {
    layout: Master,
    created() {
        this.fetchSpacialities(this.default_url);
    },
    data() {
        return {
            default_url: route("api.admin.all.specialities"),
            Spacialities: [],
            deleteId: null,
            form: {
                speciality: "",
                speciality_id: "",
                image: "",
                seo_title: "",
                seo_keywords: "",
                seo_description: "",
            },
            searchForm: {
                speciality: "",
            },
            process: "create",
            formStatus: 1,
            formErrors: [],
            existingImage: "",
            speciality: {
                status: [],
                show: [],
            },

            firstTimeLoadCheck: 0,
            loaderForTimes: 1,
            pagination: {},
            loadMoreStatus: 1,
        };
    },
    methods: {
        fetchSpacialities(page_url) {
            if (!page_url) {
                this.firstTimeLoadCheck = 1;
                this.formStatus = 0;
            } else {
                this.loadMoreStatus = 0;
            }
            let formData = new FormData();
            formData.append("speciality", this.searchForm.speciality);
            page_url = page_url || this.default_url;
            axios
                .post(page_url, formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    // this.Spacialities = response.data;
                    if (this.firstTimeLoadCheck == 1) {
                        this.Spacialities = response.data.data;
                        this.firstTimeLoadCheck = 0;
                        this.formStatus = 1;
                    } else {
                        this.Spacialities = this.Spacialities.concat(
                            response.data.data
                        );
                        this.loadMoreStatus = 1;
                        this.formStatus = 1;
                    }
                    this.makePagination(response.data);
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        changeStatus(speciality_id, speciality_status) {
            let formData = new FormData();
            formData.append("speciality_id", speciality_id);
            formData.append("status", speciality_status);
            axios
                .post(route("api.admin.speciality.status.change"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    toastr.success(this.translate("Status save successfully"));
                    this.fetchSpacialities();
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },
        showHideSpecialty(speciality_id, speciality_show_status) {
            let formData = new FormData();
            formData.append("speciality_id", speciality_id);
            formData.append("speciality_show_status", speciality_show_status);

            axios
                .post(route("api.admin.speciality.show.hide"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    toastr.success(this.translate("Process save successfully"));
                    this.fetchSpacialities();
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },

        submit() {
            this.formStatus = 0;
            let formData = new FormData();
            formData.append("speciality", this.form.speciality ?? "");
            formData.append("speciality_id", this.form.speciality_id ?? "");
            if (this.form.image == "" || this.form.image == null) {
                formData.append("image", "");
            } else {
                formData.append("image", this.form.image);
            }

            if (this.form.seo_title == null) {
                formData.append("seo_title", "");
            } else {
                formData.append("seo_title", this.form.seo_title);
            }
            if (this.form.seo_keywords == null) {
                formData.append("seo_keywords", "");
            } else {
                formData.append("seo_keywords", this.form.seo_keywords);
            }
            if (this.form.seo_description == null) {
                formData.append("seo_description", "");
            } else {
                formData.append("seo_description", this.form.seo_description);
            }

            axios
                .post(route("api.admin.speciality.store"), formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    this.$refs.closeModal.click();
                    toastr.success(
                        this.translate("Specialty save successfully")
                    );
                    this.fetchSpacialities();
                    this.formStatus = 1;
                    this.process = "";
                    this.form.speciality_id = "";
                    this.form.speciality = "";
                    this.form.image = "";
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);

                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },
        makePagination(res) {
            let pagination = {
                links: res.links,
                current_page: res.current_page,
                last_page: res.last_page,
                next_page_url: res.next_page_url,
                prev_page_url: res.prev_page_url,
            };
            this.pagination = pagination;
        },
        croppedImgSubmit(img) {
            this.form.image = img;
            this.existingImage = img;
        },
        EditSpeciality(id) {
            this.formErrors = [];
            axios
                .get(route("api.admin.speciality.show", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.form.speciality = response.data.speciality;
                    this.form.speciality_id = response.data.id;

                    this.existingImage = response.data.image;

                    this.form.seo_title = response.data.seo_title;
                    this.form.seo_keywords = response.data.seo_keywords;
                    this.form.seo_description = response.data.seo_description;

                    this.process = "update";
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        passDeleteId(id) {
            this.deleteId = id;
        },
        deleteThis(id) {
            axios
                .delete(route("api.admin.speciality.delete", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    toastr.success(
                        this.translate("Spacialty deleted successfully")
                    );
                    this.fetchSpacialities();
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        clearAll() {
            this.process = "create";
            this.form.speciality_id = "";
            this.form.image = "";
            this.existingImage = "";
            this.form.speciality = "";
            this.formErrors = [];
        },
        setAltImg(form) {
            form.target.src = "/images/default.jpg";
        },
    },
};
</script>

<style>
.table td,
.table th {
    vertical-align: middle;
}
img {
    object-fit: cover;
    overflow: hidden;
    height: 200px;
}
</style>
