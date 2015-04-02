<?php

/**
 * includes database class
 */
require_once 'db.php';


/**
 * Routes user actions
 *
 * @author alexman92
 */
class Router {
    
    
    public static function route($data) {
     
        switch($data['action']) {
            case 'enter': $out = self::enter($data); return $out; break;
            case 'register': $out = self::register($data); return $out; break;
            case 'save_list': $out = self::saveList($data); return $out; break;
            case 'remove_list': $out = self::removeList($data); return $out; break;
        }
    }
    
    private static function enter($data) {
     
        $login = $data['email'];
        $password = base64_encode($data['password']);
        $db = new DB(); 
        if(!$db){
            return $db->lastErrorMsg();
        }
        try {
           
            $user = $db->query(
                    "SELECT * FROM tUsers where login = '{$login}' and pass = '{$password}'"
                    );

                while($row_user = $user->fetchArray()) {
                    $user_flag = true;
                }
                if(!$user_flag) {
                    $login = '';
                    $result = 'error';
                    $description = 'No user with this login or password';
                } else {
                        
                    try {

                        $lists_arr = [];
                        $lists = $db->query(
                                "SELECT * FROM tUserLists where login = '{$login}'"
                                );
                            
                        while ($row = $lists->fetchArray()) {

                      
                                $lists_arr[$row['listname']][] = array(
                                    'taskname'=> $row['taskname'],
                                    'done' => $row['done'],
                                    );

                        } 
                        

                    } catch (Exception $e) {
                        $result = 'error';
                        //$description = 'Database error. Please contact us.';
                        $description = $db->lastErrorMsg();

                    }

                    $result = 'success';
                    $description = '';
                    $sessionId = uniqId(true);
                    setcookie("sid", $sessionId, time()+(3600*4)); // 4 hours
                }
            
            
        } catch (Exception $e) {
            $result = 'error';
            //$description = 'Database error. Please contact us.';
             $description = $db->lastErrorMsg();
    
        }
        
        return (
                 json_encode( array(
                    'result'=> $result, 
                    'description'=>$description, 
                    'login'=> $login, 
                    'sid'=>$sessionId,
                    'lists'=>$lists_arr)) 
                );
    }
    
    private static function register($data) {
        $login = $data['email'];
        $password = base64_encode($data['password']);

        try {
            $db = new DB();
            $db->query(
                    "INSERT INTO tUsers(login, pass) VALUES('{$login}', '{$password}')"
                    );
            $result = 'success';
            $description = '';
        } catch (Exception $e) {
            $result = 'error';
            $description = 'Database error. Please contact us.';
            
        }
        
         return ( json_encode( array('result'=> $result, 'description'=>$description) ) );
    }
    
    private static function saveList($data) {
     
         try {
            $db = new DB();
            $login = $data['login'];
            $listname = $data['title'];
            
            $db->query("DELETE FROM tUserLists WHERE listname = '{$listname}'"
            . "and login = '{$login}'");
                
            foreach($data['tasks'] as $key=>$value) {
                $done = $value['done'] == 'true' ? 1 : 0; 
                

                $db->query(
                        "INSERT INTO tUserLists(login, listname, taskname, done) VALUES(
                            '{$login}', '{$listname}', '{$value['taskname']}', '{$done}'
                        )"
                        );
            }
            $result = 'success';
            $description = '';
        } catch (Exception $e) {
            $result = 'error';
            $description = 'Database error. Please contact us.';

            
        }
        return ( json_encode( array('result'=> $result, 'description'=>$description ) ) );
    }
    
    private static function removeList($data) {
        $login = $data['login'];
        $listname = $data['listname'];
        
          try {
                    $dbHandler = new DB();

                    $db->query(
                            "DELETE FROM tUserLists where login = '{$login}' and listname = '{$listname}'"
                            );
                   
                    
                    $result ='success';
                    $description = '';

                } catch (Exception $e) {
                    $result = 'error';
                    $description = 'Database error. Please contact us.';

                }
                   
                return ( json_encode( array('result'=> $result, 'description'=>$description ) ) );
    }
    
}
