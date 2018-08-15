<div class="container">
    <div class="row">
        <div class="col-md-3">
                <div id="page-sidebar" class="sidebar">
                         <aside>
                             <header><h2>Transparencia</h2></header>
                             <ul class="list-group list-unstyled">
                                  {{ navigation:links group="transparencia" li_class="list-group-item"  }}
                            </ul>
                             
                         
                       </aside>
                       
                   </div>
        </div>
        <div class="col-md-9">
             <header><h2><?=$fraccion->nombre?></h2></header>
            <div class="jumbotron">
                            <dl class="dl-horizontal">
                                <dt>Aplicable:</dt>
                                <dd><span class="<?=$fraccion->aplicable?'text-success':'text-danger'?>"><?=$fraccion->aplicable?'Si':'No'?></span> </dd>
                                <dt>Descripción:</dt>
                                <dd><?=$fraccion->aplicable?($fraccion->descripcion?$fraccion->descripcion:'Sin descripción'):$fraccion->motivo?></dd>
                                <dt>Periodicidad:</dt>
                                <dd><?php if ($fraccion->periodicidad == 1): ?>
                                        Mensual
                                    <?php elseif ($fraccion->periodicidad == 3): ?>
                                        Trimestral
                                    <?php elseif ($fraccion->periodicidad == 6): ?>
                                        Semestral
                                    <?php elseif ($fraccion->periodicidad == 12): ?>
                                        Anual
                                    <?php endif;?>
                                </dd>
                                
                                <!--dt>Documento PDF:</dt>
                                <dd>
                                <a href="#">Descargar</a></dd>
                                <dt>Documento XLSX:</dt>
                                <dd><a href="#">Descargar</a></dd-->
                                
                            
                            </dl>
            </div>
            
            <?php foreach($fraccion->obligaciones as $obligacion):?>
                <h4>
                    <?=$obligacion->nombre?>
                    <?php if($obligacion->anexos_pdf):?>
                        <div class="btn-group pull-right">
                          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Descargar PDF <span class="caret"></span>
                          </button>
                             <ul class="dropdown-menu">
                              <?php foreach($obligacion->anexos_pdf as $anio_pdf => $pdf):?>
                                <li><a  href="#" onClick="download_ltaipec('<?=base_url('files/download/'.$pdf)?>')" ><?='LTAIPEC '.$anio_pdf?></a></li>
                              <?php endforeach;?>
                            </ul>
                        </div>
                    <?php endif;?>  
                    <?php if($obligacion->anexos_excel):?>
                        <div class="btn-group pull-right">
                          <button type="button" class="btn btn-default dropdown-toggle"
                                  data-toggle="dropdown">
                            Descargar Excel <span class="caret"></span>
                          </button>
                            <ul class="dropdown-menu" role="menu">
                               <?php foreach($obligacion->anexos_excel as $anio_excel => $excel):?>
                                 <li><a href="#" onClick="download_ltaipec('<?=base_url('files/download/'.$excel)?>')" ><?='LTAIPEC '.$anio_excel?></a></li>
                               <?php endforeach;?>
                            </ul>
                        </div>
                    <?php endif;?>
                </h4>
                <?php if($obligacion->helper):?>
                <div class="alert alert-info"><i class="fa fa-question-circle"></i> <?=$obligacion->helper?></div>
                <?php endif;?>
                <div class="clearfix"></div>
                <div class="table-responsive">
                    <table class="table table-hover course-list-table tablesorter">
                            <thead>
                                <tr>
                                    
                                    <?php foreach($obligacion->campos as $campo):?>
                                     <th width="<?=(100/count($obligacion->campos)).'%'?>"><?=$campo->nombre?></th>
                                     <?php endforeach;?>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                
                                
                                
                                <?php foreach(Transparencia::GetValues($obligacion->id_fraccion,$obligacion->id) AS $value):?>
                                <tr>
                                     <?php $campos = json_decode($value->campos);?>
                                     
                                     <?php foreach($obligacion->campos as $campo):?>
                                         <?php 
                                             $html = '';
                                             
                                             switch($campo->tipo){
                                                case 'upload':
                                                    
                                                    $file   = Files::get_file($campos->{$campo->slug});
                                                    //print_r($file);
                                                    $html   = '<a  href="'.$file['data']->path.'" target="_blank">'.$file['data']->path.'</a>'; 
                                                break; 
                                                case 'input':
                                                    $html = $campos->{$campo->slug};
                                                break;
                                                default:
                                                break;
                                             }
                                         ?>
                                             <td>
                                                <?php echo $html; ?>
                                             </td>
                                            
                                        <?php endforeach;?>
                                        
                                </tr>
                            <?php endforeach;?>
                                
                            </tbody>
                </table>
            </div>
            <?php endforeach;?>
        </div>
    </div>
</div>

<script language=javascript>
function download_ltaipec(url) {
    window.open(url);
}
</script>        