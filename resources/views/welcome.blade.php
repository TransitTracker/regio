<x-layouts.app>
{{--    <div id="map" style="width: 100vw; height: 100vh;"></div>--}}
{{--    <md-list x-data="alpineFeatures()" id="features">--}}
{{--        <md-list-item x-for="feature in features" :key="feature.id">--}}
{{--            <span x-text="feature.layer.id"></span>--}}
{{--            <x-gmdi-route slot="start" style="width: 24px;" />--}}
{{--        </md-list-item>--}}
{{--    </md-list>--}}
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
{{--    <style>--}}
{{--        #features {--}}
{{--            position: absolute;--}}
{{--            top: 0.5rem;--}}
{{--            left: 0;--}}
{{--            height: 50vh;--}}
{{--            width: 33.333%;--}}
{{--            overflow: auto;--}}
{{--            /*background: rgba(255, 255, 255, 0.8);*/--}}
{{--            /*border-radius: 0 25px 25px 0;*/--}}
{{--            /*padding: 1rem 2rem;*/--}}
{{--            /*font-family: sans-serif;*/--}}
{{--            /*font-size: 1.25rem;*/--}}
{{--            /*line-height: 2;*/--}}
{{--        }--}}

{{--        .route-tag {--}}
{{--            padding: 0.5rem;--}}
{{--            border-radius: 0.5rem;--}}
{{--            text-decoration: none;--}}
{{--        }--}}

{{--        .stop-tag {--}}
{{--            padding: 0.25rem;--}}
{{--            margin-left: 0.5rem;--}}
{{--            border-radius: 0.5rem;--}}
{{--        }--}}
{{--    </style>--}}
{{--    @script--}}
{{--    <script>--}}
{{--        const dataModel = () => ({--}}
{{--            features: [],--}}
{{--            updateFeatures(newArray) {--}}
{{--                this.features = newArray--}}
{{--                console.log(newArray)--}}
{{--            },--}}
{{--        })--}}

{{--        const alpineFeatures = dataModel()--}}

{{--        document.addEventListener('livewire:navigated', () => {--}}

{{--            const stops = JSON.parse($wire.stops)--}}
{{--            const shapes = JSON.parse($wire.shapes)--}}

{{--            mapboxgl.accessToken = 'pk.eyJ1IjoiZmVsaXhpbngiLCJhIjoiY2xqZXpkMzBkMmxkaDNtbDRiOTg0MDdxeCJ9.pX3IAecDleBe0ngEnxJQGw'--}}

{{--            const map = new mapboxgl.Map({--}}
{{--                container: 'map',--}}
{{--                center: [-65, 49],--}}
{{--                zoom: 9,--}}
{{--                style: 'mapbox://styles/mapbox/streets-v12',--}}
{{--            })--}}

{{--            console.log(shapes)--}}

{{--            map.on('load', () => {--}}

{{--                map.addSource('shapes', {--}}
{{--                    type: 'geojson',--}}
{{--                    data: shapes,--}}
{{--                })--}}

{{--                map.addLayer({--}}
{{--                    id: 'shapes',--}}
{{--                    type: 'line',--}}
{{--                    source: 'shapes',--}}
{{--                    paint: {--}}
{{--                        'line-color': ['get', 'route_color'],--}}
{{--                        'line-width': 6,--}}
{{--                    },--}}
{{--                })--}}

{{--                map.addSource('stops', {--}}
{{--                    type: 'geojson',--}}
{{--                    data: stops,--}}
{{--                })--}}

{{--                map.addLayer({--}}
{{--                    id: 'stops',--}}
{{--                    type: 'circle',--}}
{{--                    source: 'stops',--}}
{{--                    paint: {--}}
{{--                        'circle-color': ['get', 'agency_color'],--}}
{{--                        'circle-radius': 5,--}}
{{--                        'circle-stroke-color': ['get', 'agency_text_color'],--}}
{{--                        'circle-stroke-width': 2,--}}
{{--                    },--}}
{{--                })--}}

{{--                const bbox = turfBbox(shapes)--}}
{{--                console.log(bbox)--}}
{{--                map.fitBounds(bbox, {--}}
{{--                    padding: 20--}}
{{--                })--}}

{{--                console.log(map)--}}
{{--                console.log($wire)--}}
{{--            })--}}

{{--            map.on('click', (e) => {--}}
{{--                const features = map.queryRenderedFeatures(e.point)--}}
{{--                    .filter(({--}}
{{--                                 layer--}}
{{--                             }) => layer.id === 'stops' || layer.id === 'shapes')--}}

{{--                // alpineFeatures.updateFeatures(features)--}}
{{--            })--}}

{{--            map.on('click', 'shapes', (e) => {--}}
{{--                console.log(e.features[0])--}}
{{--            })--}}
{{--        })--}}
{{--    </script>--}}
{{--    @endscript--}}
</x-layouts.app>
