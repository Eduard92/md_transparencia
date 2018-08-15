<section ng-controller="IndexCtrl">
    <div class="lead text-success"><?=lang('transparencia:title')?></div>

        <?php echo form_open('','id="frm" method="get"');?>
    <div class="form-group">
        <label>Fracciones</label>
        <?=form_dropdown('fr',array(''=>'Todos')+$fr,$this->input->get('fr'),'class="form-control" onchange="$(\'#frm\').submit();" ')?>
    </div>
    
    <?php  echo form_close();?>
    <uib-accordion close-others="!oneAtATime" class="ui-accordion">
   
    <?php foreach($fracciones as $fraccion):?>
          <uib-accordion-group   heading="<?php echo $fraccion['numeral'];?> .- <?php echo $fraccion['fraccion'];?>" is-disabled="disabled"  >
              <?php if($fraccion['descripcion']):?>
                <div class="alert alert-info"><?=$fraccion['descripcion']?></div>
              <?php endif;?>
             
              <?php foreach($fraccion['obligaciones'] as $id_fraccion=>$table):?>
              <div class="row">
                <div class="col-md-12">
                    <div class="lead text-success"><?=$table->nombre_obligacion?></div>
                </div>
              </div>
              <div class="row">
                
                <div class="col-md-12">
                    <?php if($table->campos):?>
                    <a href="<?=base_url('admin/transparencia/create/'.$table->id_fraccion.'/'.$table->id)?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Agregar registro a esta tabla</a> 
                    <?php endif;?>
                    
                    
                    <a  href="<?=base_url('admin/transparencia/upload/'.$table->id_fraccion.'/'.$table->id)?>" class="btn btn-default pull-right"><i class="fa fa-upload"></i> <?=$table->anexos_pdf  || $table->anexos_excel?'Actualizar formatos':' Subir formatos' ?></a>
                </div>
              </div>
             
              <div class="row">
                  <div class="col-md-12" style="overflow-y: scroll;" >
                       <table class="table">
                        <thead>
                            <tr>
                                <?php foreach($table->campos as $campo):?>
                                <th width="<?=(90/count($table->campos)).'%'?>"><?=$campo->nombre?></th>
                                <?php endforeach;?>
                                <th width="14%">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach(Transparencia::GetValues($table->id_fraccion,$table->id) AS $value):?>
                                <tr>
                                     <?php $campos = json_decode($value->campos);?>
                                     
                                     <?php foreach($table->campos as $campo):?>
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
                                                case 'calendar':
                                                    $html = $campos->{$campo->slug};
                                                break;
                                                default:
                                                break;
                                             }
                                         ?>
                                             <td ng-non-bindable>
                                                <?php echo $html; ?>
                                             </td>
                                            
                                        <?php endforeach;?>
                                        <td class="text-center">
                                                <?php echo anchor('admin/transparencia/edit/'.$table->id.'/'.$value->id, lang('buttons:edit'), 'class="button edit" ') ?> | 
                                                <?php echo anchor('admin/transparencia/delete/'.$value->id, lang('buttons:delete'), 'class="button edit" confirm-action') ?>
                                        </td>
                                </tr>
                            <?php endforeach;?>
                        </tbody>
                      </table>
                  </div>
              </div>
             
              <?php endforeach;?>
              
          </uib-accordion-group>
    <?php endforeach;?>
    </uib-accordion>
    
</section>