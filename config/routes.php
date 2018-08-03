<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');
//$route['registros/admin/configuracion(/:any)?']			= 'admin_configuracion$1';
$route['transparencia/admin/fracciones(/:any)?']			= 'admin_fracciones$1';
$route['transparencia/admin/obligaciones(/:any)?']			= 'admin_obligaciones$1';
$route['transparencia/admin(:any)?']	= 'admin$1';
$route['transparencia(:any)?']			= 'transparencia_front$1';



?>