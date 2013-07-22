var spms = angular.module('spms', ['restangular']).config(function(RestangularProvider, $httpProvider){
		RestangularProvider.setBaseUrl('./router.php')
		$httpProvider.defaults.headers.post  = {'Content-Type': 'application/x-www-form-urlencoded'};
	})

//TODO -  split up controller logic into explicit controllers for their functions
spms.controller('SessionCtrl', function($scope, Restangular){

	$scope.templates = 
		[ { name: 'auth', url: 'partials/auth.html'}
		, { name: 'student-results', url: 'partials/student-results.html'} ]
	$scope.template = $scope.templates[0]

	$scope.createSession = function(username, password)	{
		Restangular.all("auth").post("PHP_AUTH_USER=" + username +"&PHP_AUTH_PW=" + password).then(function(result){
			if(result.valid === 'true') {
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
	}

	$scope.addResults = function(id, lab_id, mark, colour)	{
		$scope.addResponse = Restangular.one("student", id).all("result").post("lab_id=" + lab_id +"&mark=" + mark + "&colour=" + colour)
	}

})
