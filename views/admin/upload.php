<section>
    <div class="lead text-success"><?=sprintf(lang('transparencia:uploads'),$obligacion->nombre)?></div>
     <?php echo form_open_multipart(uri_string()); ?>
    <div class="form-group">
        <label>Documento PDF</label>
        <div class="row">
            <?php $file_pdf = Files::get_file($obligacion->anexo_pdf) ?>
            <?php if($file_pdf['status']):?>
               
            <div class="col-md-3">
                 <?=$file_pdf['data']->name?> | <a target="_blank" href="<?=$file_pdf['data']->path?>">Descargar  </a>
                 <input type="hidden" name="anexo_pdf" value="<?=$obligacion->anexo_pdf?>" />
            </div>
            <?php endif;?>
            
            <div class="col-md-9">
                <?=form_upload('anexo_pdf',false,'accept="application/pdf"')?>
                
            </div>
            
        </div>
        
    </div>   
    <hr />
    <div class="form-group">
        <label>Documento Excel</label>
        <div class="row">
            
            <?php $file_xls = Files::get_file($obligacion->anexo_excel);?>
            <?php if($file_xls['status']):?>
            <div class="col-md-3 block-file">
                
                <?=$file_xls['data']->name?> | <a target="_blank" href="<?=$file_xls['data']->path?>">Descargar </a> 
                <input type="hidden" name="anexo_excel" value="<?=$obligacion->anexo_excel?>" />
            </div>
            <?php endif;?>
            
            <div class="col-md-9">
                <?=form_upload('anexo_excel',false,'accept="application/vnd.ms-excel"')?>
            </div>
            
        </div>
    </div> 
    <div class="form-actions">
    
        <?php $this->load->view('admin/partials/buttons', array('buttons' => array('cancel','save'))) ?>
    </div>    
    <?php echo form_close();?>                       
</section>