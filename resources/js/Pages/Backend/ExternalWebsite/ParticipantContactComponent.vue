<template>
    <div>
        <div class="card c-form-card">
            <div class="card-header c-form-card-header">
                {{ translate("Services Tickets") }}
            </div>
            <div class="card-body pt-4">
                <section class="section">
            <div class="card">
                <div class="card-body pt-4">
                    <!-- <h5 class="card-title">Table with stripped rows</h5> -->

                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <div class="card">
                            <div class="card-body pt-4">
                                <div class="d-flex justify-content-between">
                                    <!-- Floating Labels Form -->

                                    <div class="row g-3">
                                        <div class="input-group">
                                            <div class="form-outline">
                                                <input
                                                    type="text"
                                                    v-model="form.first_name"
                                                    class="form-control"
                                                    id="first_name"
                                                    :placeholder="
                                                        translate(
                                                            'Search first name'
                                                        )
                                                    "
                                                    :class="{
                                                        'invalid-bg':
                                                            formErrors.first_name,
                                                    }"
                                                />

                                                <div
                                                    class="invalid-feedback animated fadeIn"
                                                    v-if="formErrors.first_name"
                                                >
                                                    {{
                                                        formErrors.first_name[0]
                                                    }}
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
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center">
                                            #
                                        </th>
                                        <th scope="col">
                                            {{ translate("Name") }}
                                        </th>
                                        <th scope="col">
                                            {{ translate("Email") }}
                                        </th>
                                        <th scope="col">
                                            {{ translate("Source") }}
                                        </th>
                                        <th scope="col">
                                            {{ translate("Phone") }}
                                        </th>
                                        <th scope="col">
                                            {{ translate("Country") }}
                                        </th>
                                        <th scope="col">
                                            {{ translate("Status") }}
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(service, index) in services"
                                        :key="service.id"
                                    >
                                        <th class="text-center">
                                            {{ index + 1 }}
                                        </th>
                                        <td>
                                            <Link
                                                class="fs-6 theme-text-color"
                                                :title="translate('Details')"
                                                :href="
                                                    route(
                                                        'service.ticket.details',
                                                        service.id
                                                    )
                                                "
                                            >
                                                {{ service.first_name }}
                                                {{ service.last_name }}
                                            </Link>
                                        </td>

                                        <td>{{ service.email }}</td>
                                        <td v-if="service.service_id">
                                            <a
                                                class="fs-6 theme-text-color  truncated-text"
                                                :title="translate('Details')"
                                                :href="
                                                    route(
                                                        'service.details',
                                                        service.service_id
                                                    )
                                                "
                                                target="_blank"
                                            >
                                                {{ service.service.name }}</a
                                            >
                                        </td>
                                        <td v-else>
                                            {{ service.message_page }}
                                        </td>
                                        <td>{{ service.phone }}</td>
                                        <td>{{ service.country }}</td>
                                        <td>{{ service.status }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="text-center">
                            <button
                                v-if="pagination.next_page_url"
                                @click="search(pagination.next_page_url)"
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
import Multiselect from "@vueform/multiselect";
export default {
    layout: Master,
    components: { Multiselect },
    created() {
        this.search(this.default_url);
    },
    data() {
        return {
            default_url: route("api.fetch.services.tickets"),
            services: [],
            deleteId: null,

            form: {
                first_name: "",
            },

            formErrors: [],
            formStatus: 1,
            replyFormStatus: 1,
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

            if (this.form.first_name == null) {
                formData.append("first_name", "");
            } else {
                formData.append("first_name", this.form.first_name);
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
                        this.services = response.data.services_tickets.data;
                        this.service_ids = response.data.services_tickets_ids;

                        this.firstTimeLoadCheck = 0;
                    } else {
                        this.services = this.services.concat(
                            response.data.services_tickets.data
                        );
                        this.service_ids = this.service_ids.concat(
                            response.data.services_tickets_ids
                        );
                    }

                    this.makePagination(response.data.services_tickets);
                })
                .catch((error) => {
                    this.formStatus = 1;
                    toastr.error(error.response.data.message);
                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },

        deleteThis(id) {
            axios
                .delete(route("api.service.delete", id), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    toastr.success(
                        this.translate("Service deleted successfully.")
                    );
                    this.search(null);
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
