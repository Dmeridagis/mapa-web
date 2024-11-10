<?php
include 'report/seguridad.php';


?>
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
          <div class="navbar-icon-container">
            <a href="#" class="navbar-icon pull-right visible-xs" id="nav-btn"><i class="fa fa-bars fa-lg white"></i></a>
            <a href="#" class="navbar-icon pull-right visible-xs" id="sidebar-toggle-btn"><i class="fa fa-search fa-lg white"></i></a>
          </div>
          <a class="navbar-brand" href="#">MAPAWEB MAIPÚ</a>
        </div>
        <div class="navbar-collapse collapse">
          <form class="navbar-form navbar-right" role="search">
            <div class="form-group has-feedback">
                <input id="searchbox" type="text" placeholder="Search" class="form-control">
                <span id="searchicon" class="fa fa-search form-control-feedback"></span>
            </div>
          </form>
          <ul class="nav navbar-nav">
            <li><a href="report/cerrar.php" ><i class="fa fa-question-circle white"></i>&nbsp;&nbsp;Cerrar sesion</a></li>
            <li class="dropdown">
              <a id="toolsDrop" href="#" role="button" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-globe white"></i>&nbsp;&nbsp;Herramientas <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="full-extent-btn"><i class="fa fa-arrows-alt"></i>&nbsp;&nbsp;Zoom extension total</a></li>
                <li class="divider hidden-xs"></li>				
                <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="nuevo-btn" onclick="document.getElementById('nuevoModal').style.display='block'; setTimeout(mapi.invalidateSize(), 1000);"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Nueva manzana</a></li>
				<li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="botonvia" onclick="document.getElementById('nuevaviaModal').style.display='block'; setTimeout(mapVia.invalidateSize(), 1000);"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Nueva via</a></li>
				<li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="botonpuntointeres" onclick="document.getElementById('nuevositioModal').style.display='block'; setTimeout(mapSitio.invalidateSize(), 1000);"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Nuevo Punto de interes</a></li>
        <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="botonsalud" onclick="document.getElementById('nuevosaludModal').style.display='block'; setTimeout(mapSalud.invalidateSize(), 1000);"><i class="fa fa-picture-o"></i>&nbsp;&nbsp;Nuevo centro de salud</a></li>     
        <li><a href="#" data-toggle="collapse" data-target=".navbar-collapse.in" id="botonpolicial" onclick="document.getElementById('nuevopolicialModal').style.display='block'; setTimeout(mapPolicial.invalidateSize(), 1000);"><i class="fa fa-shield"></i>&nbsp;&nbsp;Nuevo punto seguridad</a></li>

      </ul>
            </li>
            <li class="dropdown">
                <a class="dropdown-toggle" id="downloadDrop" href="#" role="button" data-toggle="dropdown"><i class="fa fa-cloud-download white"></i>&nbsp;&nbsp;Descargar <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  <li><a href="report/manzana.php" download="manzana.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Manzanas</a></li>
                  <li><a href="report/vias.php" download="vias.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Vias</a></li>
                  <li><a href="report/sitios.php" download="sitios.geojson" target="_blank" data-toggle="collapse" data-target=".navbar-collapse.in"><i class="fa fa-download"></i>&nbsp;&nbsp;Puntos de interes</a></li>
                </ul>
            </li>
          </ul>
        </div><!--/.navbar-collapse -->
      </div>
    </div>

    <div id="container">     
      <div id="map"></div>
    </div>
    <div id="loading">
      <div class="loading-indicator">
        <div class="progress progress-striped active">
          <div class="progress-bar progress-bar-info progress-bar-full"></div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="nuevoModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Adicionar</h4>
          </div>
          <div class="modal-body">

			<div class="row">
                <div class="col-md-8">
					<div id="map2" style="height: 400px"></div>
				</div>
				<div class="col-md-4">
					<form method="post" id="insert_form">
						<label>Codigo</label>
						<input type="text" name="txtcodigo" id="txtcodigo" class="form-control"  readonly />
						<br />
						<label>Id sector</label>
						<input type="text" name="txtidsector" id="txtidsector" class="form-control"  />
						<br />
						<label>Manzana</label>
						<input type="text" name="txtcodmanzana" id="txtcodmanzana" class="form-control"  placeholder="001"/>
						<br />
						<label>Coordenadas</label>
						<textarea name="txtgeo" id="txtgeo" class="form-control" readonly></textarea>
						<br />
						<input type="submit" name="insert" id="insert" value="Adicionar" class="btn btn-success" />
					</form>
				</div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div></div><!-- /.modal -->
	


    <div class="modal fade" id="nuevaviaModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Adicionar</h4>
          </div>
          <div class="modal-body">

			<div class="row">
                <div class="col-md-8">
					<div id="map3" style="height: 400px"></div>
				</div>
				<div class="col-md-4">
					<form method="post" id="insert_form_via">
						<label>Codigo</label>
						<input type="text" name="txtcodigo" id="txtcodigo" class="form-control"  readonly />
						<br />
						<label>Tipo</label>
						<input type="text" name="txttipo" id="txttipo" class="form-control" placeholder="AV / CA / JR" />
						<br />
						<label>Nombre</label>
						<input type="text" name="txtnombre" id="txtnombre" class="form-control"  placeholder="SAN JUAN"/>
						<br />
						<label>Coordenadas</label>
						<textarea name="txtgeovia" id="txtgeovia" class="form-control" readonly></textarea>
						<br />
						<input type="submit" name="insertvia" id="insertvia" value="Adicionar" class="btn btn-success" />
					</form>
				</div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div></div><!-- /.modal -->
	


	  <div class="modal fade" id="nuevositioModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Adicionar</h4>
          </div>
          <div class="modal-body">

			<div class="row">
                <div class="col-md-8">
					<div id="map4" style="height: 400px"></div>
				</div>
				<div class="col-md-4">
					<form method="post" id="insert_form_sitio">
						<label>Codigo</label>
						<input type="text" name="txtcodigo" id="txtcodigo" class="form-control"  readonly />
						<br />
						<label>Tipo</label>
						<input type="text" name="txttipositio" id="txttipositio" class="form-control" placeholder="BALLET" />
						<br />
						<label>Nombre</label>
						<input type="text" name="txtnombresitio" id="txtnombresitio" class="form-control"  placeholder="DANIEL MERIDA"/>
						<br />
						<label>Coordenadas</label>
						<textarea name="txtgeositio" id="txtgeositio" class="form-control" readonly></textarea>
						<br />
						<input type="submit" name="insertsitio" id="insertsitio" value="Adicionar" class="btn btn-success" />
					</form>
				</div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div></div><!-- /.modal -->


    <div class="modal fade" id="nuevosaludModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h4 class="modal-title">Adicionar Centro de Salud</h4>
      </div>
      <div class="modal-body">
        <div class="row">
          <div class="col-md-8">
            <div id="map6" style="height: 400px"></div>
          </div>
          <div class="col-md-5">
          <form method="post" id="insert_form_salud">
    <div class="form-group">
        <label for="txtcodigo">Código</label>
        <input type="text" name="txtcodigo" id="txtcodigo" class="form-control" readonly />
    </div>
    <div class="form-group">
        <label for="dto">Departamento</label>
        <input type="text" name="dto" id="dto" class="form-control" required />
    </div>
    <div class="form-group">
        <label for="n_ctro">N° Centro</label>
        <input type="text" name="n_ctro" id="n_ctro" class="form-control" required />
    </div>
    <div class="form-group">
        <label for="estratif">Estratificación</label>
        <input type="text" name="estratif" id="estratif" class="form-control" required />
    </div>
    <div class="form-group">
        <label for="nombre">Nombre</label>
        <input type="text" name="nombre" id="nombre" class="form-control" required />
    </div>
    <div class="form-group">
        <label for="domicilio">Domicilio</label>
        <input type="text" name="domicilio" id="domicilio" class="form-control" required />
    </div>
    <div class="form-group">
        <label for="telefono">Teléfono</label>
        <input type="text" name="telefono" id="telefono" class="form-control" required />
    </div>
    <div class="form-group">
        <label for="geom">Geometría (dibuje en el mapa)</label>
        <textarea name="geom" id="txtgeosalud" class="form-control" readonly required></textarea>
    </div>
    <button type="submit" name="insertsalud" id="insertsalud" class="btn btn-success">Adicionar</button>
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

<div class="modal fade" id="nuevopolicialModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h4 class="modal-title">Adicionar Punto Policial</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div id="mapPolicial" style="height: 400px"></div>
                    </div>
                    <div class="col-md-5">
                        <form method="post" id="insert_form_policial">
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <input type="text" name="codigo" id="codigo" class="form-control" readonly />
                            </div>
                            <div class="form-group">
                                <label for="gid">GID</label>
                                <input type="text" name="gid" id="gid" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="id_segurid">ID Seguridad</label>
                                <input type="text" name="id_segurid" id="id_segurid" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="id_usuario">ID Usuario</label>
                                <input type="text" name="id_usuario" id="id_usuario" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input type="text" name="telefono" id="telefono" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input type="text" name="direccion" id="direccion" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <input type="text" name="tipo" id="tipo" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control" required />
                            </div>
                            <div class="form-group">
                                <label for="geom">Geometría (dibuje en el mapa)</label>
                                <textarea name="geom" id="txtgeopolicial" class="form-control" readonly required></textarea>
                            </div>
                            <button type="submit" name="insertpolicial" id="insertpolicial" class="btn btn-success">Adicionar</button>
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
	<script src="js/via.js"></script>
  <script src="js/salud.js"></script>
	<script src="js/sitio.js"></script>
  <script src="js/policial.js"></script>
	

  </body>
</html>
