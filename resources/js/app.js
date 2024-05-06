import './bootstrap.js'

import { createApp, h } from 'vue'
import { createInertiaApp } from "@inertiajs/vue3"
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";

createInertiaApp({
    resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob("./Pages/**/*.vue")),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .mount(el)
    },
})

// Mapbox
import 'mapbox-gl/dist/mapbox-gl.css'
import mapboxgl from 'mapbox-gl'
window.mapboxgl = mapboxgl

// Turf
import { bbox } from '@turf/turf'
window.turfBbox = bbox

// Material
import '@material/web/dialog/dialog.js'
import '@material/web/button/text-button.js'
import '@material/web/list/list.js'
import '@material/web/list/list-item.js'
import '@material/web/icon/icon.js'
import '@material/web/menu/menu.js'
import '@material/web/menu/menu-item.js'
