<?php
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

$app->get('/students', 'getStudents');
$app->get('/students/:id', 'getStudent');
//$app->get('/students/:id/reports', 'getReports');
$app->post('/students/:id', 'updateStudent');

$app->run();

function updateStudent($id) {
    
    if (isset($_POST['data'])){
        $data = json_decode($_POST['data']);

        if(json_last_error()==0){

            try {

                $name = $data->name;
                $sql = sprintf("update student set firstName='%s' where id='%s'", $name, $id);
                $db = getConnection();
                $stmt = $db->query($sql);
                $db = null;

                echo getStudent($id);
                

            } catch(PDOException $e) {
                echo '{"error":{"text":'. $e->getMessage() .'}}';
            } catch(Exception $e) {
                echo '{"error":{"text":"name undefined"}}';
            }

        } else {
            echo '{"error":{"text":"Encountered Bad JSON"}}';
        }
    } else {
        echo '{"error":{"text":"Data Empty"}}';
    }
}

function getStudents() {

    if (isset($_GET['name'])) {
        return getStudentsByName($_GET['name']);
    }

    $sql = "select id, firstName, lastName " .
            "from employee " .
            "group by id order by lastName, firstName";
    try {
        $db = getConnection();
        $stmt = $db->query($sql);
        $students = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        // Include support for JSONP requests
        if (!isset($_GET['callback'])) {
            echo json_encode($students);
        } else {
            echo $_GET['callback'] . '(' . json_encode($students) . ');';
        }

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
    echo "go";
}

function getStudent($id) {

    $sql = "select * " .
            "from student " .
            "where id=:id";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $student = $stmt->fetchObject();
        $db = null;

        // Include support for JSONP requests
        if (!isset($_GET['callback'])) {
            echo json_encode($student);
        } else {
            echo $_GET['callback'] . '(' . json_encode($student) . ');';
        }

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

/*
function getReports($id) {

    $sql = "select e.id, e.firstName, e.lastName, e.title, count(r.id) reportCount " .
            "from employee e left join employee r on r.managerId = e.id " .
            "where e.managerId=:id " .
            "group by e.id order by e.lastName, e.firstName";

    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $stmt->bindParam("id", $id);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        // Include support for JSONP requests
        if (!isset($_GET['callback'])) {
            echo json_encode($students);
        } else {
            echo $_GET['callback'] . '(' . json_encode($students) . ');';
        }

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}';
    }
}
*/

function getStudentsByName($name) {
    $sql = "select id, firstName, lastName " .
            "from employee " .
            "WHERE UPPER(CONCAT(firstName, ' ', lastName)) LIKE :name " .
            "group by id order by lastName, firstName";
    try {
        $db = getConnection();
        $stmt = $db->prepare($sql);
        $name = "%".$name."%";
        $stmt->bindParam("name", $name);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        // Include support for JSONP requests
        if (!isset($_GET['callback'])) {
            echo json_encode($students);
        } else {
            echo $_GET['callback'] . '(' . json_encode($students) . ');';
        }

    } catch(PDOException $e) {
        echo '{"error":{"text":'. $e->getMessage() .'}}'; 
    }
}

function getConnection() {
    $dbhost="127.0.0.1";
    $dbuser="root";
    $dbpass="";
    $dbname="directory";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);  
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}