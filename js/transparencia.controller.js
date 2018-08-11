(function () {
    'use strict';
    
    angular.module('app')
    .controller('IndexCtrl',['$scope','$http',IndexCtrl])
    .controller('InputCtrl',['$scope','$http','$uibModal','logger',InputCtrl])
    .controller('IndexCtrlFraccion',['$scope','$http','logger',IndexCtrlFraccion])
    .controller('InputCtrlObligacion',['$scope',InputCtrlObligacion])

    .controller('InputModal',['$scope','$http','$uibModalInstance','$timeout','$cookies','logger','Upload','files',InputModal]);


    function IndexCtrl($scope,$http)
    {
        $scope.open_dropdown= false;
    }    

    function InputCtrl($scope,$http,$uibModal,logger)
    {
        $scope.files_obligacion = files;
 
        $scope.delete = function(id,index)
        {
            $http.post(SITE_URL+'admin/transparencia/delete_file',{id_fraccion:id_fraccion,id_obligacion:id_obligacion,id:id}).then(function(response){
                           

              var result = response.data;
                if (result.status == true)
                 {
                    $scope.files_obligacion.splice(index,1);

                 }

                 logger.logSuccess(result.message);
                      
            });

        }

       $scope.upload = function()
       {
           
              var modalInstance = $uibModal.open({
                            animation: $scope.animationsEnabled,
                            templateUrl: 'modalUpload.html',
                            controller: 'InputModal',
                  
                            resolve: {
                   
                               files: function () {
                                 return $scope.files_obligacion;
                               },

                            }
                      });

       } 

          
    }

    function InputModal($scope,$http,$uibModalInstance,$timeout,$cookies,logger,Upload,files)
    {
         //$scope.id_doc = '';
         //$scope.anexo_pdf ='';
         //$scope.anexo_excel ='';
         $scope.dispose = true;
         $scope.cont = 0;
         $scope.cont_temp1 = 0;
         $scope.cont_temp2 = 0;

        

         $scope.cancel = function(){
             $uibModalInstance.dismiss("cancel");
        }

        $scope.consult = function()
        {
         $scope.id_doc = '';
         $scope.anexo_pdf ='';
         $scope.anexo_excel ='';
         $scope.status_excel = false;
         $scope.status_pdf = false;

            $http.post(SITE_URL+'admin/transparencia/consult_doc',{id_fraccion:id_fraccion,id_obligacion:id_obligacion,anio:$scope.anio}).then(function(response){
              
                 var result = response.data;


                
                 if (result.status)
                 {
                     $scope.id_doc = result.id;
                 }
                 if (result.anexo_excel)
                 {
                     $scope.anexo_excel  = 'Actualmente tiene un documento.';
                     $scope.status_excel = true;
                     $scope.cont_temp1 = 1;

                 }
                 if (result.anexo_pdf)
                 {
                     $scope.anexo_pdf = 'Actualmente tiene un documento.';
                     $scope.status_pdf = true;
                     $scope.cont_temp2 = 1;
                 }

                 $scope.cont = $scope.cont_temp1 + $scope.cont_temp2;
                      
            });
        }


                
        $scope.save = function()
        {
             $uibModalInstance.close();
                  
        }

        $scope.delete_file = function(tipo)
        {
            console.log(tipo);
            $http.post(SITE_URL+'admin/transparencia/update',{tipo:tipo,id:$scope.id_doc,id_fraccion:id_fraccion,id_obligacion:id_obligacion,}).then(function(response){           

              var result = response.data;
                console.log(result);
                if(result.status == true)
                {
                    files.length = 0;

                      $.each(result.files, function( key, value ) {
                            //console.log( key + ": " + value );

                            files.push(value);

                      });
                      logger.logSuccess(result.message);
                      $scope.cont = 1;

                    if(tipo == 'pdf')
                    {
                        $scope.anexo_pdf = null;
                    }
                    else
                    {
                        $scope.anexo_excel = null;
                    }
                }
                else
                {
                 logger.logError(result.message);   
                }          
            });
            
                
        }

        $scope.upload_file = function(file,type)
        {
            // $scope.files_obligacion;
            $scope.dispose = false;
            
            file.upload = Upload.upload({
              url: SITE_URL+'admin/transparencia/upload_file',
              data: { id:$scope.id_doc,id_fraccion:id_fraccion,id_obligacion:id_obligacion,anio:$scope.anio,type:type,file: file,csrf_hash_name:$cookies.get(pyro.csrf_cookie_name)},
            });
            
            file.upload.then(function (response) {
              var  result = response.data,
                   data   = response.data.data;
              $timeout(function () {
                  file.result = response.data;
                  $scope.dispose = true;
                  
                  if(result.status == true){
                     $scope.id_doc =  result.id?result.id:$scope.id_doc;

                     files.length = 0;

                      $.each(result.files, function( key, value ) {
                            //console.log( key + ": " + value );

                            files.push(value);

                      });
                      logger.logSuccess('Archivo Subido Exitosamente');
                       $scope.cont =  $scope.cont + 1;

                    if(type == 'pdf')
                    {
                        $scope.anexo_pdf = 'Actualmente tiene un documento.';
                    }
                    else
                    {
                         $scope.anexo_excel = 'Actualmente tiene un documento.';

                    }
                  }
                  else
                  {
                    logger.logError(result.message);
                  }
                 
                 
              });
            }, function (response) {
              if (response.status > 0)
                $scope.errorMsg = response.status + ': ' + response.data;
            }, function (evt) {
              
              file.progress = Math.min(100, parseInt(100.0 * evt.loaded / evt.total));
            });
            
            
        }


    }

    function IndexCtrlFraccion($scope,$http,logger)
    {
        $scope.show_order = false;
        $scope.fracciones = fracciones;
        
         $scope.options = {
            dropped: function(scope) {
                //console.log(scope.source.nodeScope.$modelValue);
                /*var category_id = scope.source.nodeScope.$modelValue.category_id,                   
                    list        = $scope.categories[scope.source.nodeScope.$modelValue.category_id].list,
                    form_data   = {},
                    order       = []7*/;
                var order = [];
                angular.forEach($scope.fracciones,function(item,index){
                    
                         
                    
                  
                    
                    order[index]= item.id;//set_node(index,item);
                    
                    
                    
                    
                });
                var form_data={
                  //data  :{group:group_id},
                  //order : order
                  
                     
                     order:order
                 };
                
                
                
                $http.post(SITE_URL+'admin/transparencia/fracciones/order',form_data).then(function(response){
                    console.log(response);
                    var result  = response.data,
                        status  = result.status,
                        message = result.message;
                    
                    if(status)
                    {
                         logger.logSuccess(message);
                    }
                    else
                    {
                         logger.logSuccess(message);
                    }
                    
                });
            }
         }
    }
    function InputCtrlObligacion($scope)
    {
        $scope.form   = {};
        $scope.list = [];
        $scope.aplicable = 0;
        
        $scope.campos = campos?campos:[];
        $scope.add = function()
        {
            
            if(!$scope.form.nombre || !$scope.form.tipo){
                alert('Todos los campos son requeridos');
                return false;
            }
            $scope.campos.push($scope.form);
            $scope.form = {};
        }
        $scope.remove = function(index)
        {
            $scope.campos.splice(index,1);
        }
    }
    
})();