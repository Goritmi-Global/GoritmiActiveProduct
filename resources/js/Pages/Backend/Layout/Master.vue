<template>
    <div class="main">
        <!-- ======= Header ======= -->
        <header id="header" class="header fixed-top d-flex align-items-center">
            <div class="d-flex align-items-center justify-content-between">
                <Link
                    :href="route('dashboard')"
                    class="logo d-flex align-items-center"
                >
                    <img
                        :src="logoSrc"
                        alt="logo"
                        height="80"
                        @error="setAltImg"
                        style="object-fit: cover; overflow: hidden"
                    />

                        <span class="d-none d-lg-block">
                            {{ $page.props.website_name }}</span
                        >
                </Link>
                <i
                    class="bi bi-list toggle-sidebar-btn"
                    @click="sidebarToggle()"
                ></i>
            </div>
            <!-- End Logo -->

            <!-- <a
            class="nav-link nav-icon"
            :href="route('home')"
            target="_blank"
            title="Browse Website"
        >
            <i class="bi bi-globe"></i>
        </a> -->

            <!-- <div class="search-bar">
      <form class="search-form d-flex align-items-center" method="POST" action="#">
        <input type="text" name="query" placeholder="Search" title="Enter search keyword">
        <button type="submit" title="Search"><i class="bi bi-search"></i></button>
      </form>
    </div> --><!-- End Search Bar -->

            <nav class="header-nav ms-auto">
                <ul class="d-flex align-items-center">
                    <!-- End Search Icon-->

                    <li class="nav-item dropdown me-3">
                        <a
                            class="nav-link dropdown-toggle"
                            href="#"
                            id="languageDropdown"
                            role="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            {{ user_language_name }}
                        </a>
                        <ul
                            class="dropdown-menu dropdown-menu-end c-global-radius"
                            aria-labelledby="languageDropdown"
                        >
                            <li
                                v-for="(language, index) in languages"
                                :key="index"
                                :class="[
                                    language.code ==
                                    $page.props.default_language
                                        ? 'lang-active'
                                        : '',
                                ]"
                            >
                                <!-- {{ $page.props.default_language }} - {{ language.code }} -->
                                <a
                                    class="dropdown-item"
                                    href="javascript:void(0)"
                                    @click="changeLang(language.code)"
                                    >{{ language.name }}</a
                                >
                            </li>
                        </ul>
                    </li>
                    <!-- End Messages Nav -->

                    <li class="nav-item dropdown pe-3">
                        <a
                            class="nav-link nav-profile d-flex align-items-center pe-0"
                            href="#"
                            data-bs-toggle="dropdown"
                        >
                            <img
                                :src="$page.props.user.image"
                                @error="setAltImg"
                                alt="Profile"
                                class="rounded-circle"
                            />

                            <span class="d-none d-md-block dropdown-toggle ps-2"
                                >{{ $page.props.user.first_name }}
                                {{ $page.props.user.last_name }}</span
                            > </a
                        ><!-- End Profile Iamge Icon -->

                        <ul
                            class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile"
                        >
                            <li>
                                <Link
                                    class="dropdown-item d-flex align-items-center"
                                    :href="route('settings')"
                                >
                                    <i class="bi bi-person c-nested-icon"></i>
                                    <span
                                        >{{ translate("Update profile") }}
                                    </span>
                                </Link>
                            </li>

                            <li>
                                <hr class="dropdown-divider" />
                            </li>

                            <li>
                                <a
                                    class="dropdown-item d-flex align-items-center"
                                    :href="route('logout')"
                                >
                                    <i
                                        class="bi bi-box-arrow-right c-nested-icon"
                                    ></i>
                                    <span>{{ translate("Logout") }}</span>
                                </a>
                            </li>
                        </ul>
                        <!-- End Profile Dropdown Items -->
                    </li>
                    <!-- End Profile Nav -->
                </ul>
            </nav>

            <!-- End Icons Navigation -->
        </header>

        <!-- ======= Sidebar ======= -->
        <aside id="sidebar" class="sidebar">
            <ul class="sidebar-nav" id="sidebar-nav">
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :href="route('dashboard')"
                        :class="{ active: isActive('/dashboard') }"
                    >
                        <i class="bi bi-speedometer2"></i>
                        <span>{{ translate("Dashboard") }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" :href="route('doctors')"  :class="{ active: isActive('/doctors') }">
                        <i class="bi bi-box-seam"></i>
                        <span>{{ translate("Doctors") }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" :href="route('dashboard')">
                        <i class="bi bi-collection"></i>
                        <span>{{ translate("Nav 2") }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link collapsed" :href="route('dashboard')">
                        <i class="bi bi-layout-text-window-reverse"></i>
                        <span>{{ translate("Nav 3") }}</span>
                    </a>
                </li>

                <hr />

                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :href="route('users')"
                        :class="{ active: isActive('/users') }"
                    >
                        <i class="bi bi-people"></i>
                        <span>{{ translate("Users") }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :href="route('degrees')"
                        :class="{ active: isActive('/degrees') }"
                    >
                        <i class="bi bi-people"></i>
                        <span>{{ translate("Degrees") }}</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :href="route('specialities')"
                        :class="{ active: isActive('/specialities') }"
                    >
                        <i class="bi bi-people"></i>
                        <span>{{ translate("Specialties") }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :href="route('languages')"
                        :class="{ active: isActive('/languages') }"
                    >
                        <i class="bi bi-translate"></i>
                        <span>{{ translate("Languages") }}</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a
                        class="nav-link collapsed"
                        :href="route('external-website')"
                        :class="{ active: isActive('/external-website') }"
                    >
                        <i class="bi bi-gear"></i>
                        <span>{{ translate("Settings") }}</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- End Sidebar-->

        <slot></slot>

        <!-- ======= Footer ======= -->
        <footer id="footer" class="footer">
            <div class="copyright">
                &copy; Copyright <strong><span>Goritmi</span></strong
                >. All Rights Reserved
            </div>
            <div class="credits">
                Designed by
                <a href="https://goritmi.pk/">Goritmi</a>
            </div>
        </footer>
        <!-- End Footer -->
    </div>
</template>

<script>
import "http://goritmi-active.test/backend/assets/vendor/bootstrap/js/bootstrap.bundle.min.js";
import axios from "axios";

export default {
    mounted() {
        let mainJs = document.createElement("script");
        mainJs.setAttribute(
            "src",
            "http://goritmi-active.test/backend/assets/js/main.js"
        );

        document.head.appendChild(mainJs);
    },

    created() {
        this.fetchLanguages();
    },

    data() {
        return {
            backToTopActive: 0,
            languages: [],
            user_language_name: "",
            image: "",
        };
    },
    computed: {
        logoSrc() {
            // Check if the logo exists, fallback to the default image
            return this.$page.props.logo || "/images/default.jpg";
        },
    },
    methods: {
        isActive(route) {
            return window.location.pathname.startsWith(route);
        },
        logout() {
            axios
                .post(route("api.logout"), this.form, {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then(() => {
                    this.$inertia.get(route("login"));
                })
                .catch((error) => {});
        },
        fetchLanguages() {
            axios
                .get(route("api.top.bar.languages"), {
                    headers: {
                        Authorization: "Bearer " + this.$page.props.auth_token,
                    },
                })
                .then((response) => {
                    this.languages = response.data;
                    console.log(this.$page.props.default_language);
                    // getting user default language name from langauges array
                    this.user_language_name = response.data.filter(
                        (p) => p.code == this.$page.props.default_language
                    )[0].name;
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);
                });
        },
        changeLang(code) {
            let formData = new FormData();
            formData.append("default_language", code);

            axios
                .post(route("api.user.default.language"), formData)
                .then((data) => {
                    window.location.reload();
                })
                .catch((error) => {
                    toastr.error(error.response.data.message);

                    if (error.response.data.errors) {
                        this.formErrors = error.response.data.errors;
                    }
                });
        },

        sidebarToggle() {
            document.body.classList.toggle("toggle-sidebar");
        },
    },
};
</script>

<style>
@import url("public/backend/assets/vendor/bootstrap/css/bootstrap.min.css");
@import url("public/backend/assets/vendor/bootstrap-icons/bootstrap-icons.css");
@import url("public/backend/assets/vendor/boxicons/css/boxicons.min.css");
@import url("public/backend/assets/vendor/remixicon/remixicon.css");
@import url("public/backend/assets/vendor/simple-datatables/style.css");
@import url("public/backend/assets/css/style.css");
@import url("public/backend/assets/css/custom.css");

@import "toastr";
</style>

<style>
@import "@vueform/multiselect/themes/default.css";
/* custom style that should be moved into the custom.css file later */
.section-title {
    text-align: left;
    padding-bottom: 5px;
}
.section-title h5 {
    font-size: 22px;
    font-weight: bold;
    text-transform: uppercase;
    position: relative;
    color: rgb(50, 160, 218);
}
/* .section-title h5::before {
    content: "";
    position: absolute;
    display: block;
    width: 120px;
    height: 1px;
    background: #ddd;
    bottom: 1px;
    left: calc(50% - 60px);
}
.section-title h5::after {
    content: "";
    position: absolute;
    display: block;
    width: 40px;
    height: 3px;
    background: rgb(50,160,218);
    bottom: 0;
    left: calc(50% - 20px);
} */
.c-file-padding {
    padding: 1rem 0.75rem !important;
}
.invalid-feedback {
    display: block !important;
}
.invalid-bg {
    border-color: #f8d4d4 !important;
    background-color: #f8d4d4 !important;
}
.btn-success {
    background-color: rgb(50, 160, 218) !important;
    border-color: rgb(50, 160, 218) !important;
}

.btn-success:hover {
    background-color: #2887bb !important;
    border-color: #2887bb !important;
}

.btn-success:active {
    background-color: #2887bb !important;
    border-color: #2887bb !important;
}

.btn-success:focus {
    color: #fff !important;
    box-shadow: none !important;
}
.toast-success {
    background-color: rgb(50, 160, 218) !important;
}
.theme-text-color {
    color: rgb(50, 160, 218) !important;
    font-weight: bold;
}
.theme-bg-color {
    background-color: rgb(50, 160, 218) !important;
}

.lang-active a,
.lang-active a:hover {
    color: white !important;
    background-color: rgb(50, 160, 218) !important;
}

.nav-link.active {
    background-color: #f6f9ff !important; /* Change this to your desired color */
    color: rgb(50, 160, 218) !important;
}
</style>
