// Inicializar el mapa cuando se carga la página o cuando se abre el modal
var mapReclamo = L.map('map5', {
    center: [-33.007593020, -68.6544329100],
    zoom: 17,
    zoomControl: true,  // Activamos los controles de zoom
    attributionControl: false
});

// Añadir la capa base de OpenStreetMap
var osm5 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19
}).addTo(mapReclamo);

var imagenUrl5 = 'images/imagen2.jpg';
  var limiteImagen5 = [[-6.783495,-79.878558], [-6.779442,-79.871314]];
	  
  var raster4 = L.imageOverlay(imagenUrl5, limiteImagen5, {
	  //opacity:0.8,
	  attribution:'Habilitacion Urbana El Trebol'}).addTo(mapReclamo);


// Variable para el marcador, inicialmente nula
var marker = null;

// Añadir el geocoder al mapa
var geocoder = new L.Control.OSMGeocoder({
    collapsed: false, // Cambia a true si prefieres que esté minimizado inicialmente
    text: 'Buscar', // Texto del botón
    placeholder: 'Ingrese una ubicación', // Placeholder del campo de texto
    callback: function(results) {
        if (results.length === 0) {
            alert("No se encontró ninguna ubicación.");
            return;
        }

        var bbox = results[0].boundingbox,
            first = new L.LatLng(bbox[0], bbox[2]),
            second = new L.LatLng(bbox[1], bbox[3]),
            bounds = new L.LatLngBounds([first, second]);
        
        mapReclamo.fitBounds(bounds);
        
        // Añadir un marcador en la ubicación encontrada
        if (marker) {
            mapReclamo.removeLayer(marker);
        }
        marker = L.marker(results[0].latLng).addTo(mapReclamo);
        $('#txtgeoreclamo').val(results[0].lat + ',' + results[0].lon); // Actualiza el campo de coordenadas
    }
});
mapReclamo.addControl(geocoder); // Añade el control del geocoder al mapa

// Evento para capturar el clic en el mapa y mostrar las coordenadas
mapReclamo.on('click', function (e) {
    if (marker) {
        mapReclamo.removeLayer(marker);
    }
    marker = L.marker(e.latlng).addTo(mapReclamo);
    $('#txtgeoreclamo').val(e.latlng.lat + ',' + e.latlng.lng);
});


// Recargar el mapa cuando se abre el modal
$('#modalReclamo').on('shown.bs.modal', function () {
    setTimeout(function() {
        mapReclamo.invalidateSize(); // Ajusta el tamaño del mapa al abrir el modal
    }, 100); // Un ligero retraso puede ayudar a asegurar que el modal se abra completamente antes de reajustar el mapa
});


// Configuración para el formulario de reclamos
$(document).ready(function () {
    $('#reclamo_form').on("submit", function (event) {
        event.preventDefault();
        // Validaciones
        if ($('#txttipoReclamo').val() == "") {
            alert("Tipo de reclamo es requerido");
        } else if ($('#txtDetalleReclamo').val() == '') {
            alert("Descripción del reclamo es requerida");
        } else if ($('#txtgeoreclamo').val() == '') {
            alert("Por favor, haga clic en el mapa para marcar la ubicación");
        } else {
            var formData = new FormData(this);
            $.ajax({
                url: "report/insertarreclamo.php",
                method: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#insertreclamo').val("Registrando");
                },
                success: function (data) {
                    location.reload();
                    window.location.href = "pageuser.php";
                }
            });
        }
    });
});

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
			featureGroup: geojsonreclamo,
			remove: true
			}
		});
		
mapReclamo.addControl(drawControl);	

mapReclamo.on('draw:created', function (e) {
	var type = e.layerType,
	layer = e.layer;
	geojsonreclamo.addLayer(layer);
	$('#txtgeoreclamo').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
	});
	