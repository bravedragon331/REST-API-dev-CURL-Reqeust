<?php

class StructureDB {

    public function __construct()
        {
            /*
            $hostname = '35.187.37.102';
            $user = 'fixed-logins';
            $dbname = 'structure';
            $password = '9youhgbjl21dqw';
            */
            $hostname = 'localhost';
            $user = 'root';
            $dbname = 'structure';
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

    public function getOrganisationInfo($organisationid){

        $sql = "SELECT * FROM organisations WHERE organisationid = :organisationid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
         try {
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result;

            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function updateOrganisationName($organisationid,$newname){
        $sql = "UPDATE organisations SET organisationname = :name WHERE organisationid = :organisationid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':name',$newname);
         try {
                $query->execute();
                return true;

            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function getUsers($organisationid,$active="0"){
        $sql = "SELECT * FROM users WHERE organisationid = :organisationid";
        if ($active == 0) {
            $sql = $sql . " AND deactivated = 0"; 
        } else {
            $sql = $sql . " AND deactivated != 0"; 
        }
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
         try {
                $query->execute();
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;

            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function addUser($userid,$organisationid,$username,$email,$password,$timenow){
        $sql = "INSERT INTO users SET userid = :userid, organisationid = :organisationid, username = :username, email = :email, password = :password, dateCreated = :timenow";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':email',$email);
        $query->bindValue(':userid',$userid);
        $query->bindValue(':username',$username);
        $query->bindValue(':password',$password);
        $query->bindValue(':timenow',$timenow);
         try {
                $query->execute();
                return $this->db->lastInsertId();
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function updateUserPerms($organisationid,$userid,$permission,$value){
        $sql = "UPDATE users SET `" . ($permission) . "` = :value WHERE organisationid = :organisationid AND userid = :userid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        //$query->bindValue(':permission',$permission);
        $query->bindValue(':userid',$userid);
        $query->bindValue(':value',$value);
         try {
                $query->execute();
                return true;
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function getToken($token){
        $sql = "SELECT * FROM tokens WHERE token = :token ORDER BY tokenexpiry DESC LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->bindValue(':token',$token);
         try {
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result;

            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function addToken($userid,$internalid,$organisationid,$tokenexpiry,$ipaddress,$token){
        $sql = "INSERT INTO tokens SET
        userid = :userid,
        internalid = :internalid,
        organisationid = :organisationid,
        tokenexpiry = :tokenexpiry,
        ipaddress = :ipaddress,
        token = :token";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':internalid',$internalid);
        $query->bindValue(':userid',$userid);
        $query->bindValue(':tokenexpiry',$tokenexpiry);
        $query->bindValue(':ipaddress',$ipaddress);
        $query->bindValue(':token',$token);
        try {
                $query->execute();
                return $tokenexpiry;
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }

    }

    public function updateUserStatus($organisationid,$userid,$status){
        $sql = "UPDATE users SET `deactivated` = :status WHERE organisationid = :organisationid AND userid = :userid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':status',$status);
        $query->bindValue(':userid',$userid);
         try {
                $query->execute();
                return true;
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }



    public function getUserbyId($organisationid,$userid){
        $sql = "SELECT * FROM users WHERE 
        userid = :userid AND organisationid = :organisationid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':userid',$userid);
        try {
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result;

            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
        return false;
    }

    public function getUserbyInternalId($organisationid,$internalid){
        $sql = "SELECT * FROM users WHERE 
        internalid = :internalid AND organisationid = :organisationid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':internalid',$internalid);
        try {
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result;

            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
        return false;
    }

    public function getUserbyEmail($organisationid,$email){
        $sql = "SELECT username, userid, dateCreated FROM users WHERE 
        organisationid = :organisationid AND email = :email";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':email',$email);
        try {
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result;

            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
        return false;
    }

    public function getUserbyUsername($username){
        $sql = "SELECT * FROM users WHERE 
        username = :username LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->bindValue(':username',$username);
        try {
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result;
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
        return false;
    }

    public function getPassword($username){
        $sql = "SELECT password FROM users WHERE 
        username = :username AND deactivated = 0";
        $query = $this->db->prepare($sql);
        $query->bindValue(':username',$username);
        try {
                $query->execute();
                $result = $query->fetch(PDO::FETCH_ASSOC);
                return $result['password'];
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
        return false;
    }

    public function allowIP($category,$organisationid,$relid,$name,$ipaddress,$dateadded,$expiry=0){
        $sql = "INSERT INTO ipallows SET
            organisationid = :organisationid,
            category = :category,
            relid = :relid,
            name = :name,
            expiry = :expiry,
            ipaddress = :ipaddress,
            dateadded = :dateadded";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':relid',$relid);
        $query->bindValue(':category',$category);
        $query->bindValue(':name',$name);
        $query->bindValue(':ipaddress',$ipaddress);
        $query->bindValue(':dateadded',$dateadded);
        $query->bindValue(':expiry',$expiry);
        try {
                $query->execute();
                return $this->db->lastInsertId();
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
        return false;
    }

    public function getIPAllows($organisationid,$category,$relid,$status=0){
        $sql = "SELECT * FROM ipallows WHERE category = :category AND expiry < :timenow AND organisationid = :organisationid AND relid = :relid";
        if ($status == 0){
            $sql = $sql . " AND `status` = 0";
        } else {
            $sql = $sql . " AND `status` != 0";
        }
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':category',$category);
        $query->bindValue(':relid',$relid);
        $query->bindValue(':timenow',time());
        try {
                $query->execute();
                $result = $query->fetchAll(PDO::FETCH_ASSOC);
                return $result;
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
        return false;
    }

    public function removeIP($organisationid,$category,$relid,$ipaddress){
       $sql = "UPDATE ipallows SET status = :timenow WHERE category = :category AND organisationid = :organisationid AND relid = :relid AND ipaddress = :ipaddress";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':relid',$relid);
        $query->bindValue(':category',$category);
        $query->bindValue(':ipaddress',$ipaddress);
        $query->bindValue(':timenow',time());
        try {
                $query->execute();
                return true;
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
        return false;
    }

} 

?>

