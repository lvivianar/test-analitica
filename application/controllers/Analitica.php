<?php
if ( ! defined('BASEPATH')) exit('No se permite el acceso directo a las p&aacute;ginas de este sitio.');

class Analitica extends CI_Controller {

	function  __construct() {
		parent::__construct(); 
		$this->load->library('Nu_soap');
		$this->load->model('Analitica_model');
	} 

	function index() {
	} 
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** metodo encargado de cargar la vista principal
	** 18 abril 2020
	*/
	function testFiles() {
		try {
			$this->load->view('index');			
		}
		catch(Exception $e) {
			echo $e->getMessage();
		}
	} 
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** metodo encargado de consumir el servicio ServiciosAZDigital.wsdl, usando la operacion BuscarArchivo
	** 18 abril 2020
	*/
	function getFiles() {

		$wsdlUrl = "http://test.analitica.com.co/AZDigital_Pruebas/WebServices/ServiciosAZDigital.wsdl";
		$wsdlUrl2 = "http://test.analitica.com.co/AZDigital_Pruebas/WebServices/SOAP/index.php";

		ini_set('soap.wsdl_cache_enabled', 0); 		// Define almacenamiento en cache
		ini_set('soap.wsdl_cache_ttl', 60);			// Tiempo de vida que permanecera un fichero en cache
		ini_set('default_socket_timeout', 1200);	// Tiempo de espera para sockets
		ini_set('max_execution_time', 300);			// Tiempo maximo de ejecucion para un script
		set_time_limit(300);

		try {
			$this->load->model('Analitica_model');
			
			/* Se crea un objeto SOAP para el cliente, activando el seguimiento a los fallos, el tiempo de espera para la conexion del servicio, */
			$client = new SoapClient($wsdlUrl, array(
			'trace' =>true,
			'connection_timeout' => 500000,
			'cache_wsdl' => WSDL_CACHE_BOTH,
			'keep_alive' => false,
			));
			
			/* Mediante el metodo setLocation se le indica al constructor cual es el endpoint del web service a consumir */
			$client->__setLocation($wsdlUrl2);
			
			/* Se hace la llamada a la operacion BuscarArchivo del web service enviando los respectivos parametros de busqueda */
			$result = $client->__soapCall('BuscarArchivo', 	['body' => ['Condiciones' => ['Condicion' => ['Tipo' => 'FechaInicial', 'Expresion' => '2019-07-01 00:00:00']]]]);
		
			/* Obtenemos la respuesta XML enviada por el web service */
			$xmlResponse = $client->__getLastResponse();

			/* Se crea un archivo XML con la respuesta obtenida*/
			write_file("public/xslt/insertFiles.xml", $xmlResponse);

			/* Se carga el documento XML a memoria */
			$xml = new DOMDocument;
			$xml->load("public/xslt/insertFiles.xml");

			/* Se carga la plantilla XSLT a memoria */
			$xsl = new DOMDocument;
			$xsl->load("public/xslt/insertFiles.xsl");

			/* Configura el procesador XSLT, adjuntandole las reglas definidas en nuestro archivo XSL */
			$proc = new XSLTProcessor;
			$proc->importStyleSheet($xsl);

			/* Se "aplica" nuestra plantilla XSL al archivo XML y se guarda el resultado en una variable */
			$inserts = $proc->transformToXML($xml);

			/* Procesamiento de los INSERT obtenidos de las reglas aplicadas en nuestro XSL */
			$arrayInserts = (explode("%|%", $inserts));
			$conteo = 0;
			foreach($arrayInserts as $linea){
				if(strpos($linea, "INSERT") !== false){
					$linea = str_replace("  ", "", $this->trim_all($linea));
					$conteo += $this->Analitica_model->insertar($linea);
				}				
			}
			delete_files("public/xslt/insertFiles.xml");
			
			/* Se envia respuesta a nuestra vista mediante AJAX */
			echo json_encode(array('status' => 'ok', 'conteo' => 'Se han insertado '.round(($conteo / 2), PHP_ROUND_HALF_DOWN).' registros exitosamente!!'));
		}
		catch(Exception $e) {
			echo json_encode(array('status' => 'error', 'resultado' => $e->getMessage()));
		}
	}

	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** metodo encargado de consultar todos los archivos insertados, procesarlo a través de una plantilla XSLT y generar el reporte en HTML
	** 18 abril 2020
	*/
	public function getAll(){
		try{
			
			$this->load->model('Analitica_model');
			
			/* Consulta a nuestra base de datos a traves del modelo definido */
			$datos = $this->Analitica_model->getAll();
			
			/* Se definen la configuracion para construir nuestro XML */
			$config = array (
					'root'          => 'root',
					'element'       => 'archivo',
					'newline'       => "\n",
					'tab'           => "\t"
			);
			
			/* Se carga clase DBUtil --> clase que contiene utilidades para operar sobre nuestra base de datos */
			$this->load->dbutil();
			header('Content-Type: application/xml; charset=utf-8');
			
			/* Este metodo permite convertir el resultado de nuestra consulta SQL en un XML */
			$xml = $this->dbutil->xml_from_result($datos, $config);
			
			write_file("public/xslt/getAll.xml", $xml);
			
			$xml = new DOMDocument;
			$xml->load("public/xslt/getAll.xml");

			$xsl = new DOMDocument;
			$xsl->load("public/xslt/getAll.xsl");


			$proc = new XSLTProcessor;
			$proc->importStyleSheet($xsl);

			$table = $proc->transformToXML($xml);
			
			echo json_encode(array('status' => 'ok', 'table' => $table));
		}catch(Exception $e) {
			echo json_encode(array('status' => 'error', 'resultado' => $e->getMessage()));
		}		
	}
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** metodo encargado de contar los archivos por extensiones, procesarlo a través de una plantilla XSLT y generar el reporte en HTML
	** 18 abril 2020
	*/
	public function getCount(){
		try{
			$this->load->model('Analitica_model');
			$datos = $this->Analitica_model->getCount();

			$config = array (
					'root'          => 'root',
					'element'       => 'archivo',
					'newline'       => "\n",
					'tab'           => "\t"
			);
			$this->load->dbutil();
			header('Content-Type: application/xml; charset=utf-8');
			$xml = $this->dbutil->xml_from_result($datos, $config);
			
			write_file("public/xslt/getCount.xml", $xml);
			
			$xml = new DOMDocument;
			$xml->load("public/xslt/getCount.xml");

			$xsl = new DOMDocument;
			$xsl->load("public/xslt/getCount.xsl");

			$proc = new XSLTProcessor;
			$proc->importStyleSheet($xsl);

			$table = $proc->transformToXML($xml);
			
			echo json_encode(array('status' => 'ok', 'table' => $table));
		}catch(Exception $e) {
			echo json_encode(array('status' => 'error', 'resultado' => $e->getMessage()));
		}		
	}
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** metodo encargado de truncar las tablas de archivos e info de los archivos
	** 18 abril 2020
	*/
	public function deleteDB(){
		try{
			$this->load->model('Analitica_model');
			$datos = $this->Analitica_model->deleteDB();			
			echo json_encode(array('status' => 'ok', 'resultado' => "Base de datos restaurada.!"));
		}catch(Exception $e) {
			echo json_encode(array('status' => 'error', 'resultado' => $e->getMessage()));
		}		
	}
	
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** metodo encargado de borrar caracteres especiales que dificultan ejecutar los INSERT
	** 18 abril 2020
	*/
	function trim_all( $str , $what = NULL , $with = '' ){
		if( $what === NULL )
		{
			//  Character      Decimal      Use
			//  "\0"            0           Null Character
			//  "\t"            9           Tab
			//  "\n"           10           New line
			//  "\x0B"         11           Vertical Tab
			//  "\r"           13           New Line in Mac
			//  " "            32           Space
		   
			$what   = "\t\n\x0B\r";    //all white-spaces and control chars
		}
	   
		return trim( preg_replace( "/[".$what."]+/" , $with , $str ) , $what );
	}
} 
/* End of file analitica.php */