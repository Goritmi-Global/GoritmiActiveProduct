<template>
    <div>
        <div class="card c-form-card">
            <div class="card-header c-form-card-header">
                {{ translate("Hero Section") }}
            </div>
            <div class="card-body pt-4">
                <!-- Language Tabs -->
                <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                    <li
                        class="nav-item"
                        role="presentation"
                        v-for="(language, index) in languages"
                        :key="language.id"
                    >
                        <button
                            class="nav-link"
                            :class="{ active: activeTab === index }"
                            @click="activeTab = index"
                            type="button"
                            role="tab"
                            :id="'tab-' + index"
                        >
                            {{ language.name }}
                        </button>
                    </li>
                </ul>

                <!-- Language Tab Content -->
                <div class="tab-content mt-3">
                    <div
                        v-for="(language, index) in languages"
                        :key="language.id"
                        :class="[
                            'tab-pane fade',
                            { 'show active': activeTab === index },
                        ]"
                    >
                        <!-- Hero Section Form -->
                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <div>
                                    <label
                                        :for="'hero_title_' + language.code"
                                        >{{ translate("Title") }}</label
                                    >
                                    <input
                                        type="text"
                                        class="form-control"
                                        :id="'hero_title_' + language.code"
                                        placeholder="Title"
                                        :class="{
                                            'invalid-bg':
                                                formErrors[
                                                    `${language.code}.hero_title`
                                                ],
                                        }"
                                        v-model="form[language.code].hero_title"
                                    />
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="
                                        formErrors[
                                            `${language.code}.hero_title`
                                        ]
                                    "
                                >
                                    {{
                                        formErrors[
                                            `${language.code}.hero_title`
                                        ][0]
                                    }}
                                </div>
                            </div>

                            <div class="col-12 col-md-6">
                                <div>
                                    <label
                                        :for="'description_' + language.code"
                                        >{{ translate("Description") }}</label
                                    >
                                    <textarea
                                        class="form-control"
                                        :id="'description_' + language.code"
                                        placeholder="Description"
                                        :class="{
                                            'invalid-bg':
                                                formErrors[
                                                    `${language.code}.description`
                                                ],
                                        }"
                                        v-model="
                                            form[language.code].description
                                        "
                                        rows="6"
                                        style="height: 100px"
                                    ></textarea>
                                </div>
                                <div
                                    class="invalid-feedback animated fadeIn"
                                    v-if="
                                        formErrors[
                                            `${language.code}.description`
                                        ]
                                    "
                                >
                                    {{
                                        formErrors[
                                            `${language.code}.description`
                                        ][0]
                                    }}
                                </div>
                            </div>
                        </div>

                        <button
                            type="submit"
                            class="btn btn-success mt-3"
                            :disabled="isLoading"
                            @click="submit"
                        >
                            <span
                                v-if="isLoading"
                                class="spinner-border spinner-border-sm"
                                role="status"
                                aria-hidden="true"
                            ></span>
                            {{ translate("Save") }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    created() {
        this.fetchLanguages();
        this.fetchAllHeroes(); // Fetch data for all languages on component load
    },
    data() {
        return {
            activeTab: 0,
            languages: [], // List of available languages
            formErrors: {}, // Error messages per language
            form: {}, // Form data for all languages
            isLoading: false,
        };
    },
    methods: {
        fetchLanguages() {
            axios
                .get(route("api.languages"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.languages = response.data;
                    this.languages.forEach((lang) => {
                        this.form[lang.code] = {
                            hero_title: "",
                            description: "",
                        };
                    });
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        fetchAllHeroes() {
            axios
                .get(route("api.fetch.hero.section.all"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    console.log(response.data);
                    response.data.forEach((data) => {
                        if (this.form[data.lang]) {
                            this.form[data.lang].hero_title = data.title || "";
                            this.form[data.lang].description =
                                data.description || "";
                        }
                    });
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        submit() {
            this.isLoading = true;
            axios
                .post(route("api.hero.store.all"), this.form, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    this.isLoading = false;
                    toastr.success(
                        this.translate("Hero section updated successfully.")
                    );
                    this.fetchAllHeroes(); // Refresh the data on save
                    this.formErrors = {}; // Clear errors on success
                })
                .catch((error) => {
                    this.isLoading = false;

                    if (error.response?.data?.errors) {
                        toastr.error(
                            "Both the title and description fields are required. If one field has a value, the other must also be filled."
                        );

                        this.formErrors = error.response.data.errors;
                    }
                });
        },
    },
};
</script>

<style>
.invalid-feedback {
    display: block !important;
}

.invalid-bg {
    border-color: #f8d4d4 !important;
    background-color: #f8d4d4 !important;
}

.nav-link {
    color: #000000 !important;
}

.nav-link.active {
    background-color: #ffffff;
    color: #32A0DA !important;
    font-weight:bold !important;
}
</style>
