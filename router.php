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

$app->get('/group/:id', 'checkSession', 'getGroup');
$app->get('/group/:id/result', 'checkSession', 'getGroupResults');

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
            echo '{"valid":"true","message":"User '.strtoupper($row->username).' logged in"}';
        } else if ($GLOBALS['debug']) {
            echo '{"valid":"true","message":"User ADMIN logged in"}';
        }  else {
            unset($_SESSION['user']);
            echo '{"valid":"false","message":"User logged out"}';
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
    $sql = sprintf("SELECT lab_id, mark, colour FROM result r RIGHT OUTER JOIN lab l on r.lab_id = l.id and student_id='%s'",$id); // OUTER Needed to give empty lab results
    $rows = _getRows($sql);
    echo _parseJSON($rows);
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
    $sql = sprintf("SELECT student_id, mark, colour FROM all_results WHERE lab_id='%s'",$id);
    $rows = _getRows($sql);
    echo _parseJSON($rows);
}

function getAllResults(){
    $sql = sprintf("SELECT * FROM all_results ORDER BY student_id ASC");
    $rows = _getRows($sql);
    echo _parseJSON($rows);
}

function getGroup($id){
    $sql = sprintf("SELECT * FROM `all_students` WHERE `group` = '%s'", $id);
    $rows = _getRows($sql);
    echo _parseJSON($rows);
}

function getGroupResults($id){
    $sql = sprintf("SELECT student_id, lab_id, mark, colour FROM `all_results` r RIGHT OUTER JOIN lab l on r.lab_id = l.id WHERE `group` = '%s' order by student_id, lab_id", $id);
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
        return '{"error":{"text":"'.$e->getMessage().'"}}';
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
        return '{"error":{"text":"'.$e->getMessage().'"}}';
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
        return '{"error":{"text":"'.$e->getMessage().'"}}';
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
        return '{"error":{"text":"'.$e->getMessage().'"}}';
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
    $db = parse_ini_file('config.ini');
    $dbhost=$db['dbhost'];
    $dbuser=$db['dbuser'];
    $dbpass=$db['dbpass'];
    $dbname=$db['dbname'];
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}