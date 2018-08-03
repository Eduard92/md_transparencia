(function () {
    'use strict';
    
    angular.module('app.transparencia')
    .controller('IndexCtrl',['$scope','$http',IndexCtrl])
    .controller('IndexCtrlFraccion',['$scope','$http','logger',IndexCtrlFraccion])
    .controller('InputCtrlObligacion',['$scope',InputCtrlObligacion]);
    
    function IndexCtrl($scope,$http)
    {
        $scope.open_dropdown= false;
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
        
        $scope.campos = campos;
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