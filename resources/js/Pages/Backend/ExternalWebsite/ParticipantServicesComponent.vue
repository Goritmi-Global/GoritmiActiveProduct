<template>
    <div>
        <div class="card c-form-card">
            <div class="card-header c-form-card-header">
                {{ translate("Services section") }}
            </div>
            <div class="card-body pt-4">
                <section class="section">
                    <div class="card">
                        <div class="card-body pt-4">
                            <div
                                class="pagetitle d-flex justify-content-between"
                            >
                                <div>
                                    <div class="row g-3">
                                        <div class="input-group">
                                            <div class="form-outline">
                                                <input
                                                    type="text"
                                                    v-model="form.name"
                                                    class="form-control"
                                                    id="name"
                                                    :placeholder="
                                                        translate(
                                                            'Search service name'
                                                        )
                                                    "
                                                    :class="{
                                                        'invalid-bg':
                                                            formErrors.name,
                                                    }"
                                                />

                                                <div
                                                    class="invalid-feedback animated fadeIn"
                                                    v-if="formErrors.name"
                                                >
                                                    {{ formErrors.name[0] }}
                                                </div>
                                                <!-- <label class="form-label" for="form1">Search</label> -->
                                            </div>
                                            <button
                                                @click="search(null)"
                                                class="btn btn-success"
                                            >
                                                <i class="bi bi-search"></i>
                                            </button>

                                            <button
                                                class="btn"
                                                type="button"
                                                v-if="formStatus != 1"
                                            >
                                                <span
                                                    class="spinner-border spinner-border-sm"
                                                    role="status"
                                                    aria-hidden="true"
                                                ></span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <Link
                                        class="btn btn-success"
                                        :href="route('new.service.add')"
                                        ><i class="bi bi-plus-lg"></i>
                                        {{ translate("Add New Service") }}
                                    </Link>
                                </div>
                            </div>
                            <!-- <h5 class="card-title">Table with stripped rows</h5> -->

                            <!-- Table with stripped rows -->
                            <div class="table-responsive">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="text-center"
                                                >
                                                    #
                                                </th>
                                                <th scope="col">
                                                    {{ translate("Name") }}
                                                </th>
                                                <th scope="col">
                                                    {{ translate("Type") }}
                                                </th>
                                                <th scope="col">
                                                    {{ translate("Image") }}
                                                </th>

                                                <!-- <th scope="col" class="text-center">
                                            {{ translate("Actions") }}
                                        </th> -->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr
                                                v-for="(
                                                    service, index
                                                ) in services"
                                                :key="service.id"
                                            >
                                                <th class="text-center">
                                                    {{ index + 1 }}
                                                </th>
                                                <td>
                                                    <Link
                                                        class="fs-6 theme-text-color"
                                                        :title="
                                                            translate('Details')
                                                        "
                                                        :href="
                                                            route(
                                                                'service.details',
                                                                service.id
                                                            )
                                                        "
                                                    >
                                                        {{ service.name }}
                                                    </Link>
                                                </td>
                                                <td>{{ service.type }}</td>

                                                <td>
                                                    <ImageZooming
                                                        :file="service.image"
                                                        :width="100"
                                                    />
                                                </td>
                                                <!-- <td class="text-center">
                                            <DeleteModal
                                                :deleteId="service.id"
                                                @deleteThis="deleteThis"
                                            ></DeleteModal>
                                        </td> -->
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="text-center">
                                    <button
                                        v-if="pagination.next_page_url"
                                        @click="
                                            search(pagination.next_page_url)
                                        "
                                        class="btn btn-success"
                                    >
                                        {{ translate("Load More") }}
                                    </button>
                                </div>
                            </div>
                            <!-- End Table with stripped rows -->
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</template>

<script>
import Master from "@/Pages/Backend/Layouts/Master";
export default {
    layout: Master,
    created() {
        this.search(this.default_url);
    },
    data() {
        return {
            default_url: route("api.fetch.service"),
            services: [],
            deleteId: null,

            form: {
                name: "",
            },

            formErrors: [],
            formStatus: 1,
            downloadBtnStatus: 1,
            service_ids: [],
            pagination: {},
            firstTimeLoadCheck: 0,
        };
    },
    methods: {
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

        search(page_url) {
            // if no page url then it means that search button is clicked
            if (!page_url) {
                this.firstTimeLoadCheck = 1;
            }
            page_url = page_url || this.default_url;

            this.formStatus = 0;
            let formData = new FormData();

            if (this.form.name == null) {
                formData.append("name", "");
            } else {
                formData.append("name", this.form.name);
            }
            // formData.append("status", this.form.status);

            axios
                .post(page_url, formData, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.formStatus = 1;

                    if (this.firstTimeLoadCheck == 1) {
                        this.services = response.data.services.data;
                        this.service_ids = response.data.service_ids;

                        this.firstTimeLoadCheck = 0;
                    } else {
                        this.services = this.services.concat(
                            response.data.services.data
                        );
                        this.service_ids = this.service_ids.concat(
                            response.data.service_ids
                        );
                    }

                    this.makePagination(response.data.services);
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },

        // deleteThis(id) {
        //     axios
        //         .delete(route("api.service.delete", id), {
        //             headers: {
        //                 Authorization: "Bearer " + this.$page.props.auth_token,
        //             },
        //         })
        //         .then(() => {
        //             toastr.success(
        //                 this.translate("Service deleted successfully.")
        //             );
        //             this.search(null);
        //         })
        //         .catch((error) => {
        //             toastr.error(error.response.data.message);
        //         });
        // },
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
