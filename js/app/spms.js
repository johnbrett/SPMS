var spms = angular.module('spms', ['restangular', 'angles']).config(function(RestangularProvider, $httpProvider){
		RestangularProvider.setBaseUrl('./router.php')
		$httpProvider.defaults.headers.post  = {'Content-Type': 'application/x-www-form-urlencoded'};
	})

spms.controller('SessionCtrl', function($scope, Restangular){

 	$scope.templates = 
		[ { name: 'auth', url: 'partials/auth.html'}
		, { name: 'student-results', url: 'partials/student-results.html'} ]	

	$scope.checkSession = function() {
		Restangular.all("auth").getList().then(function(result){
			if(result[0]) {
				$scope.template = $scope.templates[0]
			} else {
				$scope.template = $scope.templates[1]
			}
		})
	}

	$scope.createSession = function(username, password)	{
		Restangular.all("auth").post("PHP_AUTH_USER=" + username +"&PHP_AUTH_PW=" + password).then(function(result){
			if(result.valid === "true") {
				$scope.userSession = result
				$scope.template = $scope.templates[1]
			} else {
				$scope.loginMessage = "User Login Failed"
				$scope.template = $scope.templates[0]
			}
		})
	}

	$scope.destroySession = function() {
		Restangular.all("auth").post("PHP_AUTH_USER=&PHP_AUTH_PW=").then(function(result){
			$scope.loginMessage = "You are now logged out"
			$scope.template = $scope.templates[0]	
		})
	}
})

spms.controller('RESTCtrl', function($scope, Restangular){

	$scope.barchart = {
		labels : [],
		datasets : [
			{
				fillColor : "rgba(151,187,205,0)",
				strokeColor : "#e67e22",
				pointColor : "rgba(151,187,205,0)",
				pointStrokeColor : "#e67e22",
				data : []
			}
		]
	}
    $scope.piechart = [];
	$scope.options = {
		scaleOverride : true,
		scaleSteps : 5,
		scaleStepWidth: 20,
		scaleStartValue: 0,
        animationSteps: 60,
        animationEasing: "easeOutQuart"
	}

	$scope.checkSession = function(){
		$scope.validSession = Restangular.all("auth").getList()
	}

	$scope.getStudents = function(id) {
		if(id)
			$scope.student = Restangular.one("student", id).get()
		else
			$scope.students = Restangular.all("student").getList()
	}

	$scope.getLabs = function(id) {
		if(id)
			$scope.lab = Restangular.one("lab", id).getList()
		else
			$scope.labs = Restangular.all("lab").getList()
	}

	$scope.getResults = function(id) {
		if(id)
			$scope.groupResults = Restangular.one("group", id).getList()
		else
			$scope.results = Restangular.all("result").getList()
	}

	$scope.getStudentResults = function(id) {
		$scope.studentResults = Restangular.one("student", id).all("result").getList()

		var marks = [];
		var labs = [];
        var colours = [
            {
                value : 0,
                color: "#00CC00"
            },
            {
                value : 0,
                color: "#FF9900"
            },
            {
                value : 0,
                color: "#CC0000"
            }
        ]
		$scope.studentResults.then( function(results){
			for(var i=0; i<results.length; i++){
                marks.push(+results[i].mark) //+ to cast to string
				labs.push("Lab "+(i+1));
                if(results[i].colour=="green") $scope.piechart[0].value++;
                else if(results[i].colour=="orange") $scope.piechart[1].value++;
                else if(results[i].colour=="red") $scope.piechart[2].value++;
			}
		})
		$scope.barchart.labels = labs;
		$scope.barchart.datasets[0].data = marks;
        $scope.piechart = colours;
		$scope.showGraph = true;
	}

	$scope.addResults = function(id, lab_id, mark, colour)	{
		$scope.addResponse = Restangular.one("student", id).all("result").post("lab_id=" + lab_id +"&mark=" + mark + "&colour=" + colour)
	}

})