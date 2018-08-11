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
            'desglose_m',
            'archivos_m',
            'fraccion_m',
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

        $fr = $this->input->get('fr');
        $base_where = array();
        if($fr)
        {
            $base_where['id_fraccion'] = $fr;
        }
                           
                           
        if($this->current_user->group == 'transparencia')
        {
           $ids = Centro::GetPermissions('transparencia') OR show_error('No se tiene asignado fracciones a tu cuenta de usuario');
            
           // $this->obligacion_m->where('user_id',$this->current_user->id);
           
            
        }
         $this->obligacion_m->select('*,fracciones.nombre AS nombre_fraccion,fraccion_obligaciones.id AS id,fraccion_obligaciones.nombre AS nombre_obligacion')
                           ->join('fracciones','fracciones.id=fraccion_obligaciones.id_fraccion')
                           ->where($base_where);
                           
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
                ->set('fr',$this->fraccion_m->dropdown('id','nombre'))
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

         $files = array();

         $obligacion = $this->db->select('*, fraccion_obligaciones_archivos.id as id_archivo')
                           ->join('fraccion_obligaciones`','fraccion_obligaciones_archivos.id_obligacion=fraccion_obligaciones.id')
                           ->where(array('default_fraccion_obligaciones_archivos.id_fraccion' => $id_fraccion,
                                        'default_fraccion_obligaciones_archivos.id_obligacion' => $id_obligacion))
                           ->order_by('fraccion_obligaciones_archivos.anio','DESC')
                           ->get('default_fraccion_obligaciones_archivos')->result();

        if($obligacion)
        {
            foreach($obligacion as $docs)
            {
                $files[] = array(
                    'pdf'    => $docs->anexo_pdf,
                    'excel'  => $docs->anexo_excel,
                    'anio'   => $docs->anio,
                    'date'   => date('d/m/Y',$docs->created),
                    'id'    => $docs->id_archivo,
                );
            }
            
        }
        else
        {
            $obligacion = $this->obligacion_m->get_many_by(array('id_fraccion' => $id_fraccion,
                                        'id' => $id_obligacion));
        }

        $this->template->title($this->module_details['name'])
                ->set('obligacion',$obligacion)
                ->set('')        
                ->append_metadata('<script type="text/javascript"> var files = '. json_encode($files) .',id_fraccion='.$id_fraccion.', id_obligacion='.$id_obligacion.';</script>')
                ->append_js('module::transparencia.controller.js')
                ->enable_parser(false)
                ->build('admin/upload');
    }

    public function consult_doc()
    {
         $result = array(
         
            'status' => false,
            'anexo_pdf'=>false,
            'anexo_excel'=>false, );        

            $base_where = array('anio' => $this->input->post('anio') ,
                                'id_obligacion' => $this->input->post('id_obligacion'),
                                'id_fraccion' => $this->input->post('id_fraccion'));

            $doc = $this->archivos_m->get_many_by($base_where); 

            if($doc){
                if(empty($doc['0']->anexo_excel)  == false)
                {
                    $result['anexo_excel'] = true;
                    
                }
                if(empty($doc['0']->anexo_pdf) == false)
                {
                    $result['anexo_pdf'] = true;
                    
                }  
                $result['status'] = true;
                $result['id'] = $doc['0']->id?$doc['0']->id:null;
            }        
           
           return $this->template->build_json($result);

    }

    function upload_file()
    {
        $result = array(
        
            'status'  => true,
            'message' => '',
            'data'    => false
        );

            $input = $this->input->post();

            $id = $this->input->post('id');

            $folder = $this->file_folders_m->get_by_path('juridico/transparencia');
            
            if($folder)
            {
                $result = Files::upload($folder->id,$input['name'],'file',false,false,false,'pdf|xls|xlsx');

                
                if($result['status'] && $id)
                {

                    if($result['data']['extension']=='.pdf')
                    {
                        $data['pdf'] = $result['data']['id'];

                        $update = array('anexo_pdf' => $data['pdf'],
                                        'created' =>  strtotime(date("Y-m-d")));

                        $this->archivos_m->update($id,$update);
                    }
                    else
                    {
                        $data['excel'] = $result['data']['id'];

                        $update = array('anexo_excel' => $data['excel'],
                                        'created' =>  strtotime(date("Y-m-d")));

                        $this->archivos_m->update($id,$update);
                    }
                  
                    
                }
                elseif($result['status'])
                {
                    if($result['data']['extension']=='.pdf')
                    {
                        $data['pdf'] = $result['data']['id'];

                        $result['id'] = $this->archivos_m->create($input,$data['pdf']);

                    }
                    else
                    {
                        $data['excel'] = $result['data']['id'];

                        $result['id'] = $this->archivos_m->create($input,null,$data['excel']);
                        
                    }
                }
                
                
            }
            else
            {
                $result['message'] = lang('files:no_folders_wysiwyg');
                $result['status']  = false;
            }

            $obligacion = $this->db->select('*, fraccion_obligaciones_archivos.id as id_archivo')
                           ->where(array('default_fraccion_obligaciones_archivos.id_fraccion' =>$input['id_fraccion'],
                                        'default_fraccion_obligaciones_archivos.id_obligacion' => $input['id_obligacion']))
                           ->order_by('fraccion_obligaciones_archivos.anio','DESC')
                           ->get('default_fraccion_obligaciones_archivos')->result();
            if($obligacion)
            {
                foreach($obligacion as $docs)
                {
                    $files[] = array(
                        'pdf'    => $docs->anexo_pdf,
                        'excel'   => $docs->anexo_excel,
                        'anio'   => $docs->anio,
                        'date'   =>  date('d/m/Y',$docs->created),
                        'id'    => $docs->id_archivo,
                    );
                }
                
            }

            $result['files'] = $files;
        
        return $this->template->build_json($result);
    }



    public function delete_file()
    {
        $result = array(
        
            'status'  => false,
            'message' => '',
            'data'    => false
        );

         $id = $this->input->post('id');
         $id_fraccion = $this->input->post('id_fraccion');
         $id_obligacion = $this->input->post('id_obligacion');


        if($this->archivos_m->delete($id))
        {
            $result['message']  = 'Registro Eliminado Correctamente ' ;
            $result['status']   = true;

        }
        else
        {
            $result['message'] = 'Error al eliminar Registro ' ;
        }

        
            $this->template->build_json($result);

    }

        public function update()
    {
        $result = array(
        
            'status'  => false,
            'message' => '',
        );

         $id = $this->input->post('id');
         $tipo = $this->input->post('tipo');
         $id_fraccion = $this->input->post('id_fraccion');
         $id_obligacion = $this->input->post('id_obligacion');


         if($tipo =='.pdf')
        {
            if($this->archivos_m->update($id,array('anexo_pdf' => null)))
            {
                $result['message']  = 'Archivo Eliminados Correctamente ' ;
                $result['status']   = true;
            }
            else
            {
              $result['message'] = 'Error al eliminar Archivos ' ;     
            }
        }
        else
        {
            if($this->archivos_m->update($id,array('anexo_excel' => null)))
            {
                $result['message']  = 'Archivo Eliminados Correctamente ' ;
                $result['status']   = true;
            }
            else
            {
                $result['message'] = 'Error al eliminar Archivos ' ;       
            }
        }

        $obligacion = $this->db->select('*, fraccion_obligaciones_archivos.id as id_archivo')
                           ->where(array('default_fraccion_obligaciones_archivos.id_fraccion' =>$id_fraccion,
                                        'default_fraccion_obligaciones_archivos.id_obligacion' => $id_obligacion))
                           ->order_by('fraccion_obligaciones_archivos.anio','DESC')
                           ->get('default_fraccion_obligaciones_archivos')->result();
            if($obligacion)
            {
                foreach($obligacion as $docs)
                {
                    $files[] = array(
                        'pdf'    => $docs->anexo_pdf,
                        'excel'   => $docs->anexo_excel,
                        'anio'   => $docs->anio,
                        'date'   =>  date('d/m/Y',$docs->created),
                        'id'    => $docs->id_archivo,
                    );
                }
                
            }

            $result['files'] = $files;
        
        return $this->template->build_json($result);
        
            $this->template->build_json($result);

    }

  }



 ?> 