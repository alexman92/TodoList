<?php
/**
 * class for working with MySQL DB
 * (c) alexman92
 */
class DB {

    /**
     * connect to database
     * @param type $login
     * @param type $password
     * @throws Exception
     */
    static function connect($login, $password) {
     
        if(!mysql_connect('localhost', $login, $password)){
             throw new Exception(mysql_error());
        } 
        
        if(!mysql_select_db('mysql')) {
            throw new Exception(mysql_error());
        }

    }
    
    /**
     * makes query to db
     * @param type $query
     * @return type
     * @throws Exception
     */
    static function query($query) {
        $resultArr = array();
        mysql_query('SET NAMES UTF8');
        $resFromDb = mysql_query($query);
        if(!$resFromDb) {
            throw new Exception(mysql_error());
        } else {
            while($r = mysql_fetch_array($resFromDb)) {
                $resultArr[] = $r;
            }
        }
        
        return $resultArr;
    }
    
    /**
     * close database connection
     * @param type $db
     */
    static function close($db) {
        mysql_close($db);
    }

}
