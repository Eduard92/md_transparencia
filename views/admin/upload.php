<section ng-controller="InputCtrl">
    <div class="lead text-success"><?=sprintf(lang('transparencia:uploads'),$obligacion['0']->nombre)?></div>
     <?php echo form_open_multipart(uri_string()); ?>
    <a href="#"  ng-click="upload()" uib-tooltip="Subir Archivo" class="btn btn-primary pull-right">Administrar Archivos</a>

        <div class="row col-md-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>Archivo PDF</th>
                        <th>Archivo Excel</th>    
                        <th>LTAIPEC</th>
                        <th>Actualizado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="file in files_obligacion">
                        <td>
                            <a target="_blank" ng-if="file.pdf != null" href="<?=base_url('files/download/{{file.pdf}}')?>" data-toggle="popover" title="Descargar {{file.pdf}}" data-content="">Descargar</a>
                        </td>
                        <td>
                            <a target="_blank" ng-if="file.excel != null" href="<?=base_url('files/download/{{file.excel}}')?>" data-toggle="popover" title="Descargar {{file.excel}}" >Descargar</a>
                        </td>
                        <td>{{file.anio}}</td>
                        <td>{{file.date}}</td>
                        <td>
                            <a href="#" ng-click="delete(file.id,$index)" class="btn btn-danger" confirm-action><i class="fa fa-trash"></i></a>
                        </td>
                                        
                    </tr>
                </tbody>
            </table>

    </div>
    <?php echo form_close();?>                       


</section>

<script type="text/ng-template" id="modalUpload.html">
    <div class="modal-header" >
        <h3>Archivos</h3>
    </div>
     <?php  echo form_open();?>
    <div class="modal-body">
        <div class="alert alert-warning" ng-if="!dispose">Favor de no cerrar esta ventana, hasta terminar con el proceso</div>                 
 
                      <div class="form-group">
                            <label>LTAIPEC</label>
                            <select class="form-control" ng-model="anio" ng-change = "consult()" required>
                                <option value=""> [ Elegir ] </option>
                                <option value="2017"> 2017 </option>
                                <option value="2018"> 2018 </option>
                            </select>
                     </div> 
                     
                     <div class="form-group" ng-if="anio!=null">
                        <label>Archivo PDF</label>
                        <div class="row">
                            <div class="col-md-8">
                                <input type="file"  accept=".pdf" ngf-select="upload_file(file_pdf,'pdf')"  ng-model="file_pdf"
                                ngf-max-height="10000" ngf-max-size="80MB"/>
                                <md-progress-linear md-mode="determinate" ng-show="file_pdf.progress >= 0" value="{{file_pdf.progress}}"></md-progress-linear>
                                <br>
                                <span class="label label-danger" ng-show="errorMsg">{{errorMsg}}</span>
                                <span class="label label-info" ng-show="anexo_pdf">{{anexo_pdf}}</span>
                            </div>
                            <div class="col-md-4">        
                                <a href="#" ng-if="status_pdf == true && cont == 2" ng-click="delete_file('pdf')" class="btn btn-danger" confirm-action><i class="fa fa-trash"></i></a>
                            </div>
                        </div>             
                     </div>

                     <div class="form-group" ng-if="anio!=null">
                        <label>Archivo Excel</label>
                        <div class="row">
                             <div class="col-md-8">
                                <input type="file"  accept=".xlsx,.xls" ngf-select="upload_file(file_excel,'xlsx')"  ng-model="file_excel"
                                ngf-max-height="10000" ngf-max-size="80MB"/>
                                <md-progress-linear md-mode="determinate" ng-show="file_excel.progress >= 0" value="{{file_excel.progress}}"></md-progress-linear>
                                <br>
                                <span class="label label-danger" ng-show="errorMsg">{{errorMsg}}</span>
                                <span class="label label-info" ng-show="anexo_excel">{{anexo_excel}}</span> 
                             </div>
                            <div class="col-md-4"> 
                                <a href="#" ng-if="status_excel == true && cont == 2" ng-click="delete_file('excel')" class="btn btn-danger" confirm-action><i class="fa fa-trash"></i></a>                       
                            </div>
                        </div>             
                     </div>
                                   
    </div>
    <div class="modal-footer">
       
                        
        <button type="button" ui-wave class="btn btn-flat" ng-click="cancel()">Cancelar</button>
        <button type="button" ui-wave class="btn btn-flat btn-primary" ng-if="anio!=null" ng-disabled="!dispose" ng-click="save()" ">Aceptar</button>
    </div>    
     <?php echo form_close(); ?>                       
</script>

