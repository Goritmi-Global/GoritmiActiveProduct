<template>
    <main class="main" id="main">
        <div class="pagetitle d-flex justify-content-between">
            <div>
                <h1>
                    {{ translate("Settings") }}
                </h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <Link :href="route('dashboard')">{{
                                $page.props.website_name
                            }}</Link>
                        </li>
                        <li class="breadcrumb-item active">
                            {{ translate("Settings") }}
                        </li>
                    </ol>
                </nav>
            </div>

            <a
                v-if="$page.props.user.role == 'Participant'"
                class="btn btn-success"
                :href="`/participant/${$page.props.user.id}/${slugify(
                    $page.props.user.name
                )}`"
                target="_blank"
                >{{ translate("Visit Website") }}
            </a>
        </div>

        <!-- End Page Title -->
        <section class="section">
            <WebsiteNameComponent v-if="$page.props.user.role == 'admin'" />
            <WebsiteLogoComponent />
            <AboutComponent />
            <!-- <CarouselComponent /> -->
            <!-- <GlobalSeoComponent v-if="$page.props.user.role == 'admin'" /> -->
            <!-- <WebsiteFooterComponent v-if="$page.props.user.role == 'admin'" /> -->
            
        </section>
    </main>
    <!-- End #main -->
</template>

<script>
import Master from "@/Pages/Backend/Layout/Master.vue";
import GlobalSeoComponent from "@/Pages/Backend/ExternalWebsite/GlobalSeoComponent.vue";
import AboutComponent from "@/Pages/Backend/ExternalWebsite/AboutComponent.vue";
// import CarouselComponent from "@/Pages/Backend/ExternalWebsite/CarouselComponent.vue";
import WebsiteNameComponent from "@/Pages/Backend/ExternalWebsite/WebsiteNameComponent.vue";
import WebsiteLogoComponent from "@/Pages/Backend/ExternalWebsite/WebsiteLogoComponent.vue";
import WebsiteFooterComponent from "@/Pages/Backend/ExternalWebsite/WebsiteFooterComponent.vue";
import Multiselect from "@vueform/multiselect";
export default {
    layout: Master,

    components: {
        Multiselect,
        GlobalSeoComponent,
        AboutComponent,
        // CarouselComponent,
        WebsiteNameComponent,
        WebsiteLogoComponent,
        WebsiteFooterComponent,
        
    },
    data() {
        return {};
    },
    methods: {
        refetchParentDetails() {
            this.fetchSeo();
        },
        slugify(name) {
            return name
                .toLowerCase() // Convert to lowercase
                .replace(/\s+/g, "-") // Replace spaces with dashes
                .replace(/[^\w\-]+/g, "") // Remove non-alphanumeric characters (except dashes)
                .replace(/--+/g, "-") // Replace multiple dashes with a single dash
                .replace(/^-+/, "") // Trim dashes from the start
                .replace(/-+$/, ""); // Trim dashes from the end
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
