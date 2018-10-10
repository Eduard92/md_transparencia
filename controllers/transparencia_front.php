<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Frontend controller for files and stuffs
 * 
 * @author		PyroCMS Dev Team
 * @package		PyroCMS\Core\Modules\Files\Controllers
 */
class Transparencia_front extends Public_Controller
{
	private	$_path = '';
	
	public function __construct()
	{
		parent::__construct();
        $this->load->model(array(
        
            'fraccion_m',
            'obligacion_m',
            'centros/centro_m'

        ));
        
        $this->load->library('files/files');
        $this->load->library(array(
            'transparencia',
            'centros/centro'
        ));
        $this->template->set_breadcrumb('Transparencia','transparencia');
    }
    function cron()
    {
        $fracciones = $this->fraccion_m->get_all();
        
        $dias       = array('7','4','1');
        $send_to = array('bernardo.cauich.reyna@gmail.com');
        
        foreach($fracciones as $fraccion)
        {
            $residuo = date('m')%$fraccion->periodicidad;
            
            if($residuo == 0)
            {
                $mes_activo  = date('m')+1;
                $anio_activo = date('Y');
                $dia_time        = 60*60*24;
                
                if($mes_activo > 12)
                {
                    $mes_activo = 1;
                    
                    $anio_activo = $anio_activo+1;
                }
                
                foreach($dias as $dia)
                {
                    $tiempo     = (strtotime($anio_activo.'-'.$mes_activo.'-01'))-($dia_time*$dia);   
                    
                    if(date('Y-m-d',$tiempo) == date('Y-m-d'))
                    {
                        $send_to = Centro::GetList($fraccion->id,'transparencia','email');
                        
                        
                        if(count($send_to)>0){
                            $data['fraccion']           = $fraccion;
                            $data['dias']                = $dia;
                            //$data['subject']			= Settings::get('site_name') . ' - Aviso Módulo Directores'; // No translation needed as this is merely a fallback to Email Template subject
                    		$data['slug'] 				= 'aviso-transparencia';
                    		$data['to'] 				= implode(',',$send_to);
                    		$data['from'] 				= Settings::get('server_email');
                    		$data['name']				= Settings::get('site_name');
                    		$data['reply-to']			= 'transparencia@cobacam.edu.mx';//Settings::get('contact_email');
                            Events::trigger('email', $data, 'array');
                            
                            echo 'Email enviado =>'.implode(',',$send_to);
                        }
                        //print_r($list_users);
                        //echo 'Revisar: '.$fraccion->id.' - ';
                        //echo date('d m Y',$tiempo).'<br/>';
                    }
                    //date('d m Y',$tiempo)
                    
                }
                    
            }
            
           
            //echo 'residuo:'.$residuo.'|division:'.(date('m')/$fraccion->periodicidad).'|periodicidad:';
            //echo $fraccion->periodicidad.'<br/>';
        }
    }
    function index()
    {
        
         
         $tipo       = $this->input->get('tipo');
         $fracciones = false;
         
         if($tipo){
             $fracciones = $this->fraccion_m->where('tipo',$tipo)
                            ->get_all();
             
             
             foreach($fracciones as &$fraccion)
             {
                $fraccion->obligaciones = $this->obligacion_m->get_many_by(array('id_fraccion'=>$fraccion->id));
             }
         }
         $this->template->title($this->module_details['name'])
                ->set('fracciones',$fracciones)
                ->build('index');
    }
    function download($id_fraccion,$id_obligacion)
    {
        $this->load->library(array(
            'pdf',
            'files/files'
        ));
        
        $html2pdf = new HTML2PDF('L', 'A4', 'fr');
        
        $table = $this->obligacion_m->get_by(array(
            'id'=> $id_obligacion,
            'id_fraccion' => $id_fraccion
        ));
        
        
     
       $rows = Transparencia::GetValues($table->id_fraccion,$table->id,true);
       
      
       $output=$this->template->set_layout(false)
          //              ->title('Reporte ')
                        ->enable_parser(true)
						->build('templates/table_pdf',array('campos'=>json_decode($table->campos),'rows'=>$rows),true);
        
        
        //echo $output;
        $html2pdf->writeHTML($output);
        $html2pdf->Output('cobacam_transparencia_'.now().'.pdf','I');
    }
    function detalles($id=0)
    {
        
        
        $fraccion = $this->fraccion_m->get($id) OR show_404();

        $fraccion->obligaciones = $this->obligacion_m->get_many_by(array('id_fraccion'=>$id));

        
        foreach($fraccion->obligaciones as &$obligacion)
        {
            $obligacion->campos =  json_decode($obligacion->campos);

            if($obligacion->campos==null)
            {
                 $anexos = $this->db->select('anio, anexo_pdf,anexo_excel')
                           ->where(array('default_fraccion_obligaciones_archivos.id_fraccion' => $id,
                                   'default_fraccion_obligaciones_archivos.id_obligacion' => $obligacion->id))
                           ->order_by('fraccion_obligaciones_archivos.anio','DESC')
                           ->get('default_fraccion_obligaciones_archivos')->result();

               foreach ($anexos as $anexo) 
               {
                    if($anexo->anexo_pdf != null){
                        $obligacion->anexos_pdf[$anexo->anio] = $anexo->anexo_pdf;
                    }
                    if($anexo->anexo_excel != null){
                        $obligacion->anexos_excel[$anexo->anio] = $anexo->anexo_excel;
                    }
               }
            }               
        }
        
        $this->template->title($this->module_details['name'])
                ->set_breadcrumb($fraccion->nombre)
                ->set('fraccion',$fraccion)
                ->build('details');
    }

     function resumen()
     {
        $centros=$this->centro_m->get_all();

        array_pop($centros);
        
        $this->template ->set_breadcrumb('Informe de Actividades y Rendimiento de Cuentas en la Educación Media Superior')
                        ->set('centros',$centros)
                        ->title($this->module_details['name'])
                        ->build('resumen');
     }

     function informe($id=0)
    {

        $this->load->library('curl');

        if(!$centro=$this->centro_m->get($id))
        {
            redirect('transparencia/resumen');
        }
      
        
        $output = $this->curl->get('http://200.77.238.31/informe2016/web/documentossubidos/viewcct?CCT='.$centro->clave);
        

        
        preg_match('/<table[^>]*>(.*)<\/table>/s',$output,$matches);
        //preg_match('/<tbody[^>](.*)</tbody>/s',$output,$matches);
        if($matches)
        {
            $table =  $matches[0];

            //print_r(   preg_replace('/<a href="/', '<a href="http://200.77.238.31$1', $table));
            $table =  preg_replace('/<a href="/', '<a href="http://200.77.238.31$1', $table);


        }


         $this->template->title($this->module_details['name'])
                        ->set_breadcrumb('Informe de Actividades y Rendimiento de Cuentas en la Educación Media Superior  '.$centro->nombre)   
                        ->set('centro',$centro)
                        ->set('table',$table)
                        
                        ->build('informe');
    }

}
?>