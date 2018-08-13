<section>
    <div class="lead text-success">
        <?php if($this->method == 'create'): ?>
            <?=sprintf(lang('transparencia:add_title'),$obligacion->nombre)?>
        <?php else:?>
            <?=sprintf(lang('transparencia:edit_title'),$obligacion->nombre)?>
        <?php endif;?>
    </div>
    <?php echo form_open_multipart(uri_string(), '');?>
        <?php foreach($obligacion->campos as $campo):?>
            <div class="form-group">
                <label class="col-lg-4"><?=$campo->nombre?></label>
                
                <div class="col-lg-8">
                <?php switch($campo->tipo){
                    
                         case 'input':
                                echo form_input($campo->slug,$desglose->campos->{$campo->slug},'class="form-control col-lg-8"');
                         break;
                         
                         case 'upload':
                                echo form_upload($campo->slug,null,'class="col-lg-8"');
                                echo '<a target="_blank" href="'.base_url('files/download/'.$desglose->campos->{$campo->slug}).'">Descargar archivo</a>';
                                echo form_hidden($campo->slug,$desglose->campos->{$campo->slug});
                         
                         break;
                         case 'input':
                                echo form_input($campo->slug,$desglose->campos->{$campo->slug},'class="form-control col-lg-8"');
                         break;
                         case 'calendar':

                                echo '<div ng-init="'.$campo->slug.'=\''.$desglose->campos->{$campo->slug}.'\'" class="input-group ui-datepicker">'.form_input($campo->slug,$desglose->campos->{$campo->slug},'readonly class="form-control"
                                        uib-datepicker-popup="dd/MM/yyyy" 
                                        ng-model="'.$campo->slug.'"
                                       is-open="open_'.$campo->slug.'" 
                                       
                                       datepicker-options="dateOptions" 
                                       date-disabled="disabled(date, mode)" 
                                       
                                       close-text="Cerrar"
                                ').'<span class="input-group-addon" ng-click="open_'.$campo->slug.'=true;"><i class="glyphicon glyphicon-calendar"></i></span></div>';
                         break;
                        
                         default:
                         break;
                      }
                ?>
                </div>
                <div class="clearfix"></div>
            </div>
            <hr />
            
        <?php endforeach;?>
         <div class="buttons">
            	   <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))) ?>
         </div>
    <?php echo form_close();?>
</section>