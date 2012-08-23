<?php

/**
 * DB.php
 *  Database connection class. Abstracts the DB interface in order to not worry about preparing MySQL string statements
 * 
 * @author David Woodruff <mustang2009cobra@gmail.com>
 * 
 * @copyright (C) 2012, David Woodruff
 * 
 * You may use, copy, modifiy, and distribute this file as desired as long as you
 *  give credit to the original authors.
 * 
 */
class DB {
    const PRODUCTION = FALSE; //Set to false on development environments!
    
    //Development DB credentials
    private $DEV_HOSTNAME = "localhost";
    private $DEV_DBNAME = "photo-manager"; //Application database
    private $DEV_USERNAME = "root";
    private $DEV_PASSWORD = "";
    
    //Production DB credentials
    private $PROD_HOSTNAME = "localhost";
    private $PROD_DBNAME = "dsw88_photo-manager";
    private $PROD_USERNAME = "dsw88_photo";
    private $PROD_PASSWORD = "R+ZISAVUd)ZE";
    
    private $dbh = null;
    
    public function __construct(){
        try{
            if(self::PRODUCTION){
                $this->dbh = new PDO("mysql:host=$this->PROD_HOSTNAME;dbname=$this->PROD_DBNAME", $this->PROD_USERNAME, $this->PROD_PASSWORD);
            }
            else{
                $this->dbh = new PDO("mysql:host=$this->DEV_HOSTNAME;dbname=$this->DEV_DBNAME", $this->DEV_USERNAME, $this->DEV_PASSWORD);
            }
        }
        catch(PDOException $e){
            print "DB connection error: " . $e->getMessage();
            die();
        }
    }
    
    /**
     * Takes a prepared MySQL statment and executes it it
     * 
     * @param string $statement A prepared MySQL statement
     */
    private function exec($statement){
        try{
            $count = $this->dbh->exec($statement);
            return $count;
        }
        catch(PDOException $e){
            print "Couldn't execute statement: " . $statement;
            die();
        }
    }
    
    /**
     * Takes a prepared MySQL query and runs it
     * 
     * @param string $query A prepared MySQL query
     */
    private function query($query){
        try{
            $resultSet = array();
            
            foreach($this->dbh->query($query) as $row){
                $resultSet[] = $row;
            }
            return $resultSet;
        }
        catch(PDOException $e){
            print "Couldn't execute query: " . $query;
            die();
        }
    }
    
    public function insertRow(){
        
    }
    
    /**
     * Selects all the matched rows from the given table
     * 
     * @param string $table The name of the table to query from
     * @param Array $rows An array of key/value pairs, where key is the field name, and value is the match data
     * 
     * @return Array an array containing the matched rows
     */
    public function selectRows($table, $rows){
        $selectStatement = "SELECT * FROM $table WHERE";
        foreach($rows as $key => $value){
            $selectStatement .= " $key='$value'";
        }
        return $this->query($selectStatement);
    }
    
    /**
     * Selects all the rows from the given table
     * 
     * @param string $table The name of the table to query from
     * 
     * @return Array an array containing the matched rows
     */
    public function selectAllRows($table){
        $selectStatement = "SELECT * FROM $table";
        return $this->query($selectStatement);
    }
    
    /**
     * Updates the matched rows in the table with the field updates
     * 
     * @param string $table The name of the table to query from
     * @param Array $query An array of key/value pairs, where key is the field name, and value is the match data
     * @param Array $updates An array of key/value pairs, where key is the field name, and value is the new data
     * 
     * @return int The number of rows affected by the update
     */
    public function update($table, $updates, $query = array()){
        $updateStatement = "UPDATE $table SET";
        foreach($updates as $key => $value){
            $updateStatement .= " $key=$value,";
        }
        $updateStatement = substr($insertStatement, 0, -2); //Remove the last comma
        if(count($query) > 0){
            $updateStatement .= " WHERE";
            foreach($query as $key => $value){
                $updateStatement .= " $key=$value,";
            }
        }
        $updateStatement = substr($insertStatement, 0, -2); //Remove the last comma
        
        return $this->exec($updateStatement);
    }
    
    /**
     * Inserts a new row into the given table, using the given key/value pairs of data to insert
     * 
     * @param string $table The name of the table to insert the data in
     * @param Array $values An array of key/value pairs, where key is the field name, and value is the match data
     * 
     * @return int The number of rows affected by the update (should be 1 if everything goes well)
     */
    public function insert($table, $values){
        $insertStatement = "INSERT INTO $table(";
        $fieldNames = array();
        $fieldValues = array();
        foreach($values as $key => $value){
            $fieldNames[] = $key;
            $fieldValues[] = $value;
        }
        
        foreach($fieldNames as $name){
            $insertStatement .= "$name, ";
        }
        $insertStatement = substr($insertStatement, 0, -2);
        
        $insertStatement .= ") VALUES (";
        foreach($fieldValues as $value){
            $insertStatement .= "'$value', ";
        }
        $insertStatement = substr($insertStatement, 0, -2);
        $insertStatement .= ")";
        
        return $this->exec($insertStatement);
    }
    
    /*
    public function delete($table, $values){
        $deleteStatement = "DELETE FROM $table WHERE";
        foreach($updates as $key => $value){
            $updateStatement .= " $key=$value";
        }
        
        return $this->exec($updateStatement);
    }
     * 
     */
    
}

?>
