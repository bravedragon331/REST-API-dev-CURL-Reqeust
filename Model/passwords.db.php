<?php

class PasswordDB {

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

    public function storePass($loginid,$password){
        $sql = "INSERT INTO passwords SET 
        `loginid` = :loginid,
        `password` = :password";
        $query = $this->db->prepare($sql);
        $query->bindValue(':loginid',$loginid);
        $query->bindValue(':password',$password);
        try {
                $query->execute();
                return $this->db->lastInsertId();
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function getPassword($passwordid){
        $sql = "SELECT password FROM passwords WHERE `loginid` = :passwordid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':passwordid',$passwordid);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        return $result['password'];
    }
	
} 

?>

