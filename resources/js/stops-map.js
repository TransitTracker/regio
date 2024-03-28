const stops = JSON.parse(document.getElementById('stops-data').text).data

const map = L.map('map')
L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
}).addTo(map);

var markers = []
stops.forEach((stop) => {
    markers.push(L.marker([stop.stop_position.coordinates[1], stop.stop_position.coordinates[0]]).addTo(map))
})

var group = new L.featureGroup(markers)
map.fitBounds(group.getBounds())

var drawnItems = new L.FeatureGroup();
map.addLayer(drawnItems);
var drawControl = new L.Control.Draw({
    draw: {
        polyline: false,
        polygon: false,
        rectangle: false,
        circle: false,
        circlemarker: false,
    },
    edit: {
        featureGroup: drawnItems
    },
})
map.addControl(drawControl)

map.on(L.Draw.Event.CREATED, function (event) {
    // copy
    navigator.clipboard.writeText(`${event.layer._latlng.lng}, ${event.layer._latlng.lat}`)

    // click to open
    document.getElementById('create-stop').click()
})
