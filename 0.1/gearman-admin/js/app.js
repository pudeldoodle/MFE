var app = angular.module('app', [
 'controllers'
  ]);

var Controllers = angular.module('controllers',[]);

Controllers.controller('MyController',
	function ($scope, $http,$timeout)
	{

		$scope.timeInMs = 0;
		
		var get = function(){
			
			$scope.timeInMs+= 500;
			$timeout(get, 500);
			$http.get('gearman-admin.php?a=json').success(function(data){
				$scope.data = data;
			});
			$scope.$apply();
		};
		 
		$timeout(get, 500);
		/*
		$scope.data = {
		    "workers": [
		        {
		            "worker": "gm_add_user",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "gm_uips_by_user",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "gm_send_friends_by_user_to_niji",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 1
		        },
		        {
		            "worker": "gm_send_uip_to_niji",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "gm_graph_uip",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "gm_fc_4",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "user_basic",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "user_likes",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "gm_fc_2",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "gm_user_has_uip",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 5
		        },
		        {
		            "worker": "gm_user_friends",
		            "queued_jobs": 15,
		            "running_jobs": 0,
		            "avalaible_workers": 0
		        },
		        {
		            "worker": "gm_fc_3",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        },
		        {
		            "worker": "gm_fc_1",
		            "queued_jobs": 0,
		            "running_jobs": 0,
		            "avalaible_workers": 6
		        }
		    ],
		    "servers": [
		        {
		            "ip": "172.28.8.200",
		            "details": [
		                {
		                    "worker": "gm_send_uip_to_niji",
		                    "total_workers": 6
		                },
		                {
		                    "worker": "gm_fc_4",
		                    "total_workers": 6
		                }
		            ]
		        }
		    ]
		};
		*/
		//$scope.data = {"workers":[{"worker":"gm_add_user","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"gm_uips_by_user","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"gm_send_friends_by_user_to_niji","queued_jobs":0,"running_jobs":0,"avalaible_workers":1},{"worker":"gm_send_uip_to_niji","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"gm_graph_uip","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"gm_fc_4","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"user_basic","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"user_likes","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"gm_fc_2","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"gm_user_has_uip","queued_jobs":0,"running_jobs":0,"avalaible_workers":5},{"worker":"gm_user_friends","queued_jobs":15,"running_jobs":0,"avalaible_workers":0},{"worker":"gm_fc_3","queued_jobs":0,"running_jobs":0,"avalaible_workers":6},{"worker":"gm_fc_1","queued_jobs":0,"running_jobs":0,"avalaible_workers":6}],"servers":{"172.28.8.200":[{"worker":"gm_user_has_uip","total_workers":5},{"worker":"gm_graph_uip","total_workers":6},{"worker":"user_likes","total_workers":6},{"worker":"gm_add_user","total_workers":6},{"worker":"gm_fc_1","total_workers":6},{"worker":"gm_uips_by_user","total_workers":6},{"worker":"gm_fc_2","total_workers":6},{"worker":"user_basic","total_workers":6},{"worker":"gm_fc_3","total_workers":6},{"worker":"gm_send_friends_by_user_to_niji","total_workers":1},{"worker":"gm_send_uip_to_niji","total_workers":6},{"worker":"gm_fc_4","total_workers":6}]}};
	//	console.log($scope.data);
		//$scope.data = '[{"worker":"gm_add_user","queued_jobs":0,"running_jobs":0,"avalaible_workers":6}]';
	}
);