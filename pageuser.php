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

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/leaflet.css">
    <link rel="stylesheet" href="css/MarkerCluster.css">
    <link rel="stylesheet" href="css/MarkerCluster.Default.css">
    <link rel="stylesheet" href="css/L.Control.Locate.css">
	<link href="css/leaflet.draw.css" rel="stylesheet" type="text/css"/>	
	<link rel="stylesheet" href="osm/Control.OSMGeocoder.css"/>
	<link rel="stylesheet" type="text/css" href="css/L.Control.ZoomBar.css"/>
	<link rel="stylesheet" href="assets/leaflet-groupedlayercontrol/leaflet.groupedlayercontrol.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/favicon-76.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/img/favicon-120.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/img/favicon-152.png">
    <link rel="icon" sizes="196x196" href="assets/img/favicon-196.png">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
	
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
  </head>

  <body>
    <div class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <a class="navbar-brand" href="#">MAPAWEB MAIPÚ</a>
    
          <li><a href="report/cerrar.php" ><i class="fa fa-question-circle white"></i>&nbsp;&nbsp;Cerrar sesión</a></li>
          <li>
              <a href="#" id="realizarReclamo" data-toggle="modal" data-target="#reclamoModal">
                  <i class="fa fa-bell"></i>&nbsp;&nbsp;Realizar reclamo
              </a>
          </li>
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
                        <form method="post" id="reclamo_form" enctype="multipart/form-data" >
                            <div class="form-group">
                                <label for="txtFechaReclamo">Fecha</label>
                                <input type="date" name="txtFechaReclamo" id="txtFechaReclamo" class="form-control" min="2024-01-01"required />
                            </div>
                            <div class="form-group">
                                <label for="txtTipoReclamo">Tipo de Reclamo</label>
                                <input type="text" name="txtTipoReclamo" id="txtTipoReclamo" class="form-control" placeholder="Tipo de Reclamo" required />
                            </div>
                            <div class="form-group">
                                <label for="txtDetalleReclamo">Detalles del Reclamo</label>
                                <textarea name="txtDetalleReclamo" id="txtDetalleReclamo" class="form-control" placeholder="Escribe aquí tu reclamo" required></textarea>
                            </div>

                            <div class="form-group">
                                <label for="imagenReclamo">Añadir Imagen (Opcional)</label>
                                 <input type="file" name="imagenReclamo" id="imagenReclamo" class="file-input" accept="image/*"/>
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


    <script src="assets/js/app.js"></script>
	<script src="js/mapa.js"></script>
	<script src="js/reclamos.js"></script>
  </body>
</html>
