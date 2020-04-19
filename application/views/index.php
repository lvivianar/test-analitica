<!DOCTYPE html>
<html>
	<head>
		<title>TEST Files Analitica</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="description" content="Demo project with jQuery">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
		<style type="text/css">
			body > .container-fluid {
					padding: 60px 15px 0;
			}
			
			li { cursor: pointer; }
		</style>
		
	</head>
	<body>
	<div id="content">
		<nav class="navbar navbar-default navbar-fixed-top">
			<div class="container">
				<div id="navbar" class="collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li><a id="btnBorrar">Limpiar tablas</a></li>
						<li><a id="btnGetFiles">Obtener archivos</a></li>
						<li><a id="btnSearchAll" data-accion="getAll">Listar todos</a></li>
						<li><a id="btnSearchCount" data-accion="getCount">Listar conteo</a></li>						
					</ul>
				</div> 
			</div>
		</nav>
		<br />
		<br />
		<br />
		<br />
		<div class="container-fluid" id="divContenido">
			<h1>Test Files Analitica!</h1>
		</div>
		<div id="cargando"  class="modal fade in" style=" display: block; text-align: center; margin: 150px auto; z-index: 150000;">
            <div class="row">
                <label class='control-label'><img src="<?php echo base_url('public/img/loading-spinner-blue.gif'); ?>" /> Cargando...</label>
            </div>
        </div>
	</div>
	</body>
	<script src="https://code.jquery.com/jquery-3.1.1.min.js" integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script src="<?php echo base_url('public/js/js.js'); ?>" type="text/javascript"></script>
</html>