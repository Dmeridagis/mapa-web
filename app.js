var map, mapi, imagenUrl, limiteImagen, nuevamanzana=[], esriTopo, esriWorldImagery,esriStreet,google,otm,osm,featureList, buscarManzana = [], buscarVia = [], buscarSitio = [], museumSearch = [];

// Redimensionar el control de capas cuando se cambia el tamaño de la ventana
$(window).resize(function() {
  sizeLayerControl();  // Ajusta el tamaño del control de capas
});

// Evento al hacer clic en una fila de características (feature-row)
$(document).on("click", ".feature-row", function(e) {
  $(document).off("mouseout", ".feature-row", clearHighlight); // Elimina el resaltado cuando se hace clic
  //sidebarClick(parseInt($(this).attr("id"), 10)); // Descomentado si se quiere realizar una acción al hacer clic
});

// Evento de 'mouseover' en filas de características solo si no es un dispositivo táctil
if ( !("ontouchstart" in window) ) {
  $(document).on("mouseover", ".feature-row", function(e) {
    // Limpiar las capas anteriores y agregar un marcador circular de resaltado en la fila
    highlight.clearLayers().addLayer(L.circleMarker([$(this).attr("lat"), $(this).attr("lng")], highlightStyle));
  });
}

// Evento de 'mouseout' para eliminar el resaltado al mover el ratón fuera de la fila
$(document).on("mouseout", ".feature-row", clearHighlight);

// Mostrar modal de "Acerca de" cuando se hace clic en el botón correspondiente
$("#about-btn").click(function() {
  $("#aboutModal").modal("show");  // Mostrar el modal "Acerca de"
  $(".navbar-collapse.in").collapse("hide");  // Cerrar el menú de navegación si está abierto
  return false;  // Prevenir la acción predeterminada del enlace
});

// Ajustar la vista del mapa para mostrar todas las "manzanas" cuando se hace clic en el botón
$("#full-extent-btn").click(function() {
  map.fitBounds(manzanas.getBounds());  // Ajustar los límites del mapa a los límites de "manzanas"
  $(".navbar-collapse.in").collapse("hide");  // Cerrar el menú de navegación
  return false;  // Prevenir la acción predeterminada del enlace
});

// Mostrar el modal para crear un nuevo objeto cuando se hace clic en el botón correspondiente
$("#nuevo-btn").click(function() {
  $("#nuevoModal").modal("show");  // Mostrar el modal para crear un nuevo objeto
  $(".navbar-collapse.in").collapse("hide");  // Cerrar el menú de navegación
  return false;  // Prevenir la acción predeterminada del enlace
});

// Mostrar el modal de "Nueva Vía" cuando se hace clic en el botón correspondiente
$("#botonvia").click(function() {
  $("#nuevaviaModal").modal("show");  // Mostrar el modal para agregar una nueva vía
  $(".navbar-collapse.in").collapse("hide");  // Cerrar el menú de navegación
  return false;  // Prevenir la acción predeterminada del enlace
});

// Mostrar el modal de "Nuevo Punto de Interés" cuando se hace clic en el botón correspondiente
$("#botonpuntointeres").click(function() {
  $("#nuevositioModal").modal("show");  // Mostrar el modal para agregar un nuevo punto de interés
  $(".navbar-collapse.in").collapse("hide");  // Cerrar el menú de navegación
  return false;  // Prevenir la acción predeterminada del enlace
});

// Mostrar el modal de "Nuevo Centro de Salud" cuando se hace clic en el botón correspondiente
$("#botonsalud").click(function() {
  $("#nuevosaludModal").modal("show");  // Mostrar el modal para agregar un nuevo centro de salud
  $(".navbar-collapse.in").collapse("hide");  // Cerrar el menú de navegación
  return false;  // Prevenir la acción predeterminada del enlace
});

// Mostrar el modal de "Nuevo Centro Policial" cuando se hace clic en el botón correspondiente
$("#botonpolicial").click(function() {
  $("#nuevopolicialModal").modal("show");  // Mostrar el modal para agregar un nuevo centro policial
  $(".navbar-collapse.in").collapse("hide");  // Cerrar el menú de navegación
  return false;  // Prevenir la acción predeterminada del enlace
});

