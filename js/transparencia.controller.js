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
         $scope.id_doc = '';
         $scope.anexo_pdf ='';
         $scope.anexo_excel ='';
         $scope.dispose = true;


        

         $scope.cancel = function(){
             $uibModalInstance.dismiss("cancel");
        }

        $scope.consult = function()
        {

            $http.post(SITE_URL+'admin/transparencia/consult_doc',{id_fraccion:id_fraccion,id_obligacion:id_obligacion,anio:$scope.anio}).then(function(response){
              
                 var result = response.data;
                
                 if (result.status)
                 {
                     $scope.id_doc = result.id;
                 }
                 if (result.anexo_excel)
                 {
                     $scope.anexo_excel  = 'Actualmente tiene un documento.';
                 }
                 if (result.anexo_pdf)
                 {
                     $scope.anexo_pdf = 'Actualmente tiene un documento.';
                 }

                 console.log( result);
                      
            });
        }


                
        $scope.save = function(){



                   $uibModalInstance.close();

                  
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
                  
                     $scope.id_doc =  result.id?result.id:$scope.id_doc;

                     files.length = 0;

                      $.each(result.files, function( key, value ) {
                            console.log( key + ": " + value );

                            files.push(value);

                      });

                      

                     

                     console.log( files );
                  
                 //console.log(result);
                 
                 
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