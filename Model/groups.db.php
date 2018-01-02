<?php

class GroupsDB {

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

    public function getGroups($organisationid,$deactivated="0"){

        $sql = "SELECT * FROM groups WHERE organisationid = :organisationid";
        if ($deactivated ==  0){
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

    public function getGroup($organisationid,$groupid){

        $sql = "SELECT * FROM groups WHERE organisationid = :organisationid AND groupid = :groupid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupid',$groupid);
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

    public function getGroupbyName($organisationid,$name){
        $sql = "SELECT groupid FROM groups WHERE 
        organisationid = :organisationid AND groupname = :groupname";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupname',$name);
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

    public function getGroupbyID($organisationid,$groupid){
        $sql = "SELECT internalid, groupname FROM groups WHERE 
        organisationid = :organisationid AND groupid = :groupid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupid',$groupid);
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

    public function getGroupbyInternalID($internalid){
        $sql = "SELECT groupid, groupname FROM groups WHERE 
        internalid = :internalid";
        $query = $this->db->prepare($sql);
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

    public function addGroup($organisationid,$name,$hash,$dateCreated){
        $sql = "INSERT INTO groups SET 
        organisationid = :organisationid,
        groupname = :name,
        dateCreated = :dateCreated,
        groupid = :groupid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupid',$hash);
        $query->bindValue(':name',$name);
        $query->bindValue(':dateCreated',$dateCreated);
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

    public function removeGroup($organisationid,$groupid,$timeof){
        $sql = "UPDATE groups SET deactivated = :timeof WHERE organisationid = :organisationid AND groupid = :groupid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupid',$groupid);
        $query->bindValue(':timeof',$timeof);
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


    public function renameGroup($organisationid,$groupid,$groupname){
        $sql = "UPDATE groups SET groupname = :groupname WHERE organisationid = :organisationid AND groupid = :groupid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupid',$groupid);
        $query->bindValue(':groupname',$groupname);
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

    public function setRestriction($organisationid,$groupid,$setting){
        $sql = "UPDATE groups SET restrictByIP = :setting WHERE organisationid = :organisationid AND groupid = :groupid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupid',$groupid);
        $query->bindValue(':setting',$setting);
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

    public function addUsertoGroup($userid,$groupid,$time){
        $sql = "INSERT INTO userGroups SET groupid = :groupid, userid = :userid, dateAdded = :dateadded";
        $query = $this->db->prepare($sql);
        $query->bindValue(':userid',$userid);
        $query->bindValue(':groupid',$groupid);
        $query->bindValue(':dateadded',$time);
         try {
                $query->execute();
                return true;
            }
            catch (PDOException $e) {
                return false;
            }
        return false;
    }

    public function removeUserfromGroup($userid,$groupid){
        $sql = "DELETE FROM userGroups WHERE groupid = :groupid AND userid = :userid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':userid',$userid);
        $query->bindValue(':groupid',$groupid);
         try {
                $query->execute();
                return true;
            }
            catch (PDOException $e) {
                return false;
            }
        return false;
    }

    public function getUserGroups($userid){
        $sql = "SELECT * FROM userGroups WHERE userid = :userid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':userid',$userid);
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

} 

?>

