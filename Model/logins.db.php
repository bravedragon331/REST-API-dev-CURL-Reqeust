<?php

class LoginsDB {

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

    public function storeUser($organisationid,$groupid,$relid,$label,$string,$username,$notes,$encrypted=0){

        $sql = "INSERT INTO logins SET 
         `label` = :label,
         `organisationid` = :organisationid,
         `groupid` = :groupid,
         `relid` = :relid,
         `randomString` = :string,
         `username` = :username,
         `notes` = :notes,
         `preencrypt` = :encrypted,
         `dateAdded` = :dateAdded";
        $query = $this->db->prepare($sql);
        $query->bindValue(':label',$label);
        $query->bindValue(':relid',$relid);
        $query->bindValue(':string',$string);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupid',$groupid);
        $query->bindValue(':username',$username);
        $query->bindValue(':notes',$notes);
        $query->bindValue(':encrypted',$encrypted);
        $query->bindValue(':dateAdded',time());
         try {
                $query->execute();
                return $this->db->lastInsertId();
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function getLogin($loginid){
        $sql = "SELECT * FROM logins WHERE `loginid` = :loginid AND deleted = 0 LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->bindValue(':loginid',$loginid);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

    public function updatePreencrypt($loginid,$encrypt){
        $sql = "UPDATE logins SET preencrypt = :encrypt WHERE `loginid` = :loginid LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->bindValue(':loginid',$loginid);
        $query->bindValue(':encrypt',$encrypt);
        $query->execute();
        return true;
    }

    public function getSiteUrl($siteid){
        $sql = "SELECT siteurl FROM sites WHERE siteid = :siteid LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->bindValue(':siteid',$siteid);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result){
            return $result['siteurl'];
        }
        return false;
    }

    public function getSiteId($siteurl){
        $sql = "SELECT siteid FROM sites WHERE siteurl = :siteurl LIMIT 1";
        $query = $this->db->prepare($sql);
        $query->bindValue(':siteurl',$siteurl);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_ASSOC);
        if ($result){
            return $result['siteid'];
        }
        return false;
    }

    public function addSite($siteurl){
        $sql = "INSERT INTO sites SET siteurl = :siteurl";
        $query = $this->db->prepare($sql);
        $query->bindValue(':siteurl',$siteurl);
         try {
                $query->execute();
                return $this->db->lastInsertId();
            }
            catch (PDOException $e) {
                var_dump($e);
                return false;
            }
    }

    public function getGroupLogins($organisationid,$groupid){
        $sql = "SELECT * FROM logins WHERE `organisationid` = :organisationid AND groupid = :groupid AND deleted = 0";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':groupid',$groupid);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLoginsbySite($organisationid,$siteid){
        $sql = "SELECT * FROM logins WHERE `organisationid` = :organisationid AND relid = :siteid AND deleted = 0";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':siteid',$siteid);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search($organisationid,$string){
        $sql = "SELECT * FROM logins WHERE `organisationid` = :organisationid AND Label LIKE :string AND deleted = 0";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':string','%'.$string.'%');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function exactsearch($organisationid,$string){
        $sql = "SELECT * FROM logins WHERE `organisationid` = :organisationid AND Label LIKE :string AND deleted = 0";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':string',$string);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all($organisationid){
        $sql = "SELECT * FROM logins WHERE `organisationid` = :organisationid AND deleted = 0";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addLabel($loginid,$label,$organisationid){
        $sql = "INSERT INTO labels SET loginid = :loginid, label = :label, organisationid = :organisationid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':loginid',$loginid);
        $query->bindValue(':label',$label);
        $query->bindValue(':organisationid',$organisationid);
         try {
                $query->execute();
                return true;
            }
            catch (PDOException $e) {
                //var_dump($e);
                return false;
            }
            return false;
    }

    public function removeLabel($loginid,$label,$organisationid){
        $sql = "DELETE FROM labels WHERE loginid = :loginid AND label = :label AND organisationid = :organisationid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':loginid',$loginid);
        $query->bindValue(':label',$label);
        $query->bindValue(':organisationid',$organisationid);
         try {
                $query->execute();
                return true;
            }
            catch (PDOException $e) {
                //var_dump($e);
                return false;
            }
            return false;
    }

    public function returnLabels($organisationid){
        $sql = "SELECT DISTINCT label FROM labels WHERE organisationid = :organisationid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $labels = array();
        foreach ($result as $r){
            $labels[] = $r['label'];
        }
        return $labels;
    }

    public function returnLoginLabels($organisationid,$loginid){
        $sql = "SELECT DISTINCT label FROM labels WHERE organisationid = :organisationid AND loginid = :loginid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':loginid',$loginid);
        $query->execute();
        $result = $query->fetchAll(PDO::FETCH_ASSOC);
        $labels = array();
        foreach ($result as $r){
            $labels[] = $r['label'];
        }
        return $labels;
    }

    public function returnLoginsforLabel($organisationid,$label){
        $sql = "SELECT t1.loginid, t1.groupid, t1.relid, t1.username, t1.label, t1.preencrypt, t1.notes, t1.dateAdded FROM logins t1
        INNER JOIN labels t2 ON t1.loginid = t2.loginid
        WHERE t1.`organisationid` = :organisationid AND t2.label = :label AND deleted = 0";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':label',$label);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deletePassword($organisationid,$loginid){
        $sql = "UPDATE logins SET deleted = 1 WHERE organisationid = :organisationid AND loginid = :loginid";
        $query = $this->db->prepare($sql);
        $query->bindValue(':organisationid',$organisationid);
        $query->bindValue(':loginid',$loginid);
        $query->execute();
        return true;
    }

} 

?>

