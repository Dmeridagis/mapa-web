var mapPolicial = L.map("mapPolicial", {
    zoom: 17,
    center: [-33.007593020, -68.6544329100],
    zoomControl: false,
    attributionControl: false
});

// Añadir elementos de dibujo
var geojsonPolicial = new L.FeatureGroup();
geojsonPolicial.addTo(mapPolicial);

var osmPolicial = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 21
}).addTo(mapPolicial);

// Adicionar búsqueda
var osmGeocoderPolicial = new L.Control.OSMGeocoder({
    collapsed: false,
    text: 'Buscar'
});
mapPolicial.addControl(osmGeocoderPolicial);

var barraZoomPolicial = new L.Control.ZoomBar({ position: 'topleft' }).addTo(mapPolicial);

var manzanaPolicial = L.geoJson(null, {
    style: function (feature) {
        return { color: "green", fill: true, opacity: 0.4, clickable: true };
    },
    onEachFeature: function (feature, layer) {
        layer.bindPopup("Manzana: " + feature.properties.cod_mzna);
    }
}).addTo(mapPolicial);

$.getJSON("report/manzana.php", function (data) {
    manzanaPolicial.addData(data);
});

var viaPolicial = L.geoJson(null, {
    style: function (feature) {
        return { color: "red", fill: true, opacity: 0.4, clickable: true };
    },
    onEachFeature: function (feature, layer) {
        layer.bindPopup("Via: " + feature.properties.via);
    }
}).addTo(mapPolicial);

$.getJSON("report/vias.php", function (data) {
    viaPolicial.addData(data);
});

var seguridad = L.geoJson(null, {
    style: function (feature) {
        return { color: "red", fill: true, opacity: 0.4, clickable: true };
    },
    onEachFeature: function (feature, layer) {
        layer.bindPopup("Descripción: " + feature.properties.nombre);
    }
}).addTo(mapPolicial);

$.getJSON("report/policial.php", function (data) {
    seguridad.addData(data);
});

$(document).ready(function() {
    $('#insert_form_policial').on("submit", function(event) {
        event.preventDefault();
        if ($('#gid').val() == "") {
            alert("GID es requerido");
        } else if ($('#id_segurid').val() == '') {
            alert("ID Seguridad es requerido");
        } else if ($('#txtgeopolicial').val() == '') {
            alert("Dibuje un punto policial");
        } else {
            var formData = $('#insert_form_policial').serialize();
            console.log("Datos enviados: " + formData); // Agregar mensaje de depuración
            $.ajax({
                url: "report/insertarpolicial.php",
                method: "POST",
                data: formData,
                beforeSend: function() {
                    $('#insertpolicial').val("Registrando");
                },
                success: function(data) {
                    alert("Respuesta del servidor: " + data);  // Mostrar la respuesta del servidor
                    console.log("Respuesta del servidor: " + data);
                    window.location.href = "mapa.php";
                }
            });
        }
    });
});

// Configuración de los elementos de dibujo
var drawControl = new L.Control.Draw({
    position: 'topright',
    draw: {
        polyline: false,
        rectangle: false,
        polygon: false,
        circle: false,
        marker: true
    },
    edit: {
        featureGroup: geojsonPolicial,
        remove: true
    }
});

mapPolicial.addControl(drawControl);

// Creación de un nuevo punto
mapPolicial.on('draw:created', function (e) {
    var type = e.layerType,
        layer = e.layer;
    geojsonPolicial.addLayer(layer);
    var coordinates = layer.toGeoJSON().geometry.coordinates;
    var point = 'POINT(' + coordinates[1] + ' ' + coordinates[0] + ')'; // Lon y lat en el orden correcto
    $('#txtgeopolicial').val(point);
    console.log("Coordenadas generadas: " + point); // Añadir mensaje de depuración
});

// Evento de edición de un punto
mapPolicial.on('draw:edited', function (e) {
    var layers = e.layers;
    layers.eachLayer(function (layer) {
        var coordinates = layer.toGeoJSON().geometry.coordinates;
        var point = 'POINT(' + coordinates[1] + ' ' + coordinates[0] + ')';
        $('#txtgeopolicial').val(point);
    });
});

// Evento de eliminación de un punto
mapPolicial.on('draw:deleted', function () {
    $('#txtgeopolicial').val('');
});

