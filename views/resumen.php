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
                <div id="page-main">
                    <section class="course-listing" id="courses">
                        <header><h2>Informe de Actividades y Rendimiento de Cuentas en la Educación Media Superior</h2></header>
                       
                        <section id="course-list">
                            <div class="table-responsive">
                                <table class="table table-hover course-list-table tablesorter">
                                    <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Municipio</th>
                                        
                                        <th>Clave</th>
                                        <th class="starts"></th>
                                        
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($centros as $centro){?>
                                        <tr>
                                            <th class="course-title"><?=$centro->nombre?></th>
                                            <th class="course-category"><?=$centro->municipio?></th>
                                            <th class="course-category"><?=$centro->clave?></th>
                                            <th><a href="<?=base_url('transparencia/informe/'.$centro->id)?>">Detalles</a></th>
                                            
                                        </tr>
                                       <?php }?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </section><!-- /.course-listing -->
                    
                </div><!-- /#page-main -->
            </div><!-- /.col-md-8 -->
            
    </div>
</div>