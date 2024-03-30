<x-filament-panels::page>
    <div class="grid grid-cols-3">
        <div id="map" class="col-span-2" style="width: 100%; height: 50vh;"></div>
        <div>
            <button id="saveList">Save list</button>
            <h3>Stops list</h3>
            <table id="stopsListTable">
                <tr>
                    <th>#</th>
                    <th>Stop code</th>
                    <th>Stop name</th>
                    <th>Arrival time</th>
                    <th>Departure time</th>
                </tr>
            </table>
            <ol id="stopsListOl"></ol>
        </div>
    </div>

    <dialog id="dialog">
        <form>
            <label>
                Arrival time?
                <input id="timeInput" type="time" pattern="[0-9]{2}:[0-9]{2}" required>
            </label>
            <div>
                <button id="confirmBtn" type="submit" value="default">Continue</button>
                <button value="cancel" formmethod="dialog">Cancel</button>
            </div>
        </form>
    </dialog>
    @assets
    <link href="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.css" rel="stylesheet">
    <script src="https://api.mapbox.com/mapbox-gl-js/v3.2.0/mapbox-gl.js"></script>
    @endassets
    @script
    <script>
        const dialog = document.getElementById('dialog')
        const confirmBtn = document.getElementById('confirmBtn')
        const timeInput = document.getElementById('timeInput')
        const stopsListOl = document.getElementById('stopsListOl')
        const stopsListTable = document.getElementById('stopsListTable')
        const saveList = document.getElementById('saveList')

        const stops = JSON.parse($wire.stops)
        const shape = JSON.parse($wire.shape)

        const stopsList = JSON.parse($wire.stopsList)
        console.log(stopsList)
        let newStop = {}

        mapboxgl.accessToken = 'pk.eyJ1IjoiZmVsaXhpbngiLCJhIjoiY2xqZXpkMzBkMmxkaDNtbDRiOTg0MDdxeCJ9.pX3IAecDleBe0ngEnxJQGw'

        const map = new mapboxgl.Map({
            container: 'map',
            center: [-65, 49],
            zoom: 9,
            style: 'mapbox://styles/mapbox/streets-v12',
        })

        map.on('load', () => {
            map.addSource('stops', {
                type: 'geojson',
                data: stops,
            })

            map.addLayer({
                id: 'stops',
                type: 'symbol',
                source: 'stops',
                paint: {
                    'text-color': '#000000',
                },
                layout: {
                    'icon-allow-overlap': true,
                    'icon-image': 'circle-white-2',
                    'symbol-placement': 'point',
                    'text-field': ['get', 'stop_code'],
                    'text-ignore-placement': true,
                    'text-offset': [1, 1],
                    'text-variable-anchor': [
                        'right',
                        'left',
                        'top',
                        'bottom',
                    ],
                },
            })

            map.addSource('shape', {
                type: 'geojson',
                data: shape,
            })

            map.addLayer({
                id: 'shape',
                type: 'line',
                source: 'shape',
                paint: {
                    'line-color': 'yellow',
                    'line-width': 6,
                    'line-opacity': 0.4
                },
            })

            console.log(map)
            console.log($wire)
        })

        map.on('click', 'stops', (e) => {
            dialog.showModal()
            newStop = {
                stop_id: e.features[0].id,
                stop_code: e.features[0].properties.stop_code,
                stop_name: e.features[0].properties.stop_name,
                arrival_time: '',
                departure_time: '',
                stop_sequence: stopsList.length + 1,
            }
        })

        confirmBtn.addEventListener('click', (e) => {
            e.preventDefault()

            newStop.arrival_time = timeInput.value
            newStop.departure_time = timeInput.value
            stopsList.push(newStop)

            addToTable(newStop)

            dialog.close()
        })

        saveList.addEventListener('click', (e) => {
            e.preventDefault()

            $wire.set('stopsList', JSON.stringify(stopsList))
            $wire.saveList()
        })

        const addToTable = function(stopTime) {
            const tr = document.createElement('tr')

            const columns = ['stop_sequence', 'stop_code', 'stop_name', 'arrival_time', 'departure_time']

            columns.forEach((column) => {
                const td = document.createElement('td')
                td.innerText = stopTime[column]
                tr.appendChild(td)
            })

            stopsListTable.appendChild(tr)
        }

        stopsList.forEach((stopTime) => addToTable(stopTime))
    </script>
    @endscript
</x-filament-panels::page>