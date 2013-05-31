var spms = angular.module('spms', ['restangular']).config(function(RestangularProvider, $httpProvider){
		RestangularProvider.setBaseUrl('./router.php')
		$httpProvider.defaults.headers.post  = {'Content-Type': 'application/x-www-form-urlencoded'};
	})

spms.controller('RESTCtrl', function($scope, Restangular){

	$scope.createSession = function(username, password)	{
		if(username && password)
			$scope.userSession = Restangular.all("auth").post("PHP_AUTH_USER=" + username +"&PHP_AUTH_PW=" + password)
		else
			$scope.userSession = Restangular.all("auth").post("PHP_AUTH_USER=&PHP_AUTH_PW=")
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
	}

	$scope.addResults = function(id, lab_id, mark, colour)	{
		$scope.addResponse = Restangular.one("student", id).all("result").post("lab_id=" + lab_id +"&mark=" + mark + "&colour=" + colour)
	}

})
