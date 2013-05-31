<?php
require 'Slim/Slim.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$GLOBALS['debug'] = true;

$app->post('/auth', 'createSession');
$app->get('/auth', 'checkSession');

$app->get('/student', 'checkSession', 'getStudents');
$app->get('/student/:id', 'checkSession', 'getStudent');
$app->get('/student/:id/result', 'checkSession', 'getStudentResult');
$app->post('/student/:id/result', 'checkSession', 'addStudentResult');

$app->get('/lab', 'checkSession', 'getLabs');
$app->get('/lab/:id', 'checkSession', 'getLabResults');

$app->get('/result', 'checkSession', 'getAllResults');

$app->get('/group/:id', 'checkSession', 'getGroupResults');

$app->post('/admin/update', 'checkSession', 'updateAdminPassword');

$app->run();

function checkSession(){
    session_start();

    $session = ( isset($_SESSION['user']) ? $_SESSION['user'] : null ) || $GLOBALS['debug'];
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
            $_SESSION['user'] = $_POST['PHP_AUTH_USER'];
            echo '{"message":"User '.strtoupper($row->username).' logged in"}';
        } else {
            unset($_SESSION['user']);
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

function getStudentResult($id){
    $sql = sprintf("SELECT * FROM all_results WHERE student_id='%s'",$id);
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

function addStudentResult($id){
    if(isset($_POST['lab_id']) && isset($_POST['mark']) && isset($_POST['colour'])){
        $lab_id = $_POST['lab_id'];
        $mark = $_POST['mark'];
        $colour = $_POST['colour'];

        $sql = sprintf("INSERT INTO `spms`.`result` (`id`, `student_id`, `lab_id`, `mark`, `colour`) 
                        VALUES (NULL, '%s', '%s', '%s', '%s')", $id, $lab_id, $mark, $colour);

        $result = _addRow($sql);
        echo $result;
    } else {
        echo '{"error":{"text":"All required values not set, values required are: lab, mark, colour"}}';
    }
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
        echo ($i > 0 && $i<count($rows)) ? "," : "";
        getStudentResult($rows[$i]->student_id);
    }
}

function getGroupResults($id){
    $sql = sprintf("SELECT * FROM `all_results` WHERE `group` = '%s' order by student_id, lab_id", $id);
    $rows = _getRows($sql);
    echo _parseJSON($rows);
}

function updateAdminPassword(){

    if(isset($_POST['password'])){
        $username = $_SESSION['user'];
        $password = $_POST['password'];

        $sql = sprintf("UPDATE admin SET password='%s' WHERE username = '%' " , $password, $username);
        $result = _updateRow($sql);
        echo $result;
    } else {
        echo '{"error":{"text":"All required values not set, values required are: password"}}';
    }
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

function _addRow($sql){
    try {
        $db = _getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;

        return '{"message":"Row added successfully"}';

    } catch(PDOException $e) {
        return '{"error":{"text":'. $e->getMessage() .'}}';
    }
}

function _updateRow($sql){
    try {
        $db = _getConnection();
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $db = null;

        return '{"message":"Row updated successfully"}';

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