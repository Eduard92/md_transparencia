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
class Admin_fracciones extends Admin_Controller {

	protected $section = 'fracciones';

	// --------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();
        //role_or_die('transparencia','admin_fracciones');
        
        $this->load->model(array(
            'fraccion_m',
            'users/user_m'
        ));
        $this->load->library(array(
            'centros/centro',
            'files/files'    
        ));
        $this->lang->load('transparencia');
        $this->validation_rules = array(
		      array(
				'field' => 'tipo',
				'label' => 'Tipo',
				'rules' => 'trim|required'
				),
			array(
				'field' => 'nombre',
				'label' => 'Nombre',
				'rules' => 'trim|required'
				),
            array(
				'field' => 'numeral',
				'label' => 'Numeral',
				'rules' => 'trim|required'
				),
            array(
				'field' => 'periodicidad',
				'label' => 'Periodicidad',
				'rules' => 'trim|required'
				),
            array(
				'field' => 'descripcion',
				'label' => 'Descripción',
				'rules' => 'trim'
				),
            array(
				'field' => 'aplicable',
				'label' => 'Aplicable',
				'rules' => 'integer'
				),
            array(
				'field' => 'users',
				'label' => 'Usuarios',
				'rules' => ''
				),
            'motivo'=> array(
				'field' => 'motivo',
				'label' => 'Motivo',
				'rules' => 'trim'
				),
        );
        
        $this->template
            ->periodos = array(
        
            '1'=>'Mensual',
            '2'=>'Bimestral',
            '3'=>'Trimestral',
            '6'=>'Semestral',
            '12'=>'Anual',
        );
    }
    function index()
    {
        $fracciones = $this->fraccion_m->order_by('ordering')
                                ->get_all();
                                
        foreach($fracciones as &$fraccion)
        {
            $fraccion->periodicidad = $this->template->periodos[$fraccion->periodicidad];
        }
        $this->template->title($this->module_details['name'])
                //->set('fracciones',$fracciones)
                ->append_metadata('<script type="text/javascript">var fracciones='.json_encode($fracciones).';</script>')
                ->append_js('module::transparencia.controller.js')
                ->build('admin/fracciones/index');
    }
    function edit($id=0)
    {
        $fraccion = $this->fraccion_m->get($id) OR show_404();
        
        $fraccion->users = Centro::GetList($id,'transparencia');
        
        
        if($this->input->post('aplicable') == 0 )
        {
            $this->validation_rules['motivo']['rules'].='|required';
        }
        $this->form_validation->set_rules($this->validation_rules);
		
				
		if($this->form_validation->run())
		{
			unset($_POST['btnAction']);
            $data = array(
                'nombre'       => $this->input->post('nombre'),
                'numeral'      => $this->input->post('numeral'),
                'periodicidad' => $this->input->post('periodicidad'),
                'descripcion'  => $this->input->post('descripcion'),
                'aplicable'    => $this->input->post('aplicable'),
                'motivo'       => $this->input->post('motivo'),
                'anexo'        => $this->input->post('anexo'),
                'tipo'         => $this->input->post('tipo'),
            );
            $folder = $this->file_folders_m->get_by_path('juridico/transparencia') OR show_error('La carpeta juridico/transparencia no existe');
            
             //$file = Files::upload($folder->id,false,'anexo');
                    
            /*if($file['status'])
             {
                        $data['anexo'] = $file['data']['id'];
             }*/
            if($this->fraccion_m->update($id,$data))
            {
			     Centro::AddUsers($id,'transparencia',$this->input->post('users'));
				$this->session->set_flashdata('success',lang('global:save_success'));
				
			}
            else
            {
				$this->session->set_flashdata('error',lang('global:save_error'));
				
			}
            
            redirect('admin/transparencia/fracciones/edit/'.$id);
        }
        elseif($_POST)
        {
            $fraccion = (Object)$_POST;
        }
        
        $users = $this->db->select('*,users.id AS id')
                            ->where_in('groups.name',array('transparencia'))
                            ->join('groups','groups.id=users.group_id')
                            ->join('profiles','profiles.user_id=users.id')
                            ->get('users')
                            ->result();
        $this->template->title($this->module_details['name'])
                ->set('fraccion',$fraccion)
                ->set('users',$users)
                ->build('admin/fracciones/form');
    }
    function create()
    {
        $fraccion = new StdClass();
        if($this->input->post('aplicable') == 0 )
        {
            $this->validation_rules['motivo']['rules'].='|required';
        }
        $this->form_validation->set_rules($this->validation_rules);
		
				
		if($this->form_validation->run())
		{
			unset($_POST['btnAction']);
            $data = array(
                'nombre'       => $this->input->post('nombre'),
                'numeral'      => $this->input->post('numeral'),
                'periodicidad' => $this->input->post('periodicidad'),
                'descripcion'  => $this->input->post('descripcion'),
                'aplicable'    => $this->input->post('aplicable'),
                'motivo'       => $this->input->post('motivo'),
                //'anexo'        => '',
                'tipo'        => $this->input->post('tipo'),
            );
            
             //$folder = $this->file_folders_m->get_by_path('juridico/transparencia') OR show_error('La carpeta juridico/transparencia no existe');
            
             //$file = Files::upload($folder->id,false,'anexo');
                    
            /* if($file['status'])
             {
                        $data['anexo'] = $file['data']['id'];
             }*/
            if($id = $this->fraccion_m->insert($data))
            {
				Centro::AddUsers($id,'transparencia',$this->input->post('users'));
				$this->session->set_flashdata('success',lang('global:save_success'));
				
			}
            else
            {
				$this->session->set_flashdata('error',lang('global:save_error'));
				
			}
            
            redirect('admin/transparencia/fracciones');
        }
        foreach ($this->validation_rules as $rule)
		{
			$fraccion->{$rule['field']} = $this->input->post($rule['field']);
		}
        
        $users = $this->db->select('*,users.id AS id')
                            ->where_in('groups.name',array('transparencia'))
                            ->join('groups','groups.id=users.group_id')
                            ->join('profiles','profiles.user_id=users.id')
                            ->get('users')
                            ->result();
        $this->template->title($this->module_details['name'])
                ->set('fraccion',$fraccion)
                ->set('users',$users)
                ->build('admin/fracciones/form');
    }
    
    function order()
    {
        
        $order       = $this->input->post('order');
        $result=array(
            'status'  => true,
            'message' => ''
        );
        if(is_array($order))
        {
            foreach($order as $i=>$item)
            {
                
                $this->db->where(array(
                    'id'=>$item,
                    
                    
                ))->set(array('ordering'=>$i))
                ->update('fracciones');
            }
            $result['message'] = lang('fracciones:order_success');
        }
        else
        {
            $result['status'] = false;
            $result['message'] = lang('fracciones:order_error');
        }
        
        return $this->template->build_json($result);
    }
 }
 ?>