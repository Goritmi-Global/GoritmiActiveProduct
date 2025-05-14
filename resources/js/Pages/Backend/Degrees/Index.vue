<template>
    <main class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1>{{ translate("Degree") }}</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('dashboard')">{{
                                translate("Home")
                            }}</Link>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ translate("Degree") }}
                        </li>
                    </ol>
                </nav>
            </div>
            <button
                class="btn btn-success"
                data-bs-toggle="modal"
                data-bs-target="#degreeCreateUpdate"
                @click="clearAll"
            >
                <i class="bi bi-plus-lg"></i>
                {{ translate("Add new degree") }}
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
                                    v-model="searchForm.degree"
                                    class="form-control c-searchbox-radius"
                                    id="degree"
                                    :placeholder="translate('Search by degree')"
                                    :class="{
                                        'invalid-bg': formErrors.degree,
                                    }"
                                />

                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.degree"
                                >
                                    {{ formErrors.degree[0] }}
                                </div>
                            </div>

                            <button
                                v-if="formStatus == 1"
                                @click="fetchDegrees(null)"
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

                    <!-- Table with stripped rows -->
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">#</th>
                                <th scope="col">
                                    {{ translate("Degree") }}
                                </th>
                                <th scope="col" class="text-center">
                                    {{ translate("Actions") }}
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(degree, index) in Degrees"
                                :key="degree.id"
                            >
                                <th class="text-center">{{ index + 1 }}</th>
                                <td>{{ degree.degree }}</td>

                                <td class="text-center">
                                    <div class="btn-group">
                                        <Link
                                            type="button"
                                            class="btn btn-sm fs-6"
                                            :title="translate('Edit')"
                                            data-bs-toggle="modal"
                                            data-bs-target="#degreeCreateUpdate"
                                            @click="EditDegree(degree.id)"
                                        >
                                            <i class="bi bi-pencil"></i>
                                        </Link>
                                        <!-- <button
                                            type="button"
                                            class="btn btn-sm fs-6"

                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteModal"
                                            :title="translate('Delete')"
                                            @click="passDeleteId(degree.id)"
                                        >
                                            <i class="bi bi-trash"></i>
                                        </button> -->
                                        <DeleteModal
                                            :deleteId="degree.id"
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
                            @click="fetchDegrees(pagination.next_page_url)"
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
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </section>
    </main>
    <!-- End #main -->

    <!-- delete Modal -->
    <div class="modal fade" id="degreeCreateUpdate" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header c-bg-theme-purple">
                    <h1 v-if="process == 'create'" class="modal-title fs-5">
                        {{ translate("New degree") }}
                    </h1>
                    <h1 v-else class="modal-title fs-5">
                        {{ translate("Update degree") }}
                    </h1>
                </div>
                <div class="modal-body">
                    <div class="block-content">
                        <!-- Floating Labels Form -->

                        <div class="row g-3">
                            <div class="col-12 col-md-12">
                                <input type="hidden" v-model="form.degree_id" />
                                <div>
                                    <label for="degree"
                                        >{{ translate("Degree") }}
                                    </label>
                                    <input
                                        type="text"
                                        class="form-control"
                                        id="degree"
                                        :class="{
                                            'invalid-bg': formErrors.degree,
                                        }"
                                        v-model="form.degree"
                                    />
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="formErrors.degree"
                                >
                                    {{ formErrors.degree[0] }}
                                </div>
                            </div>

                            <br />
                            <div class="mt-2">
                                <button
                                    type="button"
                                    @click="submit"
                                    class="btn btn-success"
                                    v-if="formStatus == 1"
                                >
                                    {{ translate("Save") }}
                                </button>
                                <button
                                    class="btn btn-success"
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
                            data-bs-target="#degreeCreateUpdate"
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
        this.fetchDegrees(this.default_url);
    },
    data() {
        return {
            default_url: route("api.admin.all.degrees"),
            searchForm: {
                degree: "",
            },
            Degrees: [],
            deleteId: null,
            form: {
                degree: "",
                degree_id: "",
            },
            process: "create",
            formStatus: 1,
            formErrors: [],

            firstTimeLoadCheck: 0,
            loaderForTimes: 1,
            pagination: {},
            loadMoreStatus: 1,
        };
    },
    methods: {
        fetchDegrees(page_url) {
            if (!page_url) {
                this.firstTimeLoadCheck = 1;
                this.formStatus = 0;
            } else {
                this.loadMoreStatus = 0;
            }
            let formData = new FormData();
            formData.append("degree", this.searchForm.degree);
            page_url = page_url || this.default_url;
            axios
                .post(page_url, formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    if (this.firstTimeLoadCheck == 1) {
                        this.Degrees = response.data.data;
                        this.firstTimeLoadCheck = 0;
                        this.formStatus = 1;
                    } else {
                        this.Degrees = this.Degrees.concat(response.data.data);
                        this.loadMoreStatus = 1;
                        this.formStatus = 1;
                    }
                    this.makePagination(response.data);
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
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
        submit() {
            this.formStatus = 0;
            axios
                .post(route("api.admin.degree.store"), this.form, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((data) => {
                    this.$refs.closeModal.click();
                    toastr.success(this.translate("Degree save successfully"));
                    this.fetchDegrees();
                    this.formStatus = 1;
                    this.process = "";
                    this.form.degree_id = "";
                    this.form.degree = "";
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);

                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },
        EditDegree(id) {
            this.formErrors = [];
            axios
                .get(route("api.admin.degree.show", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.form.degree = response.data.degree;
                    this.form.degree_id = response.data.id;
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
                .delete(route("api.admin.degree.delete", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    toastr.success(
                        this.translate("Degree deleted successfully")
                    );
                    this.fetchDegrees();
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        clearAll() {
            this.process = "create";
            this.form.degree_id = "";
            this.form.degree = "";
            this.formErrors = [];
        },
    },
};
</script>

<style>
.table td,
.table th {
    vertical-align: middle;
}
</style>
