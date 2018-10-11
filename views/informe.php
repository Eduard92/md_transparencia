<div class="container">
    <div class="row">
        <div class="col-md-3">
                <div id="page-sidebar" class="sidebar">
                         <aside>
                             <header><h2>Transparencia</h2></header>
                             <ul class="list-group list-unstyled">
                                  {{ navigation:links group="transparencia" li_class="list-group-item"  }}

                                  <li><a  href="<?=base_url('transparencia/resumen')?>"  >Informe de Actividades y Rendición de Cuentas en la Educación Media Superior</a></li>
                             </ul>
                             <a href="http://www.plataformadetransparencia.org.mx" target="_blank"><img src="http://www.plataformadetransparencia.org.mx/image/layout_set_logo?img_id=12601&t=1499310682500" /></a>
                       </aside>
                       
                   </div>
        </div>
        <div class="col-md-9">

             <header><h2>Informe de Actividades y Rendición de Cuentas en la Educación Media Superior <br><?=$centro->nombre?></h2>
             </header>

                  <div class="table-responsive">
                     <?=$table?>
                  </div>
            

        </div>
    </div>
</div>

