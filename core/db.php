<?php 
/**
 * class for working with MySQL DB
 * (c) alexman92
 */
class DB  extends SQLite3{

      public function __construct()
      { 
         
         $this->enableExceptions(true);
         
        try
        {
            parent::__construct('my_db.db', SQLITE3_OPEN_READWRITE );
        }
        catch(Exception $ex) { die( $ex->getMessage() ); }
        

    
      }

}
