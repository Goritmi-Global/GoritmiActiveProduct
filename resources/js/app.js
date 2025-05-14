import { createApp, h } from "vue";
import { InertiaProgress } from "@inertiajs/progress";
import { createInertiaApp,Link } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import axios from "axios";
import "bootstrap/dist/js/bootstrap.bundle.min.js";
import "./bootstrap";
window.axios = axios;
import toastr from "toastr";
import 'toastr/build/toastr.min.css';

window.toastr = toastr;
toastr.options = {
    positionClass: "toast-bottom-right",
};
// toastr.options = {
//     positionClass: "toast-bottom-right",
//     progressBar: true,
//     timeOut: "2000",
// }; 
InertiaProgress.init();

//  Globally Calling Components
import DeleteModal from './Pages/Global/DeleteModal.vue';
import ImageZooming from './Pages/Global/ImageZooming.vue';
import Cropper from './Pages/Global/Cropper.vue';
import CropperOffCanvas from './Pages/Global/CropperOffCanvas.vue';


createInertiaApp({
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .mixin({ methods: { route 
                ,translate(value) {
                    let tras = this.$page.props.lang_data[value];

                    if (typeof tras !== "undefined") {
                        // checking if the translation found than use it.
                        return tras;
                        // return 'Translated';
                    } else {
                        if (value) {
                            // console.log(value);
                            axios.post(
                                route("api.missing-translations.store"),
                                { value }
                            );
                        }
                    }

                    return value;
                },
            } })
            .use(plugin)
            .component("Link", Link)
            .component("DeleteModal", DeleteModal)
            .component("ImageZooming", ImageZooming)
            .component("Cropper", Cropper)
            
            .component("CropperOffCanvas", CropperOffCanvas)
            .mount(el);
    },
});
