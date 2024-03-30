<div>
    <div id="map" style="width: 100vw; height: 100vh;"></div>
    <ul id="features"></ul>
    <style>
        #features {
            position: absolute;
            top: 0.5rem;
            left: 0;
            height: 50vh;
            width: 33.333%;
            overflow: auto;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 0 25px 25px 0;
            padding: 1rem 2rem;
            font-family: sans-serif;
            font-size: 1.25rem;
            line-height: 2;
        }

        .route-tag {
            padding: 0.5rem;
            border-radius: 0.5rem;
            text-decoration: none;
        }

        .stop-tag {
            padding: 0.25rem;
            margin-left: 0.5rem;
            border-radius: 0.5rem;
        }
    </style>
    @assets
    <script src='https://unpkg.com/@turf/turf@6/turf.min.js'></script>
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.js"></script>
    @endassets
    @script
    <script>
        const stops = JSON.parse($wire.stops)
        const shapes = JSON.parse($wire.shapes)

        mapboxgl.accessToken = 'pk.eyJ1IjoiZmVsaXhpbngiLCJhIjoiY2xqZXpkMzBkMmxkaDNtbDRiOTg0MDdxeCJ9.pX3IAecDleBe0ngEnxJQGw'

        const map = new mapboxgl.Map({
            container: 'map',
            center: [-65, 49],
            zoom: 9,
            style: 'mapbox://styles/mapbox/streets-v12',
        })

        console.log(shapes)

        map.on('load', () => {

            map.addSource('shapes', {
                type: 'geojson',
                data: shapes,
            })

            map.addLayer({
                id: 'shapes',
                type: 'line',
                source: 'shapes',
                paint: {
                    'line-color': ['get', 'route_color'],
                    'line-width': 6,
                },
            })

            map.addSource('stops', {
                type: 'geojson',
                data: stops,
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

            const bbox = turf.bbox(shapes)
            console.log(bbox)
            map.fitBounds(bbox, {
                padding: 20
            })

            console.log(map)
            console.log($wire)
        })

        map.on('click', (e) => {
            const features = map.queryRenderedFeatures(e.point)
                .filter(({
                    layer
                }) => layer.id === 'stops' || layer.id === 'shapes')

            const ul = document.getElementById('features')
            ul.innerHTML = ''

            features.forEach((feature) => {
                if (feature.layer.id === 'shapes') {
                    const li = document.createElement('li')
                    const a = document.createElement('a')
                    a.className = 'route-tag'
                    a.style.backgroundColor = feature.properties.route_color
                    a.style.color = feature.properties.route_text_color
                    a.innerText = feature.properties.route_name
                    a.href = feature.properties.route_url
                    a.target = '_blank'
                    li.appendChild(a)
                    ul.appendChild(li)
                }

                if (feature.layer.id === 'stops') {
                    const li = document.createElement('li')
                    const small = document.createElement('small')
                    small.className = 'stop-tag'
                    small.style.backgroundColor = feature.properties.agency_color
                    small.style.color = feature.properties.agency_text_color
                    small.innerText = `#${feature.properties.stop_code}`

                    const span = document.createElement('span')
                    span.innerText = feature.properties.stop_name

                    li.appendChild(span)
                    li.appendChild(small)
                    ul.appendChild(li)
                }
            })
        })

        map.on('click', 'shapes', (e) => {
            console.log(e.features[0])
        })
    </script>
    @endscript
</div>