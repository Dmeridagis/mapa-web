var map, mapi, imagenUrl, limiteImagen, nuevamanzana=[], esriTopo, esriWorldImagery,esriStreet,google,otm,osm,featureList, buscarManzana = [], buscarVia = [], buscarSitio = [], museumSearch = [];

$(window).resize(function() {
  sizeLayerControl();
});

$(document).on("click", ".feature-row", function(e) {
  $(document).off("mouseout", ".feature-row", clearHighlight);
  //sidebarClick(parseInt($(this).attr("id"), 10));
});

if ( !("ontouchstart" in window) ) {
  $(document).on("mouseover", ".feature-row", function(e) {
    highlight.clearLayers().addLayer(L.circleMarker([$(this).attr("lat"), $(this).attr("lng")], highlightStyle));
  });
}

$(document).on("mouseout", ".feature-row", clearHighlight);

$("#about-btn").click(function() {
  $("#aboutModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#full-extent-btn").click(function() {
  map.fitBounds(manzanas.getBounds());
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#nuevo-btn").click(function() {
  $("#nuevoModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#about-btn").click(function() {
  $("#aboutModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#full-extent-btn").click(function() {
  map.fitBounds(manzanas.getBounds());
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#botonvia").click(function() {
  $("#nuevaviaModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#botonpuntointeres").click(function() {
  $("#nuevositioModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#botonsalud").click(function() {
  $("#nuevosaludModal").modal("show");
   $(".navbar-collapse.in").collapse("hide");
  return false;
});

$("#botonpolicial").click(function() {
  $("#nuevopolicialModal").modal("show");
  $(".navbar-collapse.in").collapse("hide");
  return false;
});




function sizeLayerControl() {
  $(".leaflet-control-layers").css("max-height", $("#map").height() - 50);
}

function clearHighlight() {
  highlight.clearLayers();
}

/* Basemap Layers */
otm = L.tileLayer("http://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
  maxZoom: 21
});
osm = L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 21
});
esriTopo = L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
  maxZoom: 21
});
esriWorldImagery = L.tileLayer('https://services.arcgisonline.com/arcgis/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
  maxZoom: 21
});
esriStreet = L.tileLayer('https://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
  maxZoom: 21
});
google = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
  maxZoom: 21
});

/* Overlay Layers */
var highlight = L.geoJson(null);
var highlightStyle = {
  stroke: false,
  fillColor: "#00FFFF",
  fillOpacity: 0.7,
  radius: 10
};

// var manzanas = L.geoJson(null, {
//   style: function (feature) {
//     return {
//       color: "red",
//       fill: true,
//       opacity: 0.4,
//       clickable: true
//     };
//   },
//   onEachFeature: function (feature, layer) {
// 	buscarManzana.push({
//       name: layer.feature.properties.cod_mzna,
//       source: "Manzanas",
//       id: L.stamp(layer),
//       bounds: layer.getBounds()
//     })
// 	layer.bindPopup("<div style=text-align:center><h3>"+
// 	'<button type="button" onclick="document.getElementById('+"'nuevoModal'"+').style.display='+"'block'"+'; setTimeout(mapi.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevoModal">C</button>&ensp;'+
// 	'<a href="report/editamanzana.php?codigo='+ feature.properties.id +'" class="btn btn-default" >U</a>&ensp;'+
// 	'<a href="report/eliminamanzana.php?codigo='+ feature.properties.id +'" class="btn btn-danger" onclick="return confirm('+"'seguro?'"+')">D</a>'+
// 	"</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div><table><tr><td>id_sector: "+feature.properties.id_sector+
// 	"</td></tr><tr><td>Manzana: "+feature.properties.cod_mzna+
// 	"</td></tr></table>",
// 	{minWidth: 150, maxWidth: 200});
//   }	
// });
// $.getJSON("report/manzana.php", function (data) {
//   manzanas.addData(data);
// });




// Función para generar colores aleatorios
function getRandomColor() {
  const letters = '0123456789ABCDEF';
  let color = '#';
  for (let i = 0; i < 6; i++) {
    color += letters[Math.floor(Math.random() * 16)];
  }
  return color;
}

// Diccionario para guardar colores de los barrios
var barrioColors = {};

// Definir la capa de manzanas
var manzanas = L.geoJson(null, {
  style: function (feature) {
    // Asignar un color aleatorio si aún no se ha asignado
    if (!barrioColors[feature.properties.id_mzna]) {
      barrioColors[feature.properties.id_mzna] = getRandomColor();
    }
    return {
      color: barrioColors[feature.properties.id_mzna],
      fillColor: barrioColors[feature.properties.id_mzna],
      fill: true,
      fillOpacity: 0.5,
      weight: 2,
      opacity: 1
    };
  },
  onEachFeature: function (feature, layer) {
    buscarManzana.push({
      name: layer.feature.properties.cod_mzna,
      source: "Manzanas",
      id: L.stamp(layer),
      bounds: layer.getBounds()
    });

  

    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" onclick="document.getElementById(\'nuevoModal\').style.display=\'block\'; setTimeout(mapi.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevoModal">C</button>&ensp;' +
      '<a href="report/editamanzana.php?codigo=' + feature.properties.id + '" class="btn btn-default" >U</a>&ensp;' +
      '<a href="report/eliminamanzana.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'seguro?\')">D</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div><table><tr><td>id_sector: " + feature.properties.id_sector +
      "</td></tr><tr><td>Manzana: " + feature.properties.cod_mzna +
      "</td></tr></table>",
      { minWidth: 150, maxWidth: 200 }
    );
  }
});

$.getJSON("report/manzana.php", function (data) {
  manzanas.addData(data);
  map.addLayer(manzanas);
});




// var calles = L.geoJson(null, {
//   style: function (feature) {
//       return {
//         color: 'blue',
//         weight: 3,
//         opacity: 1
//       };
//   },
//   onEachFeature: function (feature, layer) {
// 	buscarVia.push({
//       name: layer.feature.properties.via,
//       source: "Vias",
//       id: L.stamp(layer),
//       bounds: layer.getBounds()
//     })
// 	layer.bindPopup("<div style=text-align:center><h3>"+
// 	'<button type="button" name="botonvia" id="botonvia" onclick="document.getElementById('+"'nuevaviaModal'"+').style.display='+"'block'"+'; setTimeout(mapVia.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevaviaModal">C</button>&ensp;'+
// 	'<a href="report/editavia.php?codigo='+ feature.properties.id +'" class="btn btn-default" >U</a>&ensp;'+
// 	'<a href="report/eliminavia.php?codigo='+ feature.properties.id +'" class="btn btn-danger" onclick="return confirm('+"'seguro?'"+')">D</a>'+
// 	"</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div><table><tr><td>Codigo: "+feature.properties.id+
// 	"</td></tr><tr><td>Via: "+feature.properties.via+
// 	"</td></tr></table>",
// 	{minWidth: 150, maxWidth: 200});
//   }
// });
// $.getJSON("report/vias.php", function (data) {
//   calles.addData(data);
// });



// Definir la capa de calles
var calles = L.geoJson(null, {
  style: function (feature) {
    switch (feature.properties.tipo) {
      case 'Calle':
        return { color: 'gray', weight: 1, opacity: 0.5 };
      case 'RP':
        return { color: '#E388FC', weight: 3, opacity: 0.5 };
        case 'RN': 
        return { color: '#FC9A88', weight: 5, opacity: 0.5};
      default:
        return { color: 'gray', weight: 1, opacity: 1 };
    }
  },

  onEachFeature: function (feature, layer) {
    buscarVia.push({
      name: layer.feature.properties.via,
      source: "Vias",
      id: L.stamp(layer),
      bounds: layer.getBounds()
    });

    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" name="botonvia" id="botonvia" onclick="document.getElementById(\'nuevaviaModal\').style.display=\'block\'; setTimeout(mapVia.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevaviaModal">C</button>&ensp;' +
      '<a href="report/editavia.php?codigo=' + feature.properties.id + '" class="btn btn-default" >U</a>&ensp;' +
      '<a href="report/eliminavia.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'seguro?\')">D</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div><table><tr><td>Codigo: " + feature.properties.id +
      "</td></tr><tr><td>Via: " + feature.properties.via +
      "</td></tr></table>",
      { minWidth: 150, maxWidth: 200 }
    );
  }
});

$.getJSON("report/vias.php", function (data) {
  calles.addData(data);
});


/* Empty layer placeholder to add to layer control for listening when to add/remove sitios to markerClusters layer */
var sitiosLayer = L.geoJson(null);
var sitios = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/theater.png",
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.descripcion,
      riseOnHover: true
    });
  },
  onEachFeature: function (feature, layer) {
   buscarSitio.push({
      name: layer.feature.properties.descripcion,
      source: "Sitios",
      id: L.stamp(layer)
    })
	layer.bindPopup("<div style=text-align:center><h3>"+
	'<button type="button" onclick="document.getElementById('+"'nuevositioModal'"+').style.display='+"'block'"+'; setTimeout(mapSitio.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevositioModal">C</button>&ensp;'+
	'<a href="report/editaSitio.php?codigo='+ feature.properties.id +'" class="btn btn-default" >U</a>&ensp;'+
	'<a href="report/eliminaSitio.php?codigo='+ feature.properties.id +'" class="btn btn-danger" onclick="return confirm('+"'seguro?'"+')">D</a>'+
	"</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div><table><tr><td>Codigo: "+feature.properties.id+
	"</td></tr><tr><td>Descripcion: "+feature.properties.descripcion+
	"</td></tr></table>",
	{minWidth: 150, maxWidth: 200});
  }
});
$.getJSON("report/sitios.php", function (data) {
  sitios.addData(data);
  map.addLayer(sitiosLayer);
});




//Salud

var saludLayer = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/salud.png",
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.nombre,
      riseOnHover: true
    });
  },


  onEachFeature: function (feature, layer) {
    buscarSitio.push({
      name: layer.feature.properties.nombre,
      source: "Salud",
      id: L.stamp(layer)
    });
  
    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" onclick="document.getElementById(\'nuevosaludModal\').style.display=\'block\'; setTimeout(mapSalud.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevosaludModal">C</button> ' +
      '<a href="report/editasalud.php?codigo=' + feature.properties.id + '" class="btn btn-default">U</a> ' +
      '<a href="report/eliminasalud.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'¿Seguro?\')">D</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div>" +
      "<table>" +
      "<tr><td>Código: " + feature.properties.id + "</td></tr>" +
      "<tr><td>Nombre: " + feature.properties.nombre + "</td></tr>" +
      "<tr><td>Domicilio: " + feature.properties.domicilio + "</td></tr>" +
      "<tr><td>Teléfono: " + feature.properties.telefono + "</td></tr>" +
      "</table>",
      { minWidth: 150, maxWidth: 200 }
    );
  }

});
$.getJSON("report/salud.php", function (data) {
  saludLayer.addData(data);
  map.addLayer(saludLayer);
});




// Seguridad

var seguridadLayer = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/policia.png", // Asegúrate de tener un ícono adecuado para seguridad
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.nombre,
      riseOnHover: true
    });
  },

  onEachFeature: function (feature, layer) {
    buscarSitio.push({
      name: layer.feature.properties.nombre,
      source: "Seguridad",
      id: L.stamp(layer)
    });

    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" onclick="document.getElementById(\'nuevopolicialModal\').style.display=\'block\'; setTimeout(mapSeguridad.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevopolicialModal">C</button> ' +
      '<a href="report/editapolicial.php?codigo=' + feature.properties.id + '" class="btn btn-default">U</a> ' +
      '<a href="report/eliminarpolicial.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'¿Seguro?\')">D</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div>" +
      "<table>" +
      "<tr><td>ID: " + feature.properties.id + "</td></tr>" +
      "<tr><td>GID: " + feature.properties.gid + "</td></tr>" +
      "<tr><td>ID Seguridad: " + feature.properties.id_seguridad + "</td></tr>" +
      "<tr><td>ID Usuario: " + feature.properties.id_usuario + "</td></tr>" +
      "<tr><td>Teléfono: " + feature.properties.telefono + "</td></tr>" +
      "<tr><td>Dirección: " + feature.properties.direccion + "</td></tr>" +
      "<tr><td>Tipo: " + feature.properties.tipo + "</td></tr>" +
      "<tr><td>Nombre: " + feature.properties.nombre + "</td></tr>" +
      "</table>",
      { minWidth: 150, maxWidth: 200 }
    );
  }
});

$.getJSON("report/policial.php", function (data) {
  seguridadLayer.addData(data);
  map.addLayer(seguridadLayer);
});


//distritos

// Distritos


// Definir los estilos de cada distrito en tonos pasteles y alta transparencia
var districtStyles = {
    'FRAY LUIS BELTRAN': {color: 'black', weight: 2, opacity: 1, fillColor: '#FFC1CC', fillOpacity: 0.6}, // Rosa pastel
    'RODEO DEL MEDIO': {color: 'black', weight: 2, opacity: 1, fillColor: '#CCFFCC', fillOpacity: 0.6}, // Verde pastel
    'SAN ROQUE': {color: 'black', weight: 2, opacity: 1, fillColor: '#CCCCFF', fillOpacity: 0.6}, // Azul pastel
    'GENERAL ORTEGA': {color: 'black', weight: 2, opacity: 1, fillColor: '#FFFFCC', fillOpacity: 0.6}, // Amarillo pastel
    'GENERAL GUTIERREZ': {color: 'black', weight: 2, opacity: 1, fillColor: '#FFCCFF', fillOpacity: 0.6}, // Magenta pastel
    'TORIBIO DE LUZURIAGA': {color: 'black', weight: 2, opacity: 1, fillColor: '#CCFFFF', fillOpacity: 0.6}, // Cian pastel
    'COQUIMBITO': {color: 'black', weight: 2, opacity: 1, fillColor: '#FFCCCC', fillOpacity: 0.6}, // Rojo pastel
    'LUNLUNTA': {color: 'black', weight: 2, opacity: 1, fillColor: '#CCFF99', fillOpacity: 0.6}, // Verde claro pastel
    'BARRANCAS': {color: 'black', weight: 2, opacity: 1, fillColor: '#FFE0CC', fillOpacity: 0.6}, // Naranja claro pastel
    'MAIPU': {color: 'black', weight: 2, opacity: 1, fillColor: '#E5CCFF', fillOpacity: 0.6}, // Morado pastel
    'RUSSELL': {color: 'black', weight: 2, opacity: 1, fillColor: '#CCE5FF', fillOpacity: 0.6}, // Azul claro pastel
    'CRUZ DE PIEDRA': {color: 'black', weight: 2, opacity: 1, fillColor: '#E0FFCC', fillOpacity: 0.6} // Lima pastel
};


// Definir la capa de distritos
var distritosLayer = L.geoJson(null, {
  style: function (feature) {
    return districtStyles[feature.properties.distrito] || {
      color: "gray", // Color por defecto si el distrito no está definido
      weight: 2,
      opacity: 1,
      fillOpacity: 0.9,
      dashArray: '5, 5'
    };
  },

  onEachFeature: function (feature, layer) {
    buscarSitio.push({
      name: layer.feature.properties.distrito,
      source: "Distritos",
      id: L.stamp(layer)
    });

    layer.bindPopup(
      

      "<tr><td>Distrito: " + feature.properties.distrito + "</td></tr>" +

      "</table>",
      { minWidth: 150, maxWidth: 200 }
    );
  }
});

$.getJSON("report/distritos.php", function (data) {
  distritosLayer.addData(data);
  map.addLayer(distritosLayer);
});



// Deportes

var deportesLayer = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/depo.png", // Asegúrate de tener un ícono adecuado para deportes
        iconSize: [24, 28],
        iconAnchor: [12, 28],
        popupAnchor: [0, -25]
      }),
      title: feature.properties.nombre,
      riseOnHover: true
    });
  },

  onEachFeature: function (feature, layer) {
    buscarSitio.push({
      name: layer.feature.properties.nombre,
      source: "Deportes",
      id: L.stamp(layer)
    });

    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" onclick="document.getElementById(\'nuevodeporteModal\').style.display=\'block\'; setTimeout(mapDeporte.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevodeporteModal">C</button> ' +
      '<a href="report/editaDeporte.php?codigo=' + feature.properties.id + '" class="btn btn-default">U</a> ' +
      '<a href="report/eliminaDeporte.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'¿Seguro?\')">D</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div>" +
      "<table>" +
      "<tr><td>ID: " + feature.properties.id + "</td></tr>" +
      "<tr><td>GID: " + feature.properties.gid + "</td></tr>" +
      "<tr><td>ID Deporte: " + feature.properties.id_deporte + "</td></tr>" +
      "<tr><td>ID Usuario: " + feature.properties.id_usuario + "</td></tr>" +
      "<tr><td>Tipo: " + feature.properties.tipo + "</td></tr>" +
      "<tr><td>Nombre: " + feature.properties.nombre + "</td></tr>" +
      "<tr><td>Servicios: " + feature.properties.servicios + "</td></tr>" +
      "<tr><td>Horarios: " + feature.properties.horarios + "</td></tr>" +
      "<tr><td>Dirección: " + feature.properties.direccion + "</td></tr>" +
      "<tr><td>Teléfono: " + feature.properties.telefono + "</td></tr>" +
      "<tr><td>ID Distrito: " + feature.properties.id_distrit + "</td></tr>" +
      "</table>",
      { minWidth: 150, maxWidth: 200 }
    );
  }
});

$.getJSON("report/deportes.php", function (data) {
  deportesLayer.addData(data);
  map.addLayer(deportesLayer);
});


/*
imagenUrl = 'images/imagen2.jpg';
limiteImagen = [[-6.783495,-79.878558], [-6.779442,-79.871314]];

raster = L.imageOverlay(imagenUrl, limiteImagen, {
	//opacity:0.8,
	attribution:'Habilitacion Urbana El Trebol'
});
*/
map = L.map("map", {
  zoom: 14,
  center: [-33.00759, -68.654432],
  layers: [osm, manzanas, highlight],
  zoomControl: false,
  attributionControl: false
});

/* Filter sidebar feature list to only show features in current map bounds */
map.on("moveend", function (e) {
  //syncSidebar();
});

/* Clear feature highlight when map is clicked */
map.on("click", function(e) {
  highlight.clearLayers();
});

var barraZoom = new L.Control.ZoomBar({position: 'topleft'}).addTo(map);

/* GPS enabled geolocation control set to follow the user's location */
var locateControl = L.control.locate({
  position: "bottomright",
  drawCircle: true,
  follow: true,
  setView: true,
  keepCurrentZoomLevel: true,
  markerStyle: {
    weight: 1,
    opacity: 0.8,
    fillOpacity: 0.8
  },
  circleStyle: {
    weight: 1,
    clickable: false
  },
  icon: "fa fa-location-arrow",
  metric: false,
  strings: {
    title: "My location",
    popup: "You are within {distance} {unit} from this point",
    outsideMapBoundsMsg: "You seem located outside the boundaries of the map"
  },
  locateOptions: {
    maxZoom: 18,
    watch: true,
    enableHighAccuracy: true,
    maximumAge: 10000,
    timeout: 10000
  }
}).addTo(map);

/* Larger screens get expanded layer control and visible sidebar */
if (document.body.clientWidth <= 767) {
  var isCollapsed = true;
} else {
  var isCollapsed = false;
}

var baseLayers = {
  "Topo Map": otm,
  "Street Map": osm,
  "Esri Topografico": esriTopo,
  "Esri Satelite": esriWorldImagery,
  "Esri Callejero": esriStreet,
  "Google Satelite": google
};

var groupedOverlays = {
  "Puntos de interes": {
    "<img src='assets/img/theater.png' width='24' height='28'>&nbsp;Sitios": sitios,
    "<img src='assets/img/salud.png' width='24' height='28'>&nbsp;Salud": saludLayer, 
    "<img src='assets/img/policia.png' width='24' height='28'>&nbsp;Seguridad": seguridadLayer,
    "<img src='assets/img/depo.png' width='24' height='28'>&nbsp;Deportes": deportesLayer
	//"<img src='assets/img/planet.png' width='24' height='28'>&nbsp;Sitios de interes": sitiosLayer
  },
  "Capas": {
  "Barrios": manzanas,
    "Calles": calles,
    "Distritos": distritosLayer
  }
};

var layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
  collapsed: isCollapsed
}).addTo(map);

/* Highlight search box text on click */
$("#searchbox").click(function () {
  $(this).select();
});

/* Prevent hitting enter from refreshing the page */
$("#searchbox").keypress(function (e) {
  if (e.which == 13) {
    e.preventDefault();
  }
});

$("#featureModal").on("hidden.bs.modal", function (e) {
  $(document).on("mouseout", ".feature-row", clearHighlight);
});

/* Typeahead search functionality */
$(document).one("ajaxStop", function () {
  $("#loading").hide();
  sizeLayerControl();
  /* Fit map to manzanas bounds */
  map.fitBounds(manzanas.getBounds());
  
  featureList = new List("features", {valueNames: ["feature-name"]});
  //featureList.sort("feature-name", {order:"asc"});

  var manzanasBH = new Bloodhound({
    name: "Manzanas",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: buscarManzana,
    limit: 10
  });

  var viasBH = new Bloodhound({
    name: "Vias",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: buscarVia,
    limit: 10
  });

  var sitiosBH = new Bloodhound({
    name: "Sitios",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: buscarSitio,
    limit: 10
  });

  var museumsBH = new Bloodhound({
    name: "Museums",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: museumSearch,
    limit: 10
  });

  var geonamesBH = new Bloodhound({
    name: "GeoNames",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name);
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    remote: {
      url: "http://api.geonames.org/searchJSON?username=bootleaf&featureClass=P&maxRows=5&countryCode=US&name_startsWith=%QUERY",
      filter: function (data) {
        return $.map(data.geonames, function (result) {
          return {
            name: result.name + ", " + result.adminCode1,
            lat: result.lat,
            lng: result.lng,
            source: "GeoNames"
          };
        });
      },
      ajax: {
        beforeSend: function (jqXhr, settings) {
          settings.url += "&east=" + map.getBounds().getEast() + "&west=" + map.getBounds().getWest() + "&north=" + map.getBounds().getNorth() + "&south=" + map.getBounds().getSouth();
          $("#searchicon").removeClass("fa-search").addClass("fa-refresh fa-spin");
        },
        complete: function (jqXHR, status) {
          $('#searchicon').removeClass("fa-refresh fa-spin").addClass("fa-search");
        }
      }
    },
    limit: 10
  });
  manzanasBH.initialize();
  sitiosBH.initialize();
  museumsBH.initialize();
  geonamesBH.initialize();
  viasBH.initialize();

  /* instantiate the typeahead UI */
  $("#searchbox").typeahead({
    minLength: 3,
    highlight: true,
    hint: false
  }, {
    name: "Manzanas",
    displayKey: "name",
    source: manzanasBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'>Manzana</h4>"
	}
  }, {
    name: "Vias",
    displayKey: "name",
    source: viasBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'>Calle</h4>"
    }
  }, {
    name: "sitios",
    displayKey: "name",
    source: sitiosBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'><img src='assets/img/theater.png' width='24' height='28'>&nbsp;Sitios</h4>",
      suggestion: Handlebars.compile(["{{name}}<br>&nbsp;<small>{{address}}</small>"].join(""))
    }
  }, {
    name: "Museums",
    displayKey: "name",
    source: museumsBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'><img src='assets/img/museum.png' width='24' height='28'>&nbsp;Museums</h4>",
      suggestion: Handlebars.compile(["{{name}}<br>&nbsp;<small>{{address}}</small>"].join(""))
    }
  }, {
    name: "GeoNames",
    displayKey: "name",
    source: geonamesBH.ttAdapter(),
    templates: {
      header: "<h4 class='typeahead-header'><img src='assets/img/globe.png' width='25' height='25'>&nbsp;GeoNames</h4>"
    }
  }).on("typeahead:selected", function (obj, datum) {
    if (datum.source === "Manzanas") {
      map.fitBounds(datum.bounds);
    }
	if (datum.source === "Vias") {
      map.fitBounds(datum.bounds);
    }
    if (datum.source === "Sitios") {
      if (!map.hasLayer(sitiosLayer)) {
        map.addLayer(sitiosLayer);
      }
      map.setView([datum.lat, datum.lng], 17);
      if (map._layers[datum.id]) {
        map._layers[datum.id].fire("click");
      }
    }
    if (datum.source === "Museums") {
      if (!map.hasLayer(museumLayer)) {
        map.addLayer(museumLayer);
      }
      map.setView([datum.lat, datum.lng], 17);
      if (map._layers[datum.id]) {
        map._layers[datum.id].fire("click");
      }
    }
    if (datum.source === "GeoNames") {
      map.setView([datum.lat, datum.lng], 14);
    }
    if ($(".navbar-collapse").height() > 50) {
      $(".navbar-collapse").collapse("hide");
    }
  }).on("typeahead:opened", function () {
    $(".navbar-collapse.in").css("max-height", $(document).height() - $(".navbar-header").height());
    $(".navbar-collapse.in").css("height", $(document).height() - $(".navbar-header").height());
  }).on("typeahead:closed", function () {
    $(".navbar-collapse.in").css("max-height", "");
    $(".navbar-collapse.in").css("height", "");
  });
  $(".twitter-typeahead").css("position", "static");
  $(".twitter-typeahead").css("display", "block");
});

// Leaflet patch to make layer control scrollable on touch browsers
var container = $(".leaflet-control-layers")[0];
if (!L.Browser.touch) {
  L.DomEvent
  .disableClickPropagation(container)
  .disableScrollPropagation(container);
} else {
  L.DomEvent.disableClickPropagation(container);
}
