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

        try {
                 
            $dbHandler = DB::connect('root', '000000');
            $user = DB::query(
                    "SELECT * FROM tUsers where login = '{$login}' and pass = '{$password}'"
                    );
            if(!isset($user) || (count($user) === 0)) {
                $login = '';
                $result = 'error';
                $description = 'No user with this login or password';
            } else {
                
                try {

                    $lists_arr = [];
                    $lists = DB::query(
                            "SELECT * FROM tUserLists where login = '{$login}'"
                            );
                    DB::close($dbHandler);
                    if(isset($lists) || (count($lists) > 0)) {
                        
                        foreach($lists as $task => $task_param) {
                            $lists_arr[$task_param['listname']][] = array(
                                'taskname'=> $task_param['taskname'],
                                'done' => $task_param['done'],
                                );
                        }
                        
                    } 

                } catch (Exception $e) {
                    $result = 'error';
                    $description = 'Database error. Please contact us.';

                }

                $result = 'success';
                $description = '';
                $sessionId = uniqId(true);
                
            }
            
        } catch (Exception $e) {
            $result = 'error';
            $description = 'Database error. Please contact us.';
    
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
            $dbHandler = DB::connect('root', '000000');
            DB::query(
                    "INSERT INTO tUsers(login, pass) VALUES('{$login}', '{$password}')"
                    );
            DB::close($dbHandler);
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
            $dbHandler = DB::connect('root', '000000');
            $login = $data['login'];
            $listname = $data['title'];
            foreach($data['tasks'] as $key=>$value) {
                $done = $value['done'] === 'true' ? 1 : 0;  
                DB::query(
                        "INSERT INTO tUserLists VALUES(
                            '{$login}', '{$listname}', '{$value['taskname']}', '{$done}'
                        )"
                        );
            }
            DB::close($dbHandler);
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
                    $dbHandler = DB::connect('root', '000000');

                    DB::query(
                            "DELETE FROM tUserLists where login = '{$login}' and listname = '{$listname}'"
                            );
                    DB::close($dbHandler);
                    
                    $result ='success';
                    $description = '';

                } catch (Exception $e) {
                    $result = 'error';
                    $description = 'Database error. Please contact us.';

                }
                   
                return ( json_encode( array('result'=> $result, 'description'=>$description ) ) );
    }
    
}
