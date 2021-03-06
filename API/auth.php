<?php

function auth_check($realm) {

    $_SESSION = array();
    session_unset();
    session_destroy();

    session_start();

    if(isset($_REQUEST['login']) && $_REQUEST['login'] != "" ||
       isset($_REQUEST['password']) && $_REQUEST['password'] != "")
    {    
        $userMngr = new User();
        $user = $userMngr->load(array('login'=>$_REQUEST['login']));
        
        if(!$user) {
            $GLOBALS["authError"] = "Utilisateur inconnu";
            return false;
        }
        
        if(sha1(KEY) == $_REQUEST['password']) {
            return $user;
        }
        else {
            $GLOBALS["authError"] = "Echec de l'authentification";
            return false;
        }
    }
    else
    {
        if(isset($_SERVER['PHP_AUTH_DIGEST'])) {
            $digest = $_SERVER['PHP_AUTH_DIGEST'];
        } else if(isset($_SERVER['HTTP_AUTHENTICATION']) &&
                  strpos(strtolower($_SERVER['HTTP_AUTHENTICATION']), "digest") == 0) {
            $digest = substr($_SERVER['HTTP_AUTHORIZATION'], 7);
        }
        
        if(!isset($digest)) {
            $GLOBALS["authError"] = "Digest vide";
            return false;
        }
        
        // check digest string
        $needed_parts = array('nonce'=>1, 'nc'=>1, 'cnonce'=>1, 'qop'=>1, 'username'=>1,'uri'=>1, 'response'=>1);
        $data = array();
        $keys = implode('|', array_keys($needed_parts));

        preg_match_all('@('.$keys.')=(?:([\'"])([^\2]+?)\2|([^\s,]+))@', $digest, $matches, PREG_SET_ORDER);

        foreach ($matches as $m) {
            $data[$m[1]] = $m[3] ? $m[3] : $m[4];
            unset($needed_parts[$m[1]]);
        }

        // digest string is not valid
        if(count($needed_parts) > 0) {
            $GLOBALS["authError"] = "Digest invalide";
            return false;
        }

        // user found ?
        $userMngr = new User();
        $user = $userMngr->load(array('login'=>$data['username']));
        
        if(!$user) {
            $GLOBALS["authError"] = "Utilisateur inconnu";
            return false;
        }
        
        // check credentials
        $A1 = md5($data['username'] . ':' . $realm . ':' . sha1(KEY));
        $A2 = md5($_SERVER['REQUEST_METHOD'].':'.$data['uri']);
        $resp = md5($A1.':'.$data['nonce'].':'.$data['nc'].':'.$data['cnonce'].':'.$data['qop'].':'.$A2);
        
        if($data['response'] != $resp) {
            $GLOBALS["authError"] = "Echec de l'authentification";
            return false;
        }
    }
    
    return $user;
}

function auth_request($realm) {
    header("WWW-Authenticate: Digest realm=\"$realm\",qop=\"auth\",nonce=\"".uniqid()."\",opaque=\"".session_id()."\"");
    header("HTTP/1.0 401 Unauthorized");
    echo "{\"error\":{\"id\":\"2\",\"message\":\"<h1>login failed</h1><p>".$GLOBALS["authError"]."</p>\"}}\n";

    $_SESSION = array();
    session_unset();
    session_destroy();
    
    die();
}

?>
