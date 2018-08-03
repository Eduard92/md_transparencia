<section ng-controller="IndexCtrlFraccion">
    <div class="lead text-success"><?=lang('fracciones:title')?></div>
    <hr />
    <div class="row">
        <div class="col-md-12">
            <a href="#" ng-hide="show_order" ng-click="show_order=true" title="Ordernar la visualización de las fracciones" class="btn btn-default pull-right">
                Ordenar registros
            </a>
            <a href="#" ng-hide="!show_order" ng-click="show_order=false" title="Regresar a la tabla" class="btn btn-default pull-right">
                Regresar
            </a>
        </div>
    </div>
    <table ng-if="!show_order"   class="table" summary="catalogos"  width="100%">
             <thead>
                 <tr>
                   <th width="3%">
                   		<label>
                        <?php //echo  form_checkbox(array(
                                    
                                //    'class'=>'check-all'
                                  //  ));?>
                                 	
                        </label>
                   </th>
                   
                 
                   <th>Numeral</th>
                   <th>Tipo</th>
                   <th>Nombre</th>
                   <th width="10%">Aplicable</th>
                   
                   
                   <th>Período</th>
                   <th width="14%">Acciones</th>
                 </tr>
            </thead>
            <tbody>
                
                    <tr ng-repeat="fraccion in fracciones" class="" ng-class="{'danger':fraccion.aplicable==0}">
                        <td></td>
                        <td>{{fraccion.numeral}}</td>
                         <td>{{fraccion.tipo}}</td>
                        <td>{{fraccion.nombre}}</td>
                        <td>{{fraccion.aplicable=='1'?'SI':'NO'}}</td>
                        <td>{{fraccion.periodicidad}}</td>
                        <td>
                            <?php echo anchor('admin/transparencia/fracciones/edit/{{fraccion.id}}', lang('buttons:edit'), 'class="button edit"') ?> 
                            
                        </td>
                    </tr>
              
            </tbody>
    </table>
    <div ui-tree="options" ng-if="show_order" >
        <ol ui-tree-nodes ng-model="fracciones">
            <li ng-repeat="fraccion in fracciones" ui-tree-node ng-include="'items_renderer.html'"></li>
        </ol>
    </div>
</section>
<script type="text/ng-template" id="items_renderer.html">
                <div class="angular-ui-tree-handle">
                    <span><strong>{{fraccion.numeral}}</strong> - {{fraccion.nombre}}</span>
                </div>
</script>