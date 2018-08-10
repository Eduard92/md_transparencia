<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Archivos_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'fraccion_obligaciones_archivos';
		
	}

		public function create($input,$pdf,$excel)
	{

		$data = array(
			'anexo_pdf' => $pdf?$pdf:null,
			'anexo_excel' => $excel?$excel:null,
            'id_fraccion' => $input['id_fraccion'],
            'id_obligacion' =>$input['id_obligacion'],
            'anio' =>$input['anio'],
			'created' =>  date('Y-m-d'));


        return $this->insert($data);
    }

 
 }
 ?>