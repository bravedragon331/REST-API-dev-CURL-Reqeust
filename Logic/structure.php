<?php

class structure {

    public function authenticate($user,$password){
        $hash = $this->getPassword($user);
        if (!password_verify($password,$hash)){
            return false;
        }
        $userinfo = $this->getUserbyUsername($user);
        $accesscontrol = new Accesscontrol;
        $token = $accesscontrol->login($userinfo);
        return $token;
    }

    public function possiblePerms(){
         return array('manageusers','addpasswords','deletepasswords','changepasswords','edittags','editgroups');
    }

    public function getOrganisationInfo(){
        $organisationid = 1;
        $structuredb = new StructureDB;
        $organisationinfo = $structuredb->getOrganisationInfo($organisationid);
        if (!$organisationinfo) { return false; }
        $output = array();
        $output['Name'] = $organisationinfo['organisationname'];
        $output['dateCreated'] = $organisationinfo['dateCreated'];
        return $output;
    }

    public function updateOrganisationName($newname){
        $organisationid = 1;
        $structuredb = new StructureDB;
        // Check user perms first
        if ($structuredb->updateOrganisationName($organisationid,$newname)){
            return $this->getOrganisationInfo();
        }
        return false;
    }

    public function getUsers(){
        $organisationid = 1;
        $structuredb = new StructureDB;
        $users = $structuredb->getUsers($organisationid);
        $newusers = array();
        $possibleperms = $this->possiblePerms();
        foreach ($users as $u){
            $u2 = array();
            $u2['userid']= $u['userid'];
            $u2['username']= $u['username'];
            $u2['email'] = $u['email'];
            $u2['dateCreated'] = $u['dateCreated'];
            $u2['dateFormatted'] = date("Y-m-d",$u['dateCreated']);
            foreach ($possibleperms as $p){
                $u2[$p] = $u[$p];
            }
            $newusers[]= $u2;
        }
        return $newusers;
    }

    public function getDeactivatedUsers(){
        $organisationid = 1;
        $structuredb = new StructureDB;
        $users = $structuredb->getUsers($organisationid,1);
        $newusers = array();
        $possibleperms = $this->possiblePerms();
        $x = 0;
        foreach ($users as $u){
            $x++;
            $u2 = array();
            $u2['userid']= $u['userid'];
            $u2['deactivated'] = $u['deactivated'];
            $u2['deactivatedFormat'] = date("Y-m-d",$u['deactivated']);
            $u2['username']= $u['username'];
            $u2['email'] = $u['email'];
            $u2['dateCreated'] = $u['dateCreated'];
            $u2['dateFormatted'] = date("Y-m-d",$u['dateCreated']);
            foreach ($possibleperms as $p){
                $u2[$p] = $u[$p];
            }
            $newusers[]= $u2;
        }
        if ($x > 0){
            return $newusers;
        }
        return "none";
    }

    public function getUser($userid){
        $userinfo = $this->getUserbyId($userid);
        if (!$userinfo) { return false; }
        $possibleperms = $this->possiblePerms();
        $user = array();
        $user['userid'] = $userinfo['userid'];
        $user['username'] = $userinfo['username'];
        $user['email'] = $userinfo['email'];
        $user['dateCreated'] = $userinfo['dateCreated'];
        $user['dateFormatted'] = date("Y-m-d",$userinfo['dateCreated']);
        if ($userinfo['deactivated']){
            $user['deactivated'] = $userinfo['deactivated'];
            $user['deactivatedFormat'] = date("Y-m-d",$userinfo['deactivated']);
        }
        foreach ($possibleperms as $p){
                $user[$p] = $userinfo[$p];
            }
        return $user;
    }

    public function getMyUser(){
        $userid = Accesscontrol::getUserID();
        return $this->getUser($userid);
    }

    private function getUserbyEmail($email){
        $organisationid = 1;
        $structuredb = new StructureDB;
        $user = $structuredb->getUserbyEmail($organisationid,$email);
        return $user;
    }

    private function getUserbyUsername($username){
        $structuredb = new StructureDB;
        $user = $structuredb->getUserbyUsername($username);
        return $user;
    }

    private function getPassword($username){
        $structuredb = new StructureDB;
        $password = $structuredb->getPassword($username);
        return $password;
    }

    public function getUserbyID($userid){
        $organisationid = 1; 
        $structuredb = new StructureDB;
        $user = $structuredb->getUserbyID($organisationid,$userid);
        return $user;
    }

    public function getUserbyInternalID($internalid){
        $organisationid = 1; 
        $structuredb = new StructureDB;
        $user = $structuredb->getUserbyInternalID($organisationid,$internalid);
        return $user;
    }

    private function generateHash(){
        $length = 15;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $userid = '';
        for ($i = 0; $i < $length; $i++) {
            $userid .= $characters[rand(0, $charactersLength - 1)];
        }
        if ($this->getUserbyID($userid)){
            return $this->generateHash();
        }
        return $userid;
    }

    private function setPermissions($userid,$permission,$value){
        if ($value) { $value = true; } else {
            $value = 0;
        }
        $organisationid = 1;
        $possibleperms = $this->possiblePerms();
        if (!in_array($permission,$possibleperms)){ return false; }
        $structuredb = new StructureDB;
        $structuredb->updateUserPerms($organisationid,$userid,$permission,$value);
        return true;
    }

    public function deactivateUser($userid){
        $organisationid = 1;
        $structuredb = new StructureDB;
        $status = time();
        $user = $structuredb->updateUserStatus($organisationid,$userid,$status);
        if ($user){
            return true;
        }
        return false;
    }

    public function reactivateUser($userid){
        $organisationid = 1;
        $structuredb = new StructureDB;
        $status = 0;
        $user = $structuredb->updateUserStatus($organisationid,$userid,$status);
        if ($user){
            return true;
        }
        return false;
    }

    public function filterPerms($userid,$fields){
        $possibleperms = $this->possiblePerms();
        foreach ($possibleperms as $p){ 
            if ($fields[$p] == 'on'){ 
                $this->setPermissions($userid,$p,true); 
            } else {
                $this->setPermissions($userid,$p,false); 
            }
        }
        return true;
    }

    public function addUser($username,$email,$password){
        $time = time();
        $organisationid = 1;
        if ((strlen($username)) < 3) { return false; }
        if ((strlen($email)) < 3) { return false; }
        if ((strlen($password)) < 3) { return false; }

        $password = password_hash($password,PASSWORD_DEFAULT);
        // Check if email exists for this org
        if ($this->getUserbyEmail($email)){
            return false;
        }
        if ($this->getUserbyUsername($username)){
            return false;
        }
        $hashid = $this->generateHash(); 
        $structuredb = new StructureDB;
        $userid = $structuredb->addUser($hashid,$organisationid,$username,$email,$password,$time);
       // echo $userid; die;
        $userinfo = $this->getUserbyInternalID($userid);
        return $userinfo;
        // Check if username exists for this org

    }

    

}

?>