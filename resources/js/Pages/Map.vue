<script setup>
import { onMounted, ref } from "vue"

const props = defineProps({ routes: Object, stops: Object })
const features = ref([])
const infoDialogOpen = ref(true)
const showTransitland = ref(false)

mapboxgl.accessToken = 'pk.eyJ1IjoiZmVsaXhpbngiLCJhIjoiY2xqZXpkMzBkMmxkaDNtbDRiOTg0MDdxeCJ9.pX3IAecDleBe0ngEnxJQGw'

let map;

onMounted(() => {
    // Create Map
    map = new mapboxgl.Map({
        container: 'map',
        center: [-65, 49],
        zoom: 9,
        style: 'mapbox://styles/mapbox/streets-v12',
        dragRotate: false,
        hash: true,
        transformRequest: (url, resourceType) => {
            if (resourceType === 'Tile' && url.startsWith('https://transit.land')) {
                return {
                    url,
                    headers: {
                        apikey: '3CobN9u6gUbqfIbIG4eHUWIcMbgnEsdp',
                    },
                }
            }
        },
    })

    map.on('load', () => {
        // Adding sources
        map.addSource('rg-routes', {
            type: 'geojson',
            data: props.routes,
        })
        map.addSource('rg-stops', {
            type: 'geojson',
            data: props.stops,
        })
        map.addSource('tl-routes', {
            type: 'vector',
            tiles: ['https://transit.land/api/v2/tiles/routes/tiles/{z}/{x}/{y}.pbf'],
            maxzoom: 14,
        })
        map.addSource('tl-stops', {
            type: 'vector',
            tiles: ['https://transit.land/api/v2/tiles/stops/tiles/{z}/{x}/{y}.pbf'],
            maxzoom: 14,
        })

        // Adding layers
        map.addLayer({
            id: 'rg-routes',
            type: 'line',
            source: 'rg-routes',
            paint: {
                'line-color': ['get', 'route_color'],
                'line-width': 6,
            },
        })
        map.addLayer({
            id: 'rg-stops',
            type: 'circle',
            source: 'rg-stops',
            paint: {
                'circle-color': ['get', 'agency_color'],
                'circle-radius': 5,
                'circle-stroke-color': ['get', 'agency_text_color'],
                'circle-stroke-width': 2,
            },
        })
        map.addLayer({
            id: 'tl-routes',
            type: 'line',
            source: 'tl-routes',
            'source-layer': 'routes',
            paint: {
                'line-color': ['get', 'route_color'],
                'line-width': 6,
            },
            layout: {
                visibility: 'none',
            },
        })
        map.addLayer({
            id: 'tl-stops',
            type: 'circle',
            source: 'tl-stops',
            'source-layer': 'stops',
            paint: {
                'circle-color': '#f7ae56',
                'circle-radius': 5,
                'circle-stroke-color': '#ffffff',
                'circle-stroke-width': 2,
            },
            layout: {
                visibility: 'none',
            },
        })

        // Fit bounds to routes
        const bbox = turfBbox(props.routes)
        map.fitBounds(bbox, {
            padding: 20
        })
    })

    map.on('click', (e) => {
        const selectedFeatures = map.queryRenderedFeatures(e.point)
            .filter(({ layer }) => layer.id.startsWith('rg-') || layer.id.startsWith('tl-'))

        console.log(selectedFeatures)
        features.value = selectedFeatures
    })
})

const toggleTransitland = () => {
    const currentlyVisible = map.getLayoutProperty('tl-routes', 'visibility') === 'visible'

    map.setLayoutProperty('tl-routes', 'visibility', currentlyVisible ? 'none' : 'visible')
    map.setLayoutProperty('tl-stops', 'visibility', currentlyVisible ? 'none' : 'visible')

    showTransitland.value = !currentlyVisible
}

</script>

<template>
    <div id="map" style="width: 100vw; height: 100vh;"></div>
    <md-elevated-card class="absolute left-8 top-24 w-96">
        <p class="p-4" v-if="!features.length">Cliquez une ligne ou un arrêt pour voir les détails</p>
        <md-list v-else class="bg-transparent py-4">
            <md-list-item
                v-for="feature in features"
                :key="feature.id"
                :interactive="feature.properties.route_url || feature.properties.onestop_id"
                :href="feature.properties.route_url ?? `https://transit.land/onestop-id/${feature.properties.onestop_id}`"
                target="_blank"
                :style="{'--md-list-item-leading-icon-color': feature.properties.route_color}"
            >
                <div slot="overline" v-if="feature.properties.agency_name">
                    {{ feature.properties.agency_name }}
                </div>
                <div slot="headline">
                    {{ feature.properties.stop_name ?? feature.properties.route_short_name }}
                    {{ feature.properties.route_long_name }}
                </div>
                <div slot="supporting-text" v-if="feature.properties.stop_code">{{ feature.properties.stop_code }}</div>
                <md-icon v-if="feature.source.includes('-routes')" slot="start">route</md-icon>
            </md-list-item>
        </md-list>
    </md-elevated-card>
    <md-dialog :open="infoDialogOpen" @close="infoDialogOpen = false">
        <div slot="headline">Bienvenue sur Transit Tracker Regio!</div>
        <form slot="content" id="dialog-intro" method="dialog">
            Regio n'est pas un planficateur d'itinéraire. C'est plutôt un projet pour voir les liasons de transports en commun existantes.
            À chaque déplacement, assurez-vous de vérifier les horaires, les tarifs, les modes de réservations auprès du transporteur.
        </form>
        <div slot="actions">
            <md-text-button form="dialog-intro" value="ok">OK</md-text-button>
        </div>
    </md-dialog>
    <md-fab aria-label="Info" variant="primary" class="absolute bottom-9 right-4" @click="infoDialogOpen = true">
        <md-icon slot="icon">info</md-icon>
    </md-fab>
    <md-fab :label="showTransitland ? 'Cacher Transitland' : 'Afficher Transitland'" class="absolute right-8 top-24" @click="toggleTransitland" size="small">
        <md-icon slot="icon">{{ showTransitland ? 'visibility_off' : 'visibility' }}</md-icon>
    </md-fab>
</template>
