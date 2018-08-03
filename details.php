<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Groups module
 *
 * @author PyroCMS Dev Team
 * @package PyroCMS\Core\Modules\Groups
 */
 class Module_Transparencia extends Module
{

	public $version = '1.0';

	public function info()
	{
		$info= array(
			'name' => array(
				'en' => 'NA',
				
				'es' => 'Transparencia',
				
			),
			'description' => array(
				'en' => 'You can upload your favorite videos from YOUTUBE',
				
				'es' => 'Administra el catálogo de fracciones para Transparencia',
				
			),
			'frontend' => false,
			'backend' => true,
			'menu' => 'content',
            'roles' => array(
				'create', 'edit','delete','admin_fracciones','admin_obligaciones'
			),
			'sections'=>array(
                'transparencia'=>array(
                    'name'=>'transparencia:title',                    
                    'uri' => 'admin/transparencia',
        			'shortcuts' => array(
        				/*array(
        					'name' => 'transparencia:add',
        					'uri' => 'admin/transparencia/create',
        					'class' => 'btn btn-success',
                           
        				),*/
                       
        			)
                ),
                /*'obligaciones'=>array(
                    'name'=>'obligaciones:title',                    
                    'uri' => 'admin/transparencia/obligaciones',
        			'shortcuts' => array(
        				array(
        					'name'       => 'obligaciones:create',
        					'uri'        => 'admin/transparencia/obligaciones/create',
        					'class'      => 'btn btn-success',
                            'open-modal' => '',
                            'modal-title' => 'Seleccionar fracción'
                           
        				),
                       
        			)
                ),
                'fracciones'=>array(
                    'name'=>'fracciones:title',                    
                    'uri' => 'admin/transparencia/fracciones',
        			'shortcuts' => array(
        				array(
        					'name' => 'fracciones:create',
        					'uri' => 'admin/transparencia/fracciones/create',
        					'class' => 'btn btn-success',
                           
        				),
                       
        			)
                ),*/
           )
		);
        
        if (function_exists('group_has_role'))
		{
		    if(group_has_role('transparencia', 'admin_obligaciones'))
			{
			    
				$info['sections']['obligaciones'] = array(
				    'name'=>'obligaciones:title',                    
                    'uri' => 'admin/transparencia/obligaciones',
        			'shortcuts' => array(
        				array(
        					'name'       => 'obligaciones:create',
        					'uri'        => 'admin/transparencia/obligaciones/create',
        					'class'      => 'btn btn-success',
                            'open-modal' => '',
                            'modal-title' => 'Seleccionar fracción'
                           
        				),
                       
        			)
				);
			}
			if(group_has_role('transparencia', 'admin_fracciones'))
			{
			    
				$info['sections']['fracciones'] = array(
				    'name'=>'fracciones:title',                    
                    'uri' => 'admin/transparencia/fracciones',
        			'shortcuts' => array(
        				array(
        					'name' => 'fracciones:create',
        					'uri' => 'admin/transparencia/fracciones/create',
        					'class' => 'btn btn-success',
                           
        				),
                        array(
        					'name' => 'fracciones:ordering',
        					'uri' => 'admin/transparencia/fracciones?order=1',
        					'class' => 'btn btn-primary',
                           
        				),
                       
        			)
				);
			}
            
		}
        
        return $info;
	}

	public function install()
	{
		$this->dbforge->drop_table('fracciones');

		$tables = array(
			'fracciones' => array(
				'id' => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
				'nombre' => array('type' => 'VARCHAR', 'constraint' => 254,),
                'numeral' => array('type' => 'VARCHAR', 'constraint' => 254,),
                'periodicidad' => array('type' => 'VARCHAR', 'constraint' => 254,),
				'descripcion' => array('type' => 'TEXT', 'null' => true,),
				'motivo' => array('type' => 'TEXT', 'null' => true,),
				'aplicable' => array('type' => 'INT', 'constraint' => 11, 'null' => true,'default'=>'0'),
			),
          
            'fraccion_obligaciones'  => array(
				'id'          => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
   	            'id_fraccion' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
                'nombre'      => array('type' => 'VARCHAR', 'constraint' => 254,),
                //'id_fraccion_campo' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
				//'valor'             => array('type' => 'VARCHAR', 'constraint' => 254,),
                'campos'      => array('type' => 'TEXT', 'null' => true),
                'helper'      => array('type' => 'TEXT', 'null' => true),
                'created_on'  => array('type' => 'INT', 'constraint' => 11, 'null' => true),
                
               
				
				
			),
             'fraccion_obligaciones_desglose'  => array(
				'id'          => array('type' => 'INT', 'constraint' => 11, 'auto_increment' => true, 'primary' => true,),
   	            'id_fraccion' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
                'id_fraccion_obligacion' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
                'campos'             => array('type' => 'TEXT', 'null' => true),
				
                'created_on'        => array('type' => 'INT', 'constraint' => 11, 'null' => true),
                
               
				
				
			),
		);

		if ( ! $this->install_tables($tables))
		{
			return false;
		}

		

		return true;
	}

	public function uninstall()
	{
		// This is a core module, lets keep it around.
		return false;
	}

	public function upgrade($old_version)
	{
		return true;
	}

}
?>