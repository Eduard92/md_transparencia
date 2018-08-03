<section ng-controller="InputCtrlObligacion">
    <div class="lead text-success"><?=lang('obligaciones:create')?></div>
    <?php echo form_open(uri_string(), ''); ?>
          <div class="form-group">
              <label for="titulo">Nombre</label>
              
			  	<?php echo form_input('nombre', $obligacion->nombre,'class="form-control"');?>
                
              
          </div>
          <div class="form-group">
              <label for="titulo">Ayuda</label>
              
			  	<?php echo form_textarea('helper', $obligacion->helper,'class="form-control"');?>
                <p class="help-block">Mensaje de ayuda para el llenado de esta obligación.</p>
              
          </div>
          <hr />
          
          <h4>Campos de la obligación</h4>
          <div class="row">
            <div class="col-md-8">
                <?=form_input('campo','','class="form-control" placeholder="Nombre del campo" ng-model="form.nombre" ')?>
                <slug from="form.nombre" to="form.slug" >
                <? //=form_input('slug','','class="form-control" placeholder="Slug" ng-model="form.slug" ')?>
                <input type="hidden" name="slug" ng-model="form.slug" />
                </slug>
            </div>
           
            <div class="col-md-2">
                <?=form_dropdown('tipo',array(''=>'Tipo','input'=>'Texto','upload'=>'Archivo','anchor'=>'Hipervinculo'),'','class="form-control" ng-model="form.tipo"')?>
            </div>
             <div class="col-md-2">
                <a ng-click="add()" title="Agregar campo" class="btn-icon btn-icon-sm btn-primary  ui-wave" href="javascript:;" ui-wave=""><i class="fa fa-plus"></i></a>
             </div>
          </div>
          <hr />
          
          <table class="table">
            <thead>
                <tr>
                    <th>Campo</th>
                    <th>Tipo</th>
                    <th></th>
                </tr>
            </thead>
            <tbody >
                <tr  ng-repeat="campo in campos">
                    <td>{{campo.nombre}}</td>
                    <td width="14%">{{campo.tipo}}</td>
                    <td width="10%">
                        <input type="hidden" name="campos[{{$index}}][nombre]" value="{{campo.nombre}}" />
                        <input type="hidden" name="campos[{{$index}}][tipo]" value="{{campo.tipo}}" />
                        <input type="hidden" name="campos[{{$index}}][slug]" value="{{campo.slug}}" />
                        
                        <a ng-click="remove($index)" title="Agregar campo" class="btn-icon btn-icon-sm btn-danger  ui-wave" href="javascript:;" ui-wave=""><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            </tbody>
          </table>
          <!--div ui-tree="options">
            <ol ui-tree-nodes ng-model="campos">
                <li ui-tree-node ng-repeat="campo in campos" ng-include="'items_renderer.html'"></li>
            </ol>
          </div-->
          <div class="buttons">
        	   <?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel'))) ?>
          </div>
    <?php echo form_close();?>
    <script type="text/ng-template" id="items_renderer.html">
        <div class="angular-ui-tree-handle">
            <span>{{campo.nombre}}</span>
        </div>
    </script>
</section>