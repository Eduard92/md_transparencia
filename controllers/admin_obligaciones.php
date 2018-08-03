<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Admin Blog Fields
 *
 * Manage custom blogs fields for
 * your blog.
 *
 * @author 		PyroCMS Dev Team
 * @package 	PyroCMS\Core\Modules\Users\Controllers
 */
class Admin_obligaciones extends Admin_Controller {

	protected $section = 'obligaciones';

	// --------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
        //role_or_die('transparencia','admin_obligaciones');
        $this->load->model(array(
            'fraccion_m',
            'obligacion_m'
        ));
        $this->lang->load('transparencia');
        $this->validation_rules = array(
 		             array(
						'field' => 'nombre',
						'label' => 'Nombre',
						'rules' => 'trim|required'
						),
                     array(
						'field' => 'helper',
						'label' => 'Ayuda',
						'rules' => 'trim'
						),
                    array(
						'field' => 'campos',
						'label' => 'Campos',
						'rules' => ''
						),
        );
    }
    
    function index()
    {
        $obligaciones = $this->obligacion_m->select('*,fracciones.nombre AS nombre_fraccion,fraccion_obligaciones.id AS id')
                                ->join('fracciones','fracciones.id=fraccion_obligaciones.id_fraccion')
                                ->order_by('ordering')
                                ->get_all();
        
        $this->template->title($this->module_details['name'])
                ->set('obligaciones',$obligaciones)
                ->build('admin/obligaciones/index');
    }
    function edit($id=0)
    {
        $obligacion = $this->obligacion_m->get($id) OR show_404();
        
        
        $this->form_validation->set_rules($this->validation_rules);
		if($this->form_validation->run()){
            unset($_POST['btnAction']);
            //$_POST['id_fraccion'] = $id_fraccion;
            $data = array(
            
                
                'campos'      => json_encode($this->input->post('campos')),
                'nombre'      => $this->input->post('nombre'),
                'helper'      => $this->input->post('helper'),
                
            );
            if($this->obligacion_m->update($id,$data))
            {
				
				$this->session->set_flashdata('success',lang('global:save_success'));
                redirect('admin/transparencia/obligaciones/edit/'.$id);
				
			}else{
				$this->session->set_flashdata('error',lang('global:save_error'));
                redirect('admin/transparencia/obligaciones');
				
			}
			
        }
        elseif($_POST)
        {
            $obligacion = (Object)$_POST;
        }
        $this->template->set('obligacion',$obligacion)
                    ->append_metadata('<script type="text/javascript">var campos ='.$obligacion->campos.' ; </script>')
                    ->append_js('module::transparencia.controller.js')
                    ->build('admin/obligaciones/form'); 
    }
    function create($id_fraccion='')
    {
        $obligacion = new StdClass();
        $this->form_validation->set_rules($this->validation_rules);
		if($this->form_validation->run()){
            unset($_POST['btnAction']);
            //$_POST['id_fraccion'] = $id_fraccion;
            $data = array(
            
                'id_fraccion' => $id_fraccion,
                'campos'      => json_encode($this->input->post('campos')),
                'nombre'      => $this->input->post('nombre'),
                'helper'      => $this->input->post('helper'),
                'created_on'  => now()
            );
            if($id= $this->obligacion_m->insert($data))
            {
				
				$this->session->set_flashdata('success',lang('global:save_success'));
                redirect('admin/transparencia/obligaciones/edit/'.$id);
				
			}else{
				$this->session->set_flashdata('error',lang('global:save_error'));
                redirect('admin/transparencia/obligaciones/create/'.$id_fraccion);
				
			}
			
        }
        
        foreach ($this->validation_rules as $rule)
		{
			$obligacion->{$rule['field']} = $this->input->post($rule['field']);
		}
         $this->input->is_ajax_request()
         ? $this->template->set_layout(false)
                        ->set('fracciones',$this->fraccion_m->get_all())
                        ->build('admin/obligaciones/list_fracciones')
         :$this->template->title($this->module_details['name'])
                        ->set('obligacion',$obligacion)
                        ->append_metadata('<script type="text/javascript">var campos =[] ; </script>')
                        ->append_js('module::transparencia.controller.js')
                        ->build('admin/obligaciones/form');
    }
    
  }
?>