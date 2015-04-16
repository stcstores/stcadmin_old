<?php

function checkLogin($admin_required=false) {
    if (isset($_SESSION['username'])) {
        if (in_array($_SESSION['username'], getUsernames())) {
            if (isset($_SESSION['user_id'])) {
                if (in_array($_SESSION['user_id'], getUserIds())) {
                    if ($_SESSION['user_id'] == getUserId($_SESSION['username'])) {
                        if ($admin_required == false) {
                            return true;
                        } else {
                            if (isAdmin($_SESSION['username'])) {
                                return true;
                            } else {
                                header('Location:/no_admin.php');
                                exit();
                            }
                        }
                    }
                }
            }
        }
    }
    header('location:/logout.php');
    exit();
}

function isAdmin($username) {
    $database = new DatabaseConnection();
    $selectQuery = "SELECT username, admin FROM login;";
    $results = $database->selectQuery($selectQuery);
    foreach ($results as $result) {
        if ($result['username'] == $username) {
            if ($result['admin'] == '1') {
                return true;
            } else {
                return false;
            }
        }
    }
}

function isLoggedIn() {
    if (isset($_SESSION['username'])){
        if (isset($_SESSION['user_id'])) {
            if ($_SESSION['user_id'] == getUserId($_SESSION['username'])) {
                return true;
            }
        }
    }
    return false;
}

function getUserIds() {
    $database = new DatabaseConnection();
    $selectQuery = "SELECT user_id FROM login;";
    $results = $database->selectQuery($selectQuery);
    $userIds = array();
    foreach ($results as $result) {
        $userIds[] = $result['user_id'];
    }
    return $userIds;    
}

function getUsernames() {
    $database = new DatabaseConnection();
    $selectQuery = "SELECT username FROM login;";
    $results = $database->selectQuery($selectQuery);
    $userIds = array();
    foreach ($results as $result) {
        $userIds[] = $result['username'];
    }
    return $userIds;    
}

function addUser($username, $password, $admin) {
    if (!(in_array($username, getUsernames()))){
        $database = new DatabaseConnection();
        $hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
        
        $insertQuery = "INSERT INTO login (username, password_hash, admin) VALUES ('" . $username . "', '" . $hash . "', " . $admin . ");";
        echo $insertQuery;
        $database->insertQuery($insertQuery);
        return true;
    }
    
    return false;    
}

function getHash($username) {
    $database = new DatabaseConnection();
    $selectQuery = "SELECT * from login;";
    $results = $database->selectQuery($selectQuery);
    foreach($results as $result) {
        if ($result['username'] == $username) {
            return $result['password_hash'];
        }
    }
}

function getUserId($username) {
    $database = new DatabaseConnection();
    $selectQuery = "SELECT user_id, username from login;";
    $results = $database->selectQuery($selectQuery);
    foreach($results as $result) {
        if ($result['username'] == $username) {
            return $result['user_id'];
        }
    }
}

function login($username, $password) {
    if (in_array($username, getUsernames())){
        $hash = getHash($username);
        if (password_verify($password, $hash)) {
            //echo "<p>Password Verified</p>";
            $userId = getUserId($username);
            if (createLoginSession($userId, $username)) {
                return true;
            }
        }
    }
    return false;
}

function createLoginSession($userId, $username) {
    $_SESSION['username'] = $username;
    $_SESSION['user_id'] = $userId;
    $_SESSION['timeout'] = time() + 60*60*2;
    return true;
}

function getCurrentUsername() {
    return $_SESSION['username'];
}