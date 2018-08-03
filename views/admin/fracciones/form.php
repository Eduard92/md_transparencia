<section>
    <div class="lead text-success"><?=lang('fracciones:'.$this->method)?></div>
        <div class="ui-tab-container ui-tab-horizontal">
        
             <?php echo form_open_multipart(uri_string(), 'name="frm_fondo"  data-mode="'.$this->method.'"'); ?>
        	<uib-tabset justified="false" class="ui-tab">
	             <uib-tab heading="Información General">
            
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label><span>*</span> Tipo</label>
                                <?=form_dropdown('tipo',array(''=> ' [ Elegir ]','comun'=>'Comun','especifico'=>'Especifico'),$fraccion->tipo,'class="form-control"');?>
                            </div>
                            <div class="form-group">
                                <label><span>*</span> Nombre</label>
                                <?=form_input('nombre',$fraccion->nombre,'class="form-control"');?>
                            </div>
                            <div class="form-group">
                                <label><span>*</span> Numeral</label>
                                <?=form_input('numeral',$fraccion->numeral,'class="form-control"');?>
                            </div>
                            <div class="form-group">
                                <label>Descripción</label>
                                <?=form_textarea('descripcion',$fraccion->descripcion,'class="form-control"');?>
                            </div>
                            <div class="form-group">
                                <label>Periodicidad</label>
                                <?=form_dropdown('periodicidad',$periodos,$fraccion->periodicidad,'class="form-control"');?>
                            </div>
                            <div class="form-group">
                                <label>Aplicable</label>
                                <?=form_dropdown('aplicable',array('0'=>'No','1'=>'Si'),$fraccion->aplicable,'class="form-control" ng-model="aplicable" ng-init="aplicable=\''.$fraccion->aplicable.'\'"');?>
                            </div>
                            <div class="form-group" ng-if="aplicable==0">
                                <label><span>*</span> Motivo</label>
                                <?=form_textarea('motivo',$fraccion->motivo,'class="form-control"');?>
                                <p class="help-block">Explique cual es el motivo por el cual no es aplicable esta fracción.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                             <div class="form-group">
                                <label>Archivo anexo</label>
                                <div class="row">
                                    <?php if($fraccion->anexo):?>
                                    <div class="col-md-4">
                                        <?php $file = Files::get_file($fraccion->anexo);?>
                                   
                                        <a class="" href="<?=base_url('files/download/'.$file['data']->id)?>"><i class="fa fa-download"></i> Descargar archivo</a>
                                        <input type="hidden" name="anexo" value="<?=$file['data']->id?>" />
                                    </div>
                                    <?php endif;?>
                                    <div class="col-md-8">
                                         <?=form_upload('anexo')?>
                                    </div>
                                </div>
                                
                                    
                                
                               
                             </div>
                        </div>
                    </div>
                       
                        
                    
               </uib-tab>
               <uib-tab heading="Usuarios">
                  <div class="alert alert-info"><?=lang('fracciones:help_users')?></div>
                            <?php foreach($users as $user):?>
                                <label class="checkbox-inline">
                                    <?=form_checkbox('users[]',$user->id,in_array($user->id,is_array($fraccion->users)?$fraccion->users:array()));?>
                                    <?=$user->display_name?>
                                    <span class="help-block"><?=$user->email?></span>
                                    
                                </label>
                            <?php endforeach;?>
               </uib-tab>
         </uib-tabset>
         <div class="divider"></div>
                        <p><strong>Nota:</strong> Los campos marcados con (*) son obligatorios.</p>
                       <div class="buttons">
                    	<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))) ?>
                       </div>
         <?php echo form_close();?>
     </div>
         
    
</section>