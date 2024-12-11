<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="initial-scale=1,user-scalable=no,maximum-scale=1,width=device-width">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="theme-color" content="#000000">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>MAPAWEB MAIPÚ</title>

    <link rel="stylesheet" href="css/bootstrap.min.css">  <!--EL HEADER-->
    <link rel="stylesheet" href="css/login.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">    <!--ICONOS DEL HEADER-->
    <link rel="stylesheet" href="css/leaflet.css"> <!--EL MAPA-->
    <link rel="stylesheet" href="css/MarkerCluster.css">
    <link rel="stylesheet" href="css/MarkerCluster.Default.css">
    <link rel="stylesheet" href="css/L.Control.Locate.css">
	<link href="css/leaflet.draw.css" rel="stylesheet" type="text/css"/>	
	<link rel="stylesheet" href="osm/Control.OSMGeocoder.css"/>
	<link rel="stylesheet" type="text/css" href="css/L.Control.ZoomBar.css"/>
	<link rel="stylesheet" href="assets/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.css">
    <link rel="stylesheet" href="assets/css/app.css"> <!--ESTILO DEL MAPA GENERAL-->
    <link rel="Icon" href="assets/img/logomaipu2.png">
    
    

	
    <script src="js/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/typeahead.bundle.min.js"></script>
    <script src="js/handlebars.min.js"></script>
    <script src="js/list.min.js"></script>
    <script src="js/leaflet.js"></script>
    <script src="js/leaflet.markercluster-src.js"></script>
    <script src="js/L.Control.Locate.min.js"></script>
	<script type="text/javascript" src="js/L.Control.ZoomBar.js"></script>
	<script src="js/leaflet.draw.js" type="text/javascript"></script>
	<script src="osm/Control.OSMGeocoder.js"></script>
    <script src="assets/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.js"></script>
    <script src="https://kit.fontawesome.com/0273d565ab.js" crossorigin="anonymous"></script>
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top navbar-custom" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
            <img src="assets/img/logomaipu.png">
          <h1 class="nav-logo">MUNI</h1>

          <label for="menu" class="nav-label">
             <i class="fa-solid fa-bars"></i>
          </label>
          <input type="checkbox" class="nav-input" id="menu">

        <div class="nav-menu">

        <li class="nav-item">
            <a href="perfil/perfil.php">
              <i class="fa-solid fa-user"></i>&nbsp;&nbsp;Perfil
            </a>
          </li>

          <li class="nav-item">
              <a href="#" id="realizarReclamo" data-toggle="modal" data-target="#reclamoModal">
              <i class="fa-solid fa-envelope"></i>&nbsp;&nbsp;Realizar reclamo
              </a>
          </li>

          <li class="nav-item">
              <a href="report/cerrar.php" >
                    <i class="fa-solid fa-door-closed"></i>&nbsp;&nbsp;Cerrar sesión
               </a>
          </li>
          </div>

        </div>
      </div>
    </div>

    <!--ESTE DIV MUESTRA EL MAPA-->
    <div id="container">     
      <div id="map"></div>
    </div>
    <!--ESTE DIV MUESTRA EL MAPA-->
		
    <div class="modal fade" id="reclamoModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Realizar Reclamo</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <!-- Aquí se mostrará el mapa -->
                        <div id="map5" style="height: 400px"></div>

                    </div>
                    <div class="col-md-4">
                        <form autocomplete="off" method="post" id="reclamo_form" enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="txtFechaReclamo">Fecha</label>
                                <input type="date" name="txtFechaReclamo" id="txtFechaReclamo" class="form-control" min="2024-01-01"required />
                            </div>
                            <div class="form-group">
                                <label for="txtTipoReclamo">Tipo de Reclamo</label>
                                <select name="txtTipoReclamo" id="txtTipoReclamo" class="form-control" required >
                                <option value="">Selecciona un tipo de reclamo</option>
                                    <option value="Salud">Salud</option>
                                     <option value="Deportes">Deportes</option>
                                     <option value="Seguridad">Seguridad</option>
                                     <option value="Sitios">Sitios</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="txtDetalleReclamo">Detalles del Reclamo</label>
                                <textarea name="txtDetalleReclamo" id="txtDetalleReclamo" class="form-control" placeholder="Escribe aquí tu reclamo" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="imagenReclamo" class="btnrecla">Añadir Imagen (Opcional)</label>
                                 <input type="file" name="imagenReclamo" id="imagenReclamo" style="display:none;" class="file-input" accept="image/*"/>
                            </div>

                            <div class="form-group">
                                <label for="txtgeoreclamo">Coordenadas</label>
                                <textarea name="txtgeoreclamo" id="txtgeoreclamo" class="form-control" readonly></textarea>
                            </div>
                


                            <!-- Botón para enviar el reclamo -->
                            <button type="submit" name="enviarReclamo" class="btn btn-success">Enviar Reclamo</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


    <script src="assets/js/app1.js"></script>
	<script src="js/mapa.js"></script>
	<script src="js/reclamos.js"></script>
  </body>
</html>
