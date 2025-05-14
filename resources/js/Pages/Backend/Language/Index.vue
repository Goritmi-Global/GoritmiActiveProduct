<template>
    <main id="main" class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1>{{ translate("Language") }}</h1>
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
                            {{ translate("Home") }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>

        <DefaultLanguage />

        <div class="mb-3 d-flex justify-content-end">
            <Link class="btn btn-success" :href="route('languages.add')"
                ><i class="bi bi-plus-lg"></i>
                {{ translate("Add New Language") }}
            </Link>
        </div>
        <!-- End Page Title -->
        <section class="section">
            <div class="card">
                <div class="card-body pt-4">
                    <h5 class="card-title">{{ translate("Languages") }}</h5>

                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">{{ translate("Name") }}</th>
                                    <th scope="col">{{ translate("Code") }}</th>
                                    <th scope="col">
                                        {{ translate("Status") }}
                                    </th>
                                    <th scope="col" class="text-center">
                                        {{ translate("Options") }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(language, index) in languages"
                                    :key="language.id"
                                >
                                    <th class="text-center">{{ index + 1 }}</th>
                                    <td>{{ language.name }}</td>
                                    <td>{{ language.code }}</td>
                                    <td>
                                        {{ language.status ? "ON" : "OFF" }}
                                    </td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <Link
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                title="Translations"
                                                :href="
                                                    route(
                                                        'translations.show',
                                                        language.id
                                                    )
                                                "
                                            >
                                                <i class="bi bi-translate"></i>
                                            </Link>
                                            <Link
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                title="Edit"
                                                :href="
                                                    route(
                                                        'languages.edit',
                                                        language.id
                                                    )
                                                "
                                            >
                                                <i class="bi bi-pencil"></i>
                                            </Link>
                                            <button
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                data-bs-toggle="modal"
                                                data-bs-target="#deleteModal"
                                                title="Delete"
                                                @click="
                                                    passDeleteId(language.id)
                                                "
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table with stripped rows -->
                </div>
            </div>
        </section>
    </main>
    <!-- End #main -->

    <!-- delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="block-content text-center p-4">
                        <h2>
                            {{ translate("Are you sure?") }}
                        </h2>

                        <button
                            type="button"
                            class="btn btn-sm btn-danger me-2"
                            data-bs-dismiss="modal"
                            @click="deleteThis"
                        >
                            {{ translate("Yes, delete it!") }}
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-dark"
                            data-bs-dismiss="modal"
                        >
                            {{ translate("Close") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Master from "@/Pages/Backend/Layout/Master.vue";
import DefaultLanguage from "@/Pages/Backend/Language/DefaultLanguage.vue";

export default {
    layout: Master,
    components: {
        DefaultLanguage,
    },
    created() {
        this.fatchLanguages();
    },
    data() {
        return {
            languages: [],
            deleteId: null,
        };
    },

    methods: {
        fatchLanguages() {
            axios
                .get(route("api.languages"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.languages = response.data;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        passDeleteId(id) {
            this.deleteId = id;
        },
        deleteThis() {
            axios
                .delete(route("api.languages.delete", this.deleteId), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    toastr.success("Language deleted successfully.");
                    this.fatchLanguages();
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
</style>
