<?php

class logins {

    function addLogin($params){
        // Check if user can post passwords
       // global $userinfo;
        if ($_SESSION['addpassword']){ return false; }
        // Check if user is added to the group
        if ($params['group']){
            $userGroups = new userGroups;
            if (!$userGroups->checkGroupPerm(Accesscontrol::getInternalID(),$params['group'])){
                return false;
            }
            $groups = new groups;
            $groupinfo = $groups->getGroup($params['group']);
            if ($groupinfo['restrictByIP']){
                $accesscontrol = new accesscontrol;
                $groupips = $accesscontrol->getGroupIPs($params['group']);
                if (!in_array($_SESSION['ipaddress'],$groupips)){
                    return false;
                }
            }
            $groupid = $params['group'];
        } else {
            $groupid = 0;
        }

        if (!$params['loginname']){
            return false;
        }
        $loginname = stripslashes($params['loginname']);
        $url = stripslashes($params['url']);
        if ($url && (filter_var($url, FILTER_VALIDATE_URL))){
            $urlid = $this->checkOrAddURL($url);
        } else {
            $urlid = 0;
        }
        


        // Add password
        $password = new Password;
        $loginid = $password->StorePassword(Accesscontrol::getOrganisationID(),$groupid,$loginname,$urlid,$params['username'],$params['password'],$params['notes'],$params['preencrypt']);
        // Add labels
        return $loginid;
    }

    function checkOrAddURL($url){
        $loginsdb = new LoginsDB;
        $siteid = $loginsdb->getSiteId($url);
        if (!$siteid){
            $siteid = $loginsdb->addSite($url);
        }
        return $siteid;
    }

    function returnLogin($id,$alsopassword=false){
        //if ($_SESSION['getpassword']){ return false; }
        $password = new Password;
        $logininfo = $password->RetrieveLogin($id);
        if ($alsopassword){
            $alsopassword = $password->RetrievePassword($id);
            $logininfo['password'] = $alsopassword;
        }
        return $logininfo;
    }

    function returnPassword($loginid){
        //if ($_SESSION['getpassword']){ return false; }
        $password = new Password;
        return $password->RetrievePassword($loginid);
    }

    function returnCipheredLogin($loginid,$key){
        $password = new Password;
        return $password->RetrievePassword($loginid,$key);
    }

    function returnLoginsinGroup($groupid){
        // check group privs
        $password = new Password;
        $logins = $password->getGroupLogins($groupid);
        return $logins;
    }

    function returnAllLogins(){
        // check group privs
        $password = new Password;
        $logins = $password->returnAllLogins();
        return $logins;
    }

    function search($string){
        $password = new Password;
        $userGroups = new userGroups;
        $matches = $password->search($string);
        return $matches;
    }

    function exactsearch($string){
        $password = new Password;
        $userGroups = new userGroups;
        $mygroups = $userGroups->getGroupsArray($_SESSION['internalid']);
        $matches = $password->exactsearch($string);
        return $matches;
    }

    

    function returnLastAdded($number){
        function cmp($a, $b){
            return strcmp($b["dateAdded"], $a["dateAdded"]);
        }
        $logins = $this->returnAllLogins();
        usort($logins,'cmp');
        $lastadded = array();
        $x=0;
        foreach ($logins as $l){
            if ($x > $number) { continue; }
            $lastadded[] = $l;
            $x++;
        }
        return $lastadded;

    }



}

?>