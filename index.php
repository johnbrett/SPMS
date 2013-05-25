<?php
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$GLOBALS['debug'] = true;

$app->post('/auth', 'createSession');
$app->get('/auth', 'checkSession');

$app->get('/students', 'checkSession', 'getStudents');
$app->get('/students/:id', 'checkSession', 'getStudent');
$app->get('/students/:id/results', 'checkSession', 'getStudentResults');

$app->get('/labs', 'checkSession', 'getLabs');
$app->get('/labs/:id', 'checkSession', 'getLabResults');

$app->get('/results', 'checkSession', 'getAllResults');

$app->run();

function checkSession(){
    session_start();

    $session = ( isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : null ) || $GLOBALS['debug'];
    if(!$session){
        die('{"error":{"text":"User is not logged in"}}');
    }
}

function createSession(){
    session_start();
    if(isset($_POST['PHP_AUTH_USER']) && isset($_POST['PHP_AUTH_PW'])){
        $sql = sprintf("SELECT username, password FROM admin WHERE username='%s' AND password='%s'", $_POST['PHP_AUTH_USER'], $_POST['PHP_AUTH_PW']);
        $row = _getRow($sql);
        
        if($row){
            $_SESSION['logged_in'] = true;
            echo '{"message":"User '.strtoupper($row->username).' logged in"}';
        } else {
            unset($_SESSION['logged_in']);
            echo '{"message":"User logged out"}';
        }
    } else {
        die('{"error":{"text":"Either no username or password specified"}}');
    }
}

function getStudents() {
    $sql = sprintf("SELECT * FROM all_students");
    $rows = _getRows($sql);
    echo _parseJSON($rows);
}

function getStudent($id) {
    $sql = sprintf("SELECT * FROM all_students WHERE id='%s'",$id);
    $row = _getRow($sql);
    echo _parseJSON($row);
}

function getStudentResults($id){
    $sql = sprintf("SELECT * FROM all_results WHERE student_id='%s'",$id);
    $rows = _getRows($sql);

    $results = array();
    for($i=0; $i<count($rows); $i++){
        $results[] = array(
            "mark"=>$rows[$i]->mark,
             "colour"=>$rows[$i]->colour
             );
    }
    $return = array('student_id'=>$id, 'results'=>$results);
    echo _parseJSON($return);
}

function getLabs(){
    $sql = sprintf("SELECT id, start_date, finish_date FROM lab");
    $rows = _getRows($sql);
    echo _parseJSON($rows);
}

function getLabResults($id){

    $sql = sprintf("SELECT * FROM all_results WHERE lab_id='%s'",$id);
    $rows = _getRows($sql);

    $results = array();
    for($i=0; $i<count($rows); $i++){
        $results[] = array(
            "student_id"=>$rows[$i]->student_id,
            "mark"=>$rows[$i]->mark,
             "colour"=>$rows[$i]->colour
             );
    } 
    echo _parseJSON($results);
}

function getAllResults(){
    $sql = sprintf("SELECT DISTINCT student_id FROM `result` ORDER BY student_id ASC");
    $rows = _getRows($sql);

    $results = "";
    for($i=0; $i<count($rows); $i++){
        $results .= getStudentResults($rows[$i]->student_id);
    }
    echo $results;
}



function _getRow($sql){
    try {
        $db = _getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchObject();
        $db = null;

        return $result;

    } catch(PDOException $e) {
        return '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function _getRows($sql){
    try {
        $db = _getConnection();
        $stmt = $db->query($sql);
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;

        return $result;

    } catch(PDOException $e) {
        return '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function _parseJSON($array){
    // Include support for JSONP requests
    if (!isset($_GET['callback'])) {
        return json_encode($array);
    } else {
        return $_GET['callback'] . '(' . json_encode($array) . ');';
    }
}

function _getConnection() {
    $dbhost="127.0.0.1";
    $dbuser="root";
    $dbpass="";
    $dbname="spms";
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);  
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}