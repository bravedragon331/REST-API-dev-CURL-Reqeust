<?php

class HashDB {

	public function __construct()
    {
        /*
        $hostname = '35.187.37.102';
        $user = 'fixed-logins';
        $dbname = 'logins_db1';
        $password = '9youhgbjl21dqw';
        */
        
        $hostname = 'localhost';
        $user = 'root';
        $dbname = 'logins_db1';
        $password = '';
        
        

        $datasource = 'mysql:host=' . $hostname . ';dbname=' . $dbname;
        try {
                $this->db = new PDO($datasource, $user, $password);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
                echo $e->getMessage();
                die;
        }
    }

    public function storeHash($loginid,$hash,$iv,$passwordid){
        $sql = "INSERT INTO hashes SET 
        `loginid` = :loginid,
        `hash` = :hash,
        `iv` = :iv,
        `passwordid` = :passwordid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':loginid',$loginid);
        $query->bindValue(':hash',$hash);
        $query->bindValue(':passwordid',$passwordid);
        $query->bindValue(':iv',$iv);
        try {
                $query->execute();
                return $this->db->lastInsertId();
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function getHash($loginid){
        $sql = "SELECT * FROM hashes WHERE `loginid` = :loginid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':loginid',$loginid);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }
	
} 

?>

