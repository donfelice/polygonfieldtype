//mapboxgl.accessToken = 'pk.eyJ1IjoiZG9uZmVsaWNlIiwiYSI6IkNyMUlmLWsifQ.OkQhunHnAZJn9HDmhUxjkQ';
var mapbox_key = $('#map').data('mapbox-key');
var mapbox_lat = $('#map').data('mapbox-lat');
var mapbox_long = $('#map').data('mapbox-long');

mapboxgl.accessToken = mapbox_key;
/* eslint-disable */
var map = new mapboxgl.Map({
    container: 'map', // container id
    style: 'mapbox://styles/mapbox/basic-v9', //hosted style id
    //center: [ 5.7331073, 58.9699756 ], // starting position
    center: [ mapbox_long, mapbox_lat],
    zoom: 14 // starting zoom
});

var layerList = document.getElementById('menu');
var inputs = layerList.getElementsByTagName('input');

function switchLayer(layer) {
    var layerId = layer.target.id;
    map.setStyle('mapbox://styles/mapbox/' + layerId + '-v9');
}

for (var i = 0; i < inputs.length; i++) {
    inputs[i].onclick = switchLayer;
}

var draw = new MapboxDraw({
    displayControlsDefault: false,
    controls: {
        polygon: true,
        trash: true
    }
});
map.addControl(draw);

map.on('draw.create', updateArea);
map.on('draw.delete', updateArea);
map.on('draw.update', updateArea);

function updateArea(e) {
    var data = draw.getAll();
    var answer = document.getElementById('calculated-area');
    if ( data.features.length > 0 ) {
        //console.log(data.features[0]);
        var area = turf.area( data );
        // restrict to area to 2 decimal points
        var rounded_area = Math.round(area*100)/100;
        answer.innerHTML = '<p>Polygon drawn is <strong>' + rounded_area + '</strong> m<sup>2</sup></p>';
        // Update form input
        document.getElementById('ezrepoforms_content_edit_fieldsData_polygon_value_polygon').value = JSON.stringify( data.features[0] );
    } else {
        answer.innerHTML = '';
        if (e.type !== 'draw.delete') alert("Use the draw tools to draw a polygon!");
    }
}
