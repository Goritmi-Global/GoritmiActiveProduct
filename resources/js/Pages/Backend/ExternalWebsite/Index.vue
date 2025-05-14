<template>
    <main class="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1>{{ translate("Seo") }}</h1>
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
                        <li class="breadcrumb-item active">FAQ's</li>
                    </ol>
                </nav>
            </div>
            <Link class="btn btn-primary" :href="route('seo.add')"
                ><i class="bi bi-plus-lg"></i> Add New FAQ</Link
            >
        </div>
        <!-- End Page Title -->
        <section class="section">
            <div class="card">
                <div class="card-body pt-4">
                    <!-- <h5 class="card-title">Table with stripped rows</h5> -->

                    <!-- Table with stripped rows -->
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th scope="col" class="text-center">#</th>
                                    <th scope="col">Title</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Order#</th>
                                    <th scope="col" class="text-center">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(question, index) in FAQS"
                                    :key="question.id"
                                >
                                    <th class="text-center">{{ index + 1 }}</th>
                                    <td>{{ question.question }}</td>
                                    <td>{{ question.answer }}</td>
                                    <td>{{ question.ordering }}</td>

                                    <td class="text-center">
                                        <div class="btn-group">
                                            <Link
                                                type="button"
                                                class="btn btn-sm fs-6"
                                                title="Edit"
                                                :href="
                                                    route(
                                                        'admin.faqs.edit',
                                                        question.id
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
                                                    passDeleteId(question.id)
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
                        <h2>Are you sure?</h2>

                        <button
                            type="button"
                            class="btn btn-sm btn-success me-2"
                            data-bs-dismiss="modal"
                            @click="deleteThis"
                        >
                            Yes, delete it!
                        </button>

                        <button
                            type="button"
                            class="btn btn-sm btn-dark"
                            data-bs-dismiss="modal"
                        >
                            Close
                        </button>
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
        this.fatchFAQs();
    },
    data() {
        return {
            FAQS: [],
            deleteId: null,
        };
    },
    methods: {
        fatchFAQs() {
            axios
                .get(route("api.admin.faqs"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.FAQS = response.data;
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
                .delete(route("api.admin.faqs.delete", this.deleteId), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    toastr.success(
                        this.translate("Question deleted successfully.")
                    );
                    this.fatchFAQs();
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
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
