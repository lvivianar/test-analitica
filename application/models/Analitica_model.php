<?php
/**
 * Modelo 
 */
class Analitica_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** inserta informacion en la base de datos
	** parametros a recibir: consulta SQL (String)
	** retorna: numero de filas afectadas (Int)
	** 18 abril 2020
	*/
	public function insertar($sql = ""){
		if($sql != ""){
			$this->db->query($sql);
			return $this->db->affected_rows();
		}else{
			return 0;
		}
    }
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** consulta todos los archivos
	** retorna: coleccion de datos (Array)
	** 18 abril 2020
	*/
	public function getAll(){
        $data = $this->db->select('*')
		->from('archivos')
		->get();

        return $data;
    }
	
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** consulta y cuenta los archivos agrupados por extension
	** retorna: coleccion de datos (Array)
	** 18 abril 2020
	*/
	public function getCount(){
        $data = $this->db->select('COUNT(*) as cantidad, IFNULL(a.extension, "Ninguna") as extension, IFNULL(b.tipo_archivo, "Ninguno") as tipo_archivo')
		->from('archivos_info a')
		->join('archivos_extensiones b', 'a.extension = b.extension', 'LEFT')
		->group_by('a.extension')
		->get();

        return $data;
    }
	
	
	/*
	** auth@r Lady Viviana Ramirez <lviviana.0309@gmail.com>
	** trunca las tablas de archivos e informacion de los archivos
	** retorna: numero de filas afectadas (Int)
	** 18 abril 2020
	*/
	public function deleteDB(){

		$this->db->query("TRUNCATE TABLE archivos;");
		$this->db->query("TRUNCATE TABLE archivos_info;");
		return $this->db->affected_rows();	
    }
}
?>