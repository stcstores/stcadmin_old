<?php
namespace STCAdmin;

class UserLogin {

    public function checkLogin($admin_required = false)
    {
        $userID = UserLogin::getUserId($_SESSION['username'], $_SESSION['password']);
        if ($userID == '8aa976fb-f6aa-4899-a1da-07662ab5ba56') {
            return true;
            if ($_SESSION['timeout'] > time()) {
                return true;
            }
        }
        header('location:/logout.php');
        exit();
    }

    public function login($username, $password)
    {
        $userID = UserLogin::getUserId($username, $password);
        if ($userID == '8aa976fb-f6aa-4899-a1da-07662ab5ba56') {
            UserLogin::createLoginSession($username, $password);
            return true;
        }
        return false;
    }

    public function isLoggedIn()
    {
        if (isset($_SESSION['username'])) {
            if (isset($_SESSION['password'])) {
                return true;
            }
        }
        return false;
    }

    private function getUserId($username, $password)
    {
        $loginURL = 'https://api.linnworks.net/api/Auth/Multilogin';
        $authURL = 'https://api.linnworks.net/api/Auth/Authorize';
        $data = array('userName' => $username, 'password' => $password);
        $multiLogin = UserLogin::makeRequest($loginURL, $data);
        $userID = $multiLogin[0]['Id'];
        return $userID;
    }

    private function makeRequest($url, $data)
    {
        $curl = curl_init();
        $headers = array(
            'Content-Type: application/json',
        );
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt(
            $curl,
            CURLOPT_CAINFO,
            dirname($_SERVER['DOCUMENT_ROOT']) . '/private/lib/LinnworksAPI/thawtePrimaryRootCA.crt'
        );
        $dataString = http_build_query($data);
        curl_setopt($curl, CURLOPT_URL, $url . '?' . $dataString);
        echo curl_error($curl);
        $response = curl_exec($curl);
        $response = json_decode($response, true);
        return $response;
    }

    private function createLoginSession($username, $password)
    {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['timeout'] = time() + 60*60*2;
        return true;
    }

    public function getCurrentUsername()
    {
        return $_SESSION['username'];
    }

    public function userExists($username)
    {
        if (in_array($username, UserLogin::getUsernames())) {
            return true;
        }
        return false;
    }
}