// Función para ajustar el tamaño del control de capas al tamaño del mapa
function sizeLayerControl() {
  $(".leaflet-control-layers").css("max-height", $("#map").height() - 50);  // Establece la altura máxima del control de capas
}

// Función para limpiar el resaltado del mapa
function clearHighlight() {
  highlight.clearLayers();  // Elimina todos los elementos de la capa de resalto
}

/* Basemap Layers (Capas base del mapa) */
otm = L.tileLayer("http://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
  maxZoom: 21  // Define el zoom máximo para esta capa
});
osm = L.tileLayer("http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
  maxZoom: 21  // Define el zoom máximo para esta capa
});
esriTopo = L.tileLayer('https://services.arcgisonline.com/ArcGIS/rest/services/World_Topo_Map/MapServer/tile/{z}/{y}/{x}', {
  maxZoom: 21  // Define el zoom máximo para esta capa
});
esriWorldImagery = L.tileLayer('https://services.arcgisonline.com/arcgis/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
  maxZoom: 21  // Define el zoom máximo para esta capa
});
esriStreet = L.tileLayer('https://services.arcgisonline.com/arcgis/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
  maxZoom: 21  // Define el zoom máximo para esta capa
});
google = L.tileLayer('http://www.google.cn/maps/vt?lyrs=s@189&gl=cn&x={x}&y={y}&z={z}', {
  maxZoom: 21  // Define el zoom máximo para esta capa
});

/* Overlay Layers (Capas de superposición) */
var highlight = L.geoJson(null);  // Capa para resaltar elementos del mapa
var highlightStyle = {
  stroke: false,  // No mostrar borde
  fillColor: "#00FFFF",  // Color de relleno cyan
  fillOpacity: 0.7,  // Opacidad del relleno
  radius: 10  // Radio del círculo (tamaño)
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
    if (!barrioColors[feature.properties.id_sector]) {
      barrioColors[feature.properties.id_sector] = getRandomColor();
    }
    return {
      color: barrioColors[feature.properties.id_sector],
      fillColor: barrioColors[feature.properties.id_sector],
      fill: true,
      fillOpacity: 0.5,
      weight: 2,
      opacity: 1
    };
  },
  onEachFeature: function (feature, layer) {
    buscarManzana.push({
      name: layer.feature.properties.id_sector,
      source: "Manzanas",
      id: L.stamp(layer),
      bounds: layer.getBounds()
    });
  

    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" onclick="document.getElementById(\'nuevoModal\').style.display=\'block\'; setTimeout(mapi.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevoModal">Agregar</button>&ensp;' +
      '<a href="report/editamanzana.php?codigo=' + feature.properties.id + '" class="btn btn-default" >Editar</a>&ensp;' +
      '<a href="report/eliminamanzana.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'seguro?\')">Eliminar</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div><table><tr><td>Barrio: " + feature.properties.id_sector +
      "</td></tr><tr><td>Manzana: " + feature.properties.cod_mzna +
      "</td></tr></table>",
      { minWidth: 250, maxWidth: 300 }
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
    // Establecer el estilo de cada calle según el tipo
    switch (feature.properties.tipo) {
      case 'Calle':
        return { color: 'gray', weight: 3, opacity: 0.9 };  // Color gris y grosor 3 para calles generales
      case 'RP':
        return { color: '#E388FC', weight: 5, opacity: 0.9 };  // Color morado y grosor 5 para "RP"
      case 'RN': 
        return { color: '#FC9A88', weight: 6, opacity: 0.9};  // Color naranja claro y grosor 6 para "RN"
      default:
        return { color: 'gray', weight: 1, opacity: 1 };  // Estilo por defecto (gris y delgado)
    }
  },

  onEachFeature: function (feature, layer) {
    // Agregar la vía a la lista de búsqueda con nombre, fuente, ID y límites del mapa
    buscarVia.push({
      name: layer.feature.properties.nombre,
      source: "Vias",
      id: L.stamp(layer),  // Generar un ID único para cada capa
      bounds: layer.getBounds()  // Obtener los límites del área de la vía
    });

    // Vincular un popup a cada capa de calle con opciones para Insertar, Editar y Eliminar
    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" name="botonvia" id="botonvia" onclick="document.getElementById(\'nuevaviaModal\').style.display=\'block\'; setTimeout(mapVia.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevaviaModal">Insertar</button>&ensp;' +
      '<a href="report/editavia.php?codigo=' + feature.properties.id + '" class="btn btn-default" >Editar</a>&ensp;' +
      '<a href="report/eliminavia.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'seguro?\')">Eliminar</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div><table><tr><td>Tipo: " + feature.properties.tipo +
      "</td></tr><tr><td>Nombre: " + feature.properties.nombre +
      "</td></tr></table>",
      { minWidth: 250, maxWidth: 300 }  // Definir las dimensiones máximas y mínimas del popup
    );
  }
});

// Cargar datos de las calles desde un archivo JSON y agregarlos a la capa 'calles'
$.getJSON("report/vias.php", function (data) {
  calles.addData(data);  // Añadir datos obtenidos al objeto 'calles'
});

// Capa vacía para agregar al control de capas (utilizada para escuchar la adición o eliminación de sitios en la capa)
var sitiosLayer = L.geoJson(null);

// Definir la capa de sitios
var sitios = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    // Crear un marcador personalizado para cada sitio con un ícono de teatro
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/theater.png",  // Icono de teatro
        iconSize: [24, 28],  // Tamaño del icono
        iconAnchor: [12, 28],  // Punto de anclaje del icono
        popupAnchor: [0, -25]  // Anclaje del popup
      }),
      title: feature.properties.descripcion,  // Descripción del sitio
      riseOnHover: true  // Hace que el marcador se eleve cuando el cursor pasa sobre él
    });
  },
  
  onEachFeature: function (feature, layer) {
    // Agregar sitio a la lista de búsqueda
    buscarSitio.push({
      name: layer.feature.properties.nombre,
      source: "Sitios",
      id: L.stamp(layer)
    });

    // Vincular un popup con opciones para agregar, editar y eliminar sitios
    layer.bindPopup("<div style=text-align:center><h3>"+
      '<button type="button" onclick="document.getElementById('+"'nuevositioModal'"+').style.display='+"'block'"+'; setTimeout(mapSitio.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevositioModal">Agregar</button>&ensp;'+
      '<a href="report/editaSitio.php?codigo='+ feature.properties.id +'" class="btn btn-default" >Editar</a>&ensp;'+
      '<a href="report/eliminaSitio.php?codigo='+ feature.properties.id +'" class="btn btn-danger" onclick="return confirm('+"'seguro?'"+')">Eliminar</a>'+
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div><table><tr><td>Tipo: "+feature.properties.tipo+
      "</td></tr><tr><td>Nombre: "+feature.properties.nombre+
      "</td></tr></table>", 
      { minWidth: 250, maxWidth: 300 }  // Establecer el tamaño del popup
    );
  }
});

// Cargar datos de los sitios desde un archivo JSON y agregarlos a la capa 'sitios'
$.getJSON("report/sitios.php", function (data) {
  sitios.addData(data);  // Añadir los datos de sitios a la capa
  map.addLayer(sitiosLayer);  // Añadir la capa de sitios al mapa
});


// Capa de salud
var saludLayer = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    // Crear un marcador personalizado para centros de salud con ícono específico
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/salud.png",  // Icono para salud
        iconSize: [24, 28],  // Tamaño del icono
        iconAnchor: [12, 28],  // Anclaje del icono
        popupAnchor: [0, -25]  // Anclaje del popup
      }),
      title: feature.properties.nombre,  // Título del marcador
      riseOnHover: true  // Hace que el marcador se eleve al pasar el ratón
    });
  },

  onEachFeature: function (feature, layer) {
    // Agregar centro de salud a la lista de búsqueda
    buscarSitio.push({
      name: layer.feature.properties.nombre,
      source: "Salud",
      id: L.stamp(layer)
    });

    // Vincular un popup con botones de acción (Agregar, Editar, Eliminar)
    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" onclick="document.getElementById(\'nuevosaludModal\').style.display=\'block\'; setTimeout(mapSalud.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevosaludModal">Agregar</button> ' +
      '<a href="report/editasalud.php?codigo=' + feature.properties.id + '" class="btn btn-default">Editar</a> ' +
      '<a href="report/eliminasalud.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'¿Seguro?\')">Eliminar</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div>" +
      "<table>" +
      "<tr><td>Código: " + feature.properties.id + "</td></tr>" +
      "<tr><td>Nombre: " + feature.properties.nombre + "</td></tr>" +
      "<tr><td>Domicilio: " + feature.properties.domicilio + "</td></tr>" +
      "<tr><td>Teléfono: " + feature.properties.telefono + "</td></tr>" +
      "</table>",
      { minWidth: 250, maxWidth: 300 }  // Definir el tamaño del popup
    );
  }
});

// Cargar los datos de salud desde el archivo JSON y agregarlos a la capa de salud
$.getJSON("report/salud.php", function (data) {
  saludLayer.addData(data);  // Añadir datos de salud a la capa
  map.addLayer(saludLayer);  // Agregar la capa de salud al mapa
});

// Capa de seguridad
var seguridadLayer = L.geoJson(null, {
  pointToLayer: function (feature, latlng) {
    // Crear un marcador para centros de seguridad con un ícono específico
    return L.marker(latlng, {
      icon: L.icon({
        iconUrl: "assets/img/policia.png",  // Icono para seguridad
        iconSize: [24, 28],  // Tamaño del icono
        iconAnchor: [12, 28],  // Anclaje del icono
        popupAnchor: [0, -25]  // Anclaje del popup
      }),
      title: feature.properties.nombre,  // Título del marcador
      riseOnHover: true  // Hace que el marcador se eleve al pasar el ratón
    });
  },

  onEachFeature: function (feature, layer) {
    // Agregar seguridad a la lista de búsqueda
    buscarSitio.push({
      name: layer.feature.properties.nombre,
      source: "Seguridad",
      id: L.stamp(layer)
    });

    // Vincular un popup con botones de acción (Agregar, Editar, Eliminar)
    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      '<button type="button" onclick="document.getElementById(\'nuevopolicialModal\').style.display=\'block\'; setTimeout(mapSeguridad.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevopolicialModal">Agregar</button> ' +
      '<a href="report/editapolicial.php?codigo=' + feature.properties.id + '" class="btn btn-default">Editar</a> ' +
      '<a href="report/eliminarpolicial.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'¿Seguro?\')">Eliminar</a>' +
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
      { minWidth: 250, maxWidth: 300 }  // Definir las dimensiones del popup
    );
  }
});

// Cargar los datos de seguridad desde un archivo JSON y agregarlos a la capa de seguridad
$.getJSON("report/policial.php", function (data) {
  seguridadLayer.addData(data);  // Añadir datos de seguridad a la capa
  map.addLayer(seguridadLayer);  // Agregar la capa de seguridad al mapa
});



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

// Crear una capa para los lugares deportivos usando GeoJSON
var deportesLayer = L.geoJson(null, {
  // Definir cómo se representan los puntos (marcadores) de deportes en el mapa
  pointToLayer: function (feature, latlng) {
    return L.marker(latlng, {
      // Usar un ícono personalizado para los marcadores
      icon: L.icon({
        iconUrl: "assets/img/depo.png",  // URL del ícono (deportes)
        iconSize: [24, 28],  // Tamaño del ícono
        iconAnchor: [12, 28],  // Anclaje del ícono
        popupAnchor: [0, -25]  // Anclaje del popup
      }),
      title: feature.properties.nombre,  // Título del marcador (nombre del deporte)
      riseOnHover: true  // Hacer que el marcador se eleve cuando el cursor pase sobre él
    });
  },

  // Definir las interacciones con cada entidad (punto) del GeoJSON
  onEachFeature: function (feature, layer) {
    // Agregar la ubicación deportiva a la lista de búsqueda
    buscarSitio.push({
      name: layer.feature.properties.nombre,
      source: "Deportes",  // Fuente de datos (Deportes)
      id: L.stamp(layer)  // ID único para cada marcador
    });

    // Definir el contenido del popup que aparece al hacer clic en el marcador
    layer.bindPopup(
      "<div style='text-align:center'><h3>" +
      // Botones para insertar, editar y eliminar un deporte
      '<button type="button" onclick="document.getElementById(\'nuevodeporteModal\').style.display=\'block\'; setTimeout(mapDeporte.invalidateSize(), 1000);" class="btn btn-success" data-toggle="modal" data-target="#nuevodeporteModal">Insertar</button> ' +
      '<a href="report/editaDeporte.php?codigo=' + feature.properties.id + '" class="btn btn-default">Editar</a> ' +
      '<a href="report/eliminaDeporte.php?codigo=' + feature.properties.id + '" class="btn btn-danger" onclick="return confirm(\'¿Seguro?\')">Eliminar</a>' +
      "</h3><hr style='height:2px;border-width:0;color:gray;background-color:gray'></div>" +
      "<table>" +
      "<tr><td>Tipo: " + feature.properties.tipo + "</td></tr>" +  // Mostrar tipo de deporte
      "<tr><td>Nombre: " + feature.properties.nombre + "</td></tr>" +  // Nombre del lugar deportivo
      "<tr><td>Servicios: " + feature.properties.servicios + "</td></tr>" +  // Servicios ofrecidos
      "<tr><td>Horarios: " + feature.properties.horarios + "</td></tr>" +  // Horarios disponibles
      "<tr><td>Dirección: " + feature.properties.direccion + "</td></tr>" +  // Dirección del lugar
      "<tr><td>Teléfono: " + feature.properties.telefono + "</td></tr>" +  // Teléfono de contacto
      "</table>",
      { minWidth: 250, maxWidth: 300 }  // Establecer el tamaño máximo y mínimo del popup
    );
  }
});

// Cargar los datos de los deportes desde un archivo JSON y añadirlos a la capa
$.getJSON("report/deportes.php", function (data) {
  deportesLayer.addData(data);  // Añadir los datos a la capa de deportes
  map.addLayer(deportesLayer);  // Agregar la capa de deportes al mapa
});

// Crear el mapa interactivo
map = L.map("map", {
  zoom: 14,  // Nivel de zoom inicial
  center: [-33.00759, -68.654432],  // Coordenadas del centro del mapa
  layers: [osm, manzanas, highlight],  // Capas iniciales del mapa (OSM, manzanas, capa de resaltado)
  zoomControl: false,  // Desactivar el control de zoom por defecto
  attributionControl: false  // Desactivar el control de atribución por defecto
});

// Filtro para la lista de características en la barra lateral, mostrando solo los elementos dentro de los límites actuales del mapa
map.on("moveend", function (e) {
  // Esta función se podría usar para actualizar la barra lateral cuando el mapa se mueve
  //syncSidebar();
});

// Limpiar el resaltado de características cuando se hace clic en el mapa
map.on("click", function(e) {
  highlight.clearLayers();  // Limpiar cualquier capa de resaltado
});

// Agregar un control de zoom personalizado al mapa
var barraZoom = new L.Control.ZoomBar({position: 'topleft'}).addTo(map);

// Control de localización que muestra la ubicación actual del usuario en el mapa
var locateControl = L.control.locate({
  position: "bottomright",  // Posición del control en la esquina inferior derecha
  drawCircle: true,  // Dibujar un círculo alrededor de la ubicación
  follow: true,  // Seguir la ubicación del usuario
  setView: true,  // Centrar el mapa en la ubicación
  keepCurrentZoomLevel: true,  // Mantener el nivel de zoom actual
  markerStyle: {  // Estilo del marcador de localización
    weight: 1,
    opacity: 0.8,
    fillOpacity: 0.8
  },
  circleStyle: {  // Estilo del círculo de localización
    weight: 1,
    clickable: false
  },
  icon: "fa fa-location-arrow",  // Ícono que se muestra para la localización
  metric: false,  // No usar unidades métricas
  strings: {  // Mensajes que se muestran en el control
    title: "¿Dónde estoy ahora?",  // Título del control
    popup: "You are within {distance} {unit} from this point",  // Mensaje de popup con la distancia
    outsideMapBoundsMsg: "You seem located outside the boundaries of the map"  // Mensaje si el usuario está fuera de los límites del mapa
  },
  locateOptions: {  // Opciones adicionales para la localización
    maxZoom: 18,  // Nivel de zoom máximo cuando se localiza
    watch: true,  // Vigilar la ubicación continuamente
    enableHighAccuracy: true,  // Habilitar la alta precisión de GPS
    maximumAge: 10000,  // Usar la ubicación solo si tiene menos de 10 segundos
    timeout: 10000  // Tiempo de espera de 10 segundos para obtener la ubicación
  }
}).addTo(map);

// Ajustar el control de la barra lateral según el tamaño de la pantalla
if (document.body.clientWidth <= 767) {
  var isCollapsed = true;  // Si el tamaño de la pantalla es pequeño, la barra lateral está colapsada
} else {
  var isCollapsed = false;  // Si el tamaño de la pantalla es grande, la barra lateral está expandida
}

// Definir las capas base (mapas de referencia)
var baseLayers = {
  "Topo Map": otm,  // Mapa topográfico
  "Street Map": osm,  // Mapa de calles
  "Esri Topografico": esriTopo,  // Mapa topográfico de Esri
  "Esri Satelite": esriWorldImagery,  // Mapa satelital de Esri
  "Esri Callejero": esriStreet,  // Mapa callejero de Esri
  "Google Satelite": google  // Mapa satelital de Google
};

// Definir los overlays (capas adicionales, como puntos de interés)
var groupedOverlays = {
  "Puntos de interes": {
    "<img src='assets/img/theater.png' width='24' height='28'>&nbsp;Sitios": sitios,  // Sitios de interés
    "<img src='assets/img/salud.png' width='24' height='28'>&nbsp;Salud": saludLayer,  // Centros de salud
    "<img src='assets/img/policia.png' width='24' height='28'>&nbsp;Seguridad": seguridadLayer,  // Centros de seguridad
    "<img src='assets/img/depo.png' width='24' height='28'>&nbsp;Deportes": deportesLayer  // Lugares deportivos
  },
  "Capas": {
    "Barrios": manzanas,  // Capas de barrios
    "Calles": calles,  // Capas de calles
    "Distritos": distritosLayer  // Capas de distritos
  }
};

// Crear un control de capas agrupadas que permite al usuario elegir entre las capas base y las capas adicionales
var layerControl = L.control.groupedLayers(baseLayers, groupedOverlays, {
  collapsed: isCollapsed  // Determinar si el control de capas está colapsado según el tamaño de la pantalla
}).addTo(map);

/* Resaltar el texto de la barra de búsqueda al hacer clic */
$("#searchbox").click(function () {
  $(this).select(); // Selecciona todo el texto en la barra de búsqueda cuando se hace clic en ella.
});

/* Prevenir que la tecla Enter recargue la página */
$("#searchbox").keypress(function (e) {
  if (e.which == 13) { // Si la tecla presionada es Enter (código 13)
    e.preventDefault(); // Evita la acción predeterminada de recargar la página.
  }
});

/* Manejar evento de cierre del modal */
$("#featureModal").on("hidden.bs.modal", function (e) {
  // Vuelve a agregar el evento mouseout para deseleccionar resaltados de las filas de características.
  $(document).on("mouseout", ".feature-row", clearHighlight);
});

/* Funcionalidad de búsqueda con Typeahead */
$(document).one("ajaxStop", function () {
  $("#loading").hide(); // Oculta el ícono de carga cuando se completan las solicitudes AJAX.
  sizeLayerControl(); // Ajusta el control de capas del mapa.

  /* Ajustar el mapa a los límites de las manzanas */
  map.fitBounds(manzanas.getBounds());

  // Inicializa la lista de características para la búsqueda.
  featureList = new List("features", { valueNames: ["feature-name"] });
  // featureList.sort("feature-name", { order: "asc" }); // Ordenar alfabéticamente (desactivado por ahora).

  /* Configurar motores de búsqueda Bloodhound */
  var manzanasBH = new Bloodhound({
    name: "Manzanas",
    datumTokenizer: function (d) {
      return Bloodhound.tokenizers.whitespace(d.name); // Tokeniza por espacios en blanco.
    },
    queryTokenizer: Bloodhound.tokenizers.whitespace,
    local: buscarManzana, // Datos locales para buscar manzanas.
    limit: 10 // Limita los resultados a 10.
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
    remote: { // Configuración para búsqueda remota con GeoNames.
      url: "http://api.geonames.org/searchJSON?username=bootleaf&featureClass=P&maxRows=5&countryCode=US&name_startsWith=%QUERY",
      filter: function (data) {
        // Procesa los resultados remotos y devuelve un formato estándar.
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
          // Agrega límites geográficos actuales a la URL de la consulta.
          settings.url += "&east=" + map.getBounds().getEast() +
            "&west=" + map.getBounds().getWest() +
            "&north=" + map.getBounds().getNorth() +
            "&south=" + map.getBounds().getSouth();
          $("#searchicon").removeClass("fa-search").addClass("fa-refresh fa-spin"); // Muestra un icono de carga.
        },
        complete: function (jqXHR, status) {
          // Vuelve al icono de búsqueda después de completar la solicitud.
          $('#searchicon').removeClass("fa-refresh fa-spin").addClass("fa-search");
        }
      }
    },
    limit: 10
  });

  // Inicializar motores Bloodhound
  manzanasBH.initialize();
  sitiosBH.initialize();
  museumsBH.initialize();
  geonamesBH.initialize();
  viasBH.initialize();

  /* Configuración de Typeahead */
  $("#searchbox").typeahead({
    minLength: 3, // Mínimo 3 caracteres para comenzar a buscar.
    highlight: true, // Resalta las coincidencias.
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
    // Acción según la fuente del dato seleccionado.
    if (datum.source === "Manzanas" || datum.source === "Vias") {
      map.fitBounds(datum.bounds);
    }
    if (datum.source === "Sitios") {
      if (!map.hasLayer(sitiosLayer)) {
        map.addLayer(sitiosLayer);
      }
      map.setView([datum.lat, datum.lng], 17);
      if (map._layers[datum.id]) {
        map._layers[datum.id].fire("click"); // Dispara evento de clic en el marcador.
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
      map.setView([datum.lat, datum.lng], 14); // Ajusta el mapa a una vista cercana.
    }
    if ($(".navbar-collapse").height() > 50) {
      $(".navbar-collapse").collapse("hide"); // Colapsa la barra de navegación si está expandida.
    }
  }).on("typeahead:opened", function () {
    // Ajusta la altura máxima de la barra de navegación al abrir.
    $(".navbar-collapse.in").css("max-height", $(document).height() - $(".navbar-header").height());
    $(".navbar-collapse.in").css("height", $(document).height() - $(".navbar-header").height());
  }).on("typeahead:closed", function () {
    // Restablece el estilo de la barra de navegación al cerrar.
    $(".navbar-collapse.in").css("max-height", "");
    $(".navbar-collapse.in").css("height", "");
  });

  // Estilo para la UI de Typeahead.
  $(".twitter-typeahead").css("position", "static");
  $(".twitter-typeahead").css("display", "block");
});

/* Parche para que el control de capas en Leaflet sea desplazable en navegadores táctiles */
var container = $(".leaflet-control-layers")[0];
if (!L.Browser.touch) {
  L.DomEvent
    .disableClickPropagation(container)
    .disableScrollPropagation(container);
} else {
  L.DomEvent.disableClickPropagation(container);
}
