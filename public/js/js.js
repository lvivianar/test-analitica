if(window.location.host == 'localhost'){
  var SITE_URL = "http://localhost/analitica/";;
}else{
  var SITE_URL = "http://localhost/analitica/";;
}

$(function(){ 
	cerrarCargando();
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** captura el evento click en el boton borrar y llama al controlador con su respectiva funcion , mediante AJAX
	** 18 abril 2020
	*/
	$("#btnBorrar").on('click', function(){
        cargando();
		$.ajax({
			url: SITE_URL+'Analitica/deleteDB',
			type: 'GET',
			dataType: 'json',
			success: function(resp) {				
				$("#divContenido").html('<div class="alert alert-success" role="alert"><h1>'+
				  resp.resultado+
				'</h1></div>');
				cerrarCargando();

			},
			error: function() {
				$("#divContenido").html('<div class="alert alert-danger" role="alert"><h1>Ocurrio un problema, por favor actualice la página y vuelva a intentarlo.</h1></div>');
				cerrarCargando();
			}
		}) 
	});
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** captura el evento click en el boton para obtener los archivos mediante el web service y llama al controlador con su respectiva funcion , mediante AJAX,
	** posteriormente muestra el resultado HTML en un DIV
	** 18 abril 2020
	*/	
	$("#btnGetFiles").on('click', function(){
        cargando();
		$.ajax({
			url: SITE_URL+'Analitica/getFiles',
			type: 'GET',
			dataType: 'json',
			success: function(resp) {
				if(resp.status == 'ok'){
					$("#divContenido").html('<div class="alert alert-success" role="alert"><h1>'+
					  resp.conteo+
					'</h1></div>');
					cerrarCargando();
				}else{
					$("#divContenido").html('<div class="alert alert-danger" role="alert"><h1>'+
					  resp.resultado+
					'</h1></div>');
					cerrarCargando();
				}
			},
			error: function() {
				$("#divContenido").html('<div class="alert alert-danger" role="alert"><h1>Ocurrio un problema, por favor actualice la página y vuelva a intentarlo.</h1></div>');
				cerrarCargando();
			}
		}) 
	});
	
	
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** captura el evento click para cualquier de los dos botones que consulta, luego leyendo el valor del atributo de datos data-accion, determina que metodo del controlador debera ejecutar,  lo llama mediante AJAX,
	** posteriormente muestra el resultado HTML en un DIV
	** 18 abril 2020
	*/
	$('a[id^="btnSearch"]').on('click', function(){
        cargando();
		
		var accion = $(this).attr("data-accion");
		$.ajax({
			url: SITE_URL+'Analitica/'+accion,
			type: 'GET',
			dataType: 'json',
			success: function(resp) {
				if(resp.status == 'ok'){
					$("#divContenido").html(resp.table);
					cerrarCargando();
				}else{
					$("#divContenido").html('<div class="alert alert-danger" role="alert"><h1>'+
					  resp.resultado+
					'</h1></div>');
					cerrarCargando();
				}
			},
			error: function() {
				$("#divContenido").html('<div class="alert alert-danger" role="alert"><h1>Ocurrio un problema, por favor actualice la página y vuelva a intentarlo.</h1></div>');
				cerrarCargando();
			}
		}) 
	});
});


/*
** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
** funciones que muestran y ocultan el DIV "cargando", para indicar que un proceso esta en ejecucion
** 18 abril 2020
*/

//muestra mensaje de cargando
function cargando(){
  $('#cargando').show();
    $("#content").css({ opacity: 0.2 });
}

//cerrar mensaje de cargando
function cerrarCargando(){
  $('#cargando').hide();
  $("#content").css({ opacity: 1 });
}