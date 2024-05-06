<script setup>
import { onMounted, ref } from "vue"

const props = defineProps({ shapes: Object, stops: Object })
const features = ref([])

mapboxgl.accessToken = 'pk.eyJ1IjoiZmVsaXhpbngiLCJhIjoiY2xqZXpkMzBkMmxkaDNtbDRiOTg0MDdxeCJ9.pX3IAecDleBe0ngEnxJQGw'

onMounted(() => {
    // Create Map
    const map = new mapboxgl.Map({
        container: 'map',
        center: [-65, 49],
        zoom: 9,
        style: 'mapbox://styles/mapbox/streets-v12',
    })

    map.on('load', () => {
        // Adding sources
        map.addSource('shapes', {
            type: 'geojson',
            data: props.shapes,
        })
        map.addSource('stops', {
            type: 'geojson',
            data: props.stops,
        })

        // Adding layers
        map.addLayer({
            id: 'shapes',
            type: 'line',
            source: 'shapes',
            paint: {
                'line-color': ['get', 'route_color'],
                'line-width': 6,
            },
        })
        map.addLayer({
            id: 'stops',
            type: 'circle',
            source: 'stops',
            paint: {
                'circle-color': ['get', 'agency_color'],
                'circle-radius': 5,
                'circle-stroke-color': ['get', 'agency_text_color'],
                'circle-stroke-width': 2,
            },
        })

        // Fit bounds to shapes
        const bbox = turfBbox(props.shapes)
        map.fitBounds(bbox, {
            padding: 20
        })
    })

    map.on('click', (e) => {
        const selectedFeatures = map.queryRenderedFeatures(e.point)
            .filter(({ layer }) => layer.id === 'stops' || layer.id === 'shapes')

        console.log(selectedFeatures)
        features.value = selectedFeatures
    })
})

</script>

<template>
    <div id="map" style="width: 100vw; height: 100vh;"></div>
    <md-list style="position: absolute; left: 0; top: 4rem; min-height: 25vh; max-width: 25vw;">
        <md-list-item v-for="feature in features" :key="feature.id" :interactive="feature.properties.route_url" :href="feature.properties.route_url" target="_blank"  :style="{'--md-list-item-leading-icon-color': feature.properties.route_color}">
            <div slot="headline">
                {{ feature.properties.stop_name ?? feature.properties.route_short_name ?? feature.properties.route_long_name }}
            </div>
            <div slot="supporting-text" v-if="feature.properties.stop_code || feature.properties.route_short_name">{{ feature.properties.stop_code ?? feature.properties.route_long_name }}</div>
            <md-icon v-if="feature.source === 'shapes'" slot="start">route</md-icon>
            <md-icon v-if="feature.properties.route_url" slot="end">open_in_new</md-icon>
        </md-list-item>
    </md-list>
    <md-dialog open>
        <div slot="headline">Bienvenue sur Transit Tracker Regio!</div>
        <form slot="content" id="dialog-intro" method="dialog">
            Regio n'est pas un planficateur d'itinéraire. C'est plutôt un projet pour voir les liasons de transports en commun existantes.
            À chaque déplacement, assurez-vous de vérifier les horaires, les tarifs, les modes de réservations auprès du transporteur.
        </form>
        <div slot="actions">
            <md-text-button form="dialog-intro" value="ok">OK</md-text-button>
        </div>
    </md-dialog>
</template>
