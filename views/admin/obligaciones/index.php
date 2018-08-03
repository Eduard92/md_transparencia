<section>
    <table   class="table" summary="catalogos"  width="100%">
             <thead>
                 <tr>
                   <th width="3%">
                   		<label>
                        <?php echo  form_checkbox(array(
                                    
                                    'class'=>'check-all'
                                    ));?>
                                 <span class="lbl"></span>	
                        </label>
                   </th>
                   
                 
                   <th>Fracción</th>
                   <th>Obligacion</th>
                   
                   <th width="14%">Acciones</th>
                 </tr>
            </thead>
            <tbody>
                <?php foreach($obligaciones as $obligacion){?>
                    <tr>
                        <td></td>
                        <td><?=$obligacion->numeral?>.- <?=$obligacion->nombre_fraccion?></td>
                        <td><?=$obligacion->nombre?></td>
                        
                        <td>
                            <?php echo anchor('admin/transparencia/obligaciones/edit/'.$obligacion->id, lang('buttons:edit'), 'class="button edit"') ?> 
                            
                            
                            </td>
                    </tr>
                <?php }?>
            </tbody>
    </table>
</section>