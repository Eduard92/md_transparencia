<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * PyroCMS File library. 
 *
 * This handles all file manipulation 
 * both locally and in the cloud
 * 
 * @author		Jerel Unruh - PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Files\Libraries
 */
class Transparencia
{
    public function __construct()
	{
	   ci()->load->model('desglose_m');
    }
    public static function GetValues($id_fraccion,$id_obligacion,$forced=false)
    {
        $base_where = array(
                    
                        'id_fraccion' => $id_fraccion,
                        'id_fraccion_obligacion' => $id_obligacion
                    );
                    
        if(!$forced && ci()->current_user->group == 'transparencia')
        {
            $base_where['user_id'] = ci()->current_user->id;
        }
        $result = ci()->desglose_m->get_many_by($base_where);
                    
        
        //print_r($result); 
        //exit();         
        return $result;
    }
 }
 ?>