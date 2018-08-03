<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends Admin_Controller
{
	protected $section = 'transparencia';

	public function __construct()
	{
		parent::__construct();
        $this->load->model(array(
            'obligacion_m',
            'files/file_folders_m',
            'desglose_m'
        ));
        $this->load->library(array(
                'files/files',
                'transparencia',
                'centros/centro'
        
                
        ));
        
        $this->load->helper('descargas/descargas');
        $this->lang->load('transparencia');
        
        $this->template
            ->periodos = array(
        
            '1'=>'Mensual',
            '2'=>'Bimestral',
            '3'=>'Trimestral',
            '6'=>'Semestral',
            '12'=>'Anual',
        );
        
        
        
    }
    function edit($id_obligacion=0,$id=0)
    {
        
        $desglose = $this->desglose_m->get($id);
        
        if($this->current_user->group == 'transparencia')
        {
            if($desglose->user_id != $this->current_user->id )
            {
                redirect('admin');
            }
        }
        
        $desglose->campos = json_decode($desglose->campos);
        
        $obligacion = $this->obligacion_m->get_by(array(
                            
                            'id'          => $id_obligacion
                      )) OR show_404();
                      
        $obligacion->campos = json_decode($obligacion->campos);
        
        
        
        $campos             = array();
        
        
        if($_POST)
        {
            $data = array(
                'updated_on'  => now(),
                
                'campos'                 => ''
            );
            $folder = $this->file_folders_m->get_by_path('juridico/transparencia') OR show_error('La carpeta juridico/transparencia no existe');
            foreach($obligacion->campos as $campo)
            {
                //if(!$_FILES['xml_file']['name'])
               // {
                if($campo->tipo == 'upload' && $_FILES[$campo->slug]['name'])
                {
                    $file = Files::upload($folder->id,false,$campo->slug);
                    
                    if($file['status'])
                    {
                        $campos[$campo->slug] = $file['data']['id'];
                    }
                    else
                    {
                        $this->session->set_flashdata('error',$file['message']);
                    }
                }
                else
                {
                    $campos[$campo->slug] = $this->input->post($campo->slug);
                }
            }
            $data['campos'] = json_encode($campos);
            
            if($this->desglose_m->update($id,$data))
            {
				
				$this->session->set_flashdata('success',lang('global:save_success'));
				
			}
            else
            {
				$this->session->set_flashdata('error',lang('global:save_error'));
				
			}
            redirect('admin/transparencia');
        }          
        $this->template->title($this->module_details['name'])
                ->set('obligacion',$obligacion)
                ->set('desglose',$desglose)
                ->build('admin/form');
    }
    function create($id_fraccion=0,$id_obligacion=0)
    {
        $obligacion = $this->obligacion_m->get_by(array(
                            'id_fraccion' => $id_fraccion,
                            'id'          => $id_obligacion
                      )) OR show_404();
                      
        $obligacion->campos = json_decode($obligacion->campos);
        $campos             = array();
        
        if($this->current_user->group == 'transparencia')
        {
            if(!in_array($id_fraccion,Centro::GetPermissions('transparencia')))
            {
                redirect('admin');
            } 
            
            
            
        }
        
        
        if($_POST)
        {
            $data = array(
                'created_on'  => now(),
                'id_fraccion' => $id_fraccion,
                'id_fraccion_obligacion' => $id_obligacion,
                'user_id'                => $this->current_user->id,
                'campos'                 => ''
            );
            $folder = $this->file_folders_m->get_by_path('juridico/transparencia') OR show_error('La carpeta juridico/transparencia no existe');
            foreach($obligacion->campos as $campo)
            {
                if($campo->tipo == 'upload')
                {
                    $file = Files::upload($folder->id,false,$campo->slug);
                    
                    if($file['status'])
                    {
                        $campos[$campo->slug] = $file['data']['id'];
                    }
                    else
                    {
                        $this->session->set_flashdata('error',$file['message']);
                    }
                }
                else
                {
                    $campos[$campo->slug] = $this->input->post($campo->slug);
                }
            }
            $data['campos'] = json_encode($campos);
            
            if($this->desglose_m->insert($data))
            {
				
				$this->session->set_flashdata('success',lang('global:save_success'));
				
			}
            else
            {
				$this->session->set_flashdata('error',lang('global:save_error'));
				
			}
            redirect('admin/transparencia');
        }
        
        $this->template->title($this->module_details['name'])
                ->set('obligacion',$obligacion)
                ->build('admin/form');
    }
    function index()
    {
         $fracciones = array();
        
                           
                           
        if($this->current_user->group == 'transparencia')
        {
           $ids = Centro::GetPermissions('transparencia') OR show_error('No se tiene asignado fracciones a tu cuenta de usuario');
            
           // $this->obligacion_m->where('user_id',$this->current_user->id);
           
            
        }
         $this->obligacion_m->select('*,fracciones.nombre AS nombre_fraccion,fraccion_obligaciones.id AS id,fraccion_obligaciones.nombre AS nombre_obligacion')
                           ->join('fracciones','fracciones.id=fraccion_obligaciones.id_fraccion');
                           
         if(empty($ids)== false)
         {
            $this->obligacion_m->where_in('id_fraccion',$ids);
         }
         
         $obligaciones = $this->obligacion_m->order_by('fracciones.ordering')
                        ->get_all();
                        
         foreach($obligaciones as $obligacion)
         {
             $obligacion->campos = json_decode($obligacion->campos);
             if(!isset($fracciones[$obligacion->id_fraccion]))
             {
                $fracciones[$obligacion->id_fraccion] = array(
                
                    'fraccion'     => $obligacion->nombre_fraccion,
                    'numeral'      => $obligacion->numeral,
                    'descripcion'   => $obligacion->descripcion,
                    'obligaciones' => array()
                );
             }
             
             $fracciones[$obligacion->id_fraccion]['obligaciones'][] = $obligacion;
         }
         $this->template->title($this->module_details['name'])
                ->set('fracciones',$fracciones)
                ->enable_parser(true)
                ->append_js('module::transparencia.controller.js')
                ->build('admin/index');
    }
    
    function delete($id=0)
    {
   	    $ids = ($id) ?array(0=>$id) : $this->input->post('action_to');

		// Go through the array of ids to delete
		$deletes= array();
        
        foreach($ids as $id)
        {
            if($desglose = $this->desglose_m->get($id))
            {
                
                //$data = Transparencia::GetValues($desglose->id_fraccion,$desglose->id_obligacion);
                //print_r($desglose);
                
                $template = $this->obligacion_m->get($desglose->id_fraccion_obligacion);
                $valores  = json_decode($desglose->campos);
                foreach(json_decode($template->campos) as $field)
                {
                    
                    if($field->tipo == 'upload')
                    {
                        Files::delete_file($valores->{$field->slug});
                    }
                }
                $this->desglose_m->delete($id);
                
                //$deletes[] = $equipo->no_serie;
            }
        }
        
        if(!empty($equipos_delete))
        {
            
        }
        
        redirect('admin/transparencia');
    }
    function upload($id_fraccion,$id_obligacion)
    {
        $this->load->library('files/files');
        $this->load->model('files/file_folders_m');
        
        $obligacion = $this->obligacion_m->get_by(array(
            'id'          => $id_obligacion,
            'id_fraccion' => $id_fraccion
        ));
        
        if($_POST)
        {
             $folder = $this->file_folders_m->get_by_path('juridico/transparencia') OR show_error('La carpeta juridico/transparencia no existe');
            
             $pdf   = Files::upload($folder->id,false,'anexo_pdf',false,false,false,'pdf');
             $excel = Files::upload($folder->id,false,'anexo_excel',false,false,false,'xls|xlsx');
             
             
             $data = array(
                'anexo_pdf'   => $this->input->post('anexo_pdf'),
                'anexo_excel' => $this->input->post('anexo_excel'),
             );   
             if($pdf['status'])
             {
                        $data['anexo_pdf'] = $pdf['data']['id'];
             }
            
             if($excel['status'])
             {
                        $data['anexo_excel'] = $excel['data']['id'];
             }
             
            if(!empty($data)&&$this->obligacion_m->update($obligacion->id,$data))
            {
			     
				$this->session->set_flashdata('success',lang('global:save_success'));
				
			}
            
            
            redirect('admin/transparencia');
        }
        $this->template->title($this->module_details['name'])
                ->set('obligacion',$obligacion)
                ->enable_parser(true)
                ->build('admin/upload');
    }
  }
 ?> 