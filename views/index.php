<style type="text/css">
    .wrapper-text p{
        font-size: 16px !important;
    }
    .jumbotron{
        padding-left:40px!important;
        padding-right:40px!important;
    }
    .sidebar img{
        width: 100%;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md-3">
                <div id="page-sidebar" class="sidebar">
                         <aside>
                             <header><h2>Transparencia</h2></header>
                             <ul class="list-group list-unstyled">
                                  {{ navigation:links group="transparencia" li_class="list-group-item"  }}

                                   <li><a  href="<?=base_url('transparencia/resumen')?>"  >Informe de Actividades y Rendimiento de Cuentas en la Educación Media Superior</a></li>
                             </ul>
                             
                             <a href="http://www.plataformadetransparencia.org.mx" target="_blank"><img src="http://www.plataformadetransparencia.org.mx/image/layout_set_logo?img_id=12601&t=1499310682500" /></a>
                       </aside>
                       
                   </div>
        </div>
        <div class="col-md-9">
            <header><h2>Transparencia</h2></header>
            <p>
    De conformidad con lo establecido en el acuerdo publicado en el Diario Oficial de la Federación de fecha 2 de noviembre de 2016, los sujetos obligados del ámbito federal, estatal y municipal tienen como fecha límite hasta el 4 de mayo de 2017 para incorporar a sus portales de Internet y a la Plataforma Nacional de Transparencia la información relativa a sus respectivas obligaciones de transparencia.
            </p>
            <p>(LGTAIP.- Artículo 31, Fracción IV, Título Quinto)</p>
            
            <hr />
            <?php if(!$this->input->get('tipo')){?>
            <div class="row">
                <div class="col-md-6">
                    <div class="jumbotron">
                        <h2>Obligaciones comunes</h2>
                        <div class="wrapper-text">
                            <p><strong>Artículo 70</strong></p>
                            <p>Ley General de Transparencia  y Acceso a la Información Pública</p>
                            
                            <p><strong>Artículo 74</strong></p>
                            <p>Ley de Transparencia  y Acceso a la Información Pública del Estado de Campeche</p>
                        </div>
                        <div class="text-center">
                            <a href="<?=base_url('transparencia/?tipo=comun')?>" class="btn btn-default btn-lg">Entrar</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="jumbotron">
                        <h2>Obligaciones específicas</h2>
                        <div class="wrapper-text">
                            <p><strong>Artículo 71</strong></p>
                            <p>Ley General de Transparencia  y Acceso a la Información Pública</p>
                            
                            <p><strong>Artículo 75</strong></p>
                            <p>Ley de Transparencia  y Acceso a la Información Pública del Estado de Campeche</p>
                        </div>
                        <div class="text-center">
                            <a href="<?=base_url('transparencia/?tipo=especifico')?>" class="btn btn-default btn-lg">Entrar</a>
                        </div>
                    </div>
                </div>
                
            </div>
            <?php }?>
            <?php if($this->input->get('tipo')){?>
                            <table class="table table-hover course-list-table tablesorter">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th width="10%">Aplicable</th>
                                            <th width="10%">Periodicidad</th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($fracciones as $fraccion):?>
                                        <tr>
                                            <th><strong><?=$fraccion->numeral?></strong> - <?=$fraccion->nombre?></th>
                                            <th class="text-center"><a class="<?=$fraccion->aplicable?'text-success':'text-danger'?>"><?=$fraccion->aplicable?'<i class="fa fa-check"></i>':'<i class="fa fa-close"></i>'?></a></th>
                                            <th><?php if ($fraccion->periodicidad == 1): ?>
                                                Mensual
                                                <?php elseif ($fraccion->periodicidad == 3): ?>
                                                Trimestral
                                                <?php elseif ($fraccion->periodicidad == 6): ?>
                                                Semestral
                                                <?php elseif ($fraccion->periodicidad == 12): ?>
                                                Anual
                                            <?php endif;?>
                                            </th>
                                            <th><a href="<?=base_url('transparencia/detalles/'.$fraccion->id)?>">Detalles</a></th>
                                        </tr>
                                        <?php endforeach;?>
                                    </tbody>
                                </table>
            <?php }?>   
            
            
        </div>
    </div>
    
</div>