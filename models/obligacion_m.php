<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Obligacion_m extends MY_Model {

	private $folder;

	public function __construct()
	{
		parent::__construct();
		$this->_table = 'fraccion_obligaciones';
		
	}
 }
 ?>