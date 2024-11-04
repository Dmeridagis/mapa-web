var mapSalud = L.map("map6", {
  zoom: 14,
  center: [-33.007593020, -68.6544329100],
  zoomControl: false,
  attributionControl: false
});

// Añadir elementos de dibujo
var geojsonsalud = new L.FeatureGroup();
geojsonsalud.addTo(mapSalud);

// Añadir capa de OpenStreetMap
var osm4 = L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 21
}).addTo(mapSalud);

// Añadir control de búsqueda
var osmGeocoder5 = new L.Control.OSMGeocoder({
  collapsed: false,
  text: 'Buscar'
});
mapSalud.addControl(osmGeocoder5);

// Añadir barra de zoom
var barraZoom5 = new L.Control.ZoomBar({position: 'topleft'}).addTo(mapSalud);

// Añadir capas GeoJSON

// Ejemplo de capa para hospitales
var hospitales = L.geoJson(null, {
  style: function (feature) {
    return {
      color: "blue",
      fill: true,
      opacity: 0.4,
      clickable: true
    };
  },
  onEachFeature: function (feature, layer) {
    layer.bindPopup("Hospital: " + feature.properties.nombre);
  }
}).addTo(mapSalud);

$.getJSON("report/salud.php", function (data) {
  hospitales.addData(data);
});


$(document).ready(function() {
  $('#insert_form_salud').on("submit", function(event) {
      event.preventDefault();
      
      if ($('#dto').val() == "") {
          alert("El departamento es requerido");
      } else if ($('#n_ctro').val() == '') {
          alert("El número de centro de salud es requerido");
      } else if ($('#txtgeosalud').val() == '') {
          alert("Las coordenadas del centro de salud son requeridas");
      } else {
          $.ajax({
              url: "report/insertarsalud.php",  // Ajusta la ruta según sea necesario
              method: "POST",
              data: $('#insert_form_salud').serialize(),  // Serializa todos los datos del formulario
              beforeSend: function() {
                  $('#insertsalud').val("Actualizando...");
              },
              success: function(data) {
                  alert("Respuesta del servidor: " + data);  // Mostrar la respuesta del servidor
                  console.log("Respuesta del servidor: " + data);
                  location.reload();
                  window.location.href = "mapa.php";  // Redirigir a la página principal
              }
          });
      }
  });
});



// Configuración de elementos de dibujo
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
    featureGroup: geojsonsalud,
    remove: true
  }
});
mapSalud.addControl(drawControl);

// Eventos de dibujo

// // Creación de un nuevo punto
// mapSalud.on('draw:created', function (e) {
//   var type = e.layerType,
//   layer = e.layer;
//   geojsonalud.addLayer(layer);
//   $('#txtgeosalud').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
// });//*

mapSalud.on('draw:created', function (e) {
  var type = e.layerType,
      layer = e.layer;
  
  // Añadir el punto a la capa geojsonsalud
  geojsonsalud.addLayer(layer);
  
  // Obtener las coordenadas y mostrarlas en el campo de texto
  var coordinates = layer.toGeoJSON().geometry.coordinates;
  $('#txtgeosalud').val(JSON.stringify(coordinates));
});



// Edición de un punto
mapSalud.on('draw:edited', function (e) {
  var layers = e.layers;
  layers.eachLayer(function (layer) {
    $('#txtgeosalud').val(JSON.stringify(layer.toGeoJSON().geometry.coordinates));
  });
});

// Eliminación de un punto
mapSalud.on('draw:deleted', function () {
  $('#txtgeosalud').val('');
});




