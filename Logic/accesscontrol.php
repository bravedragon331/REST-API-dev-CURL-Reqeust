<?php

class Accesscontrol/* extends AbstractMiddleware*/{
    public static $token = null;

    public static function authenticate($user,$password){
        $hash = self::getPassword($user);
        if (!password_verify($password,$hash)){
            return false;
        }
        $userinfo = Accesscontrol::getUserbyUsername($user);    
        //$accesscontrol = new Accesscontrol;
        self::$token = Accesscontrol:: login($userinfo);
        //self::$token = self::generateToken();
        return self::$token;
    }

    public function login($userinfo){
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $organisationips = Accesscontrol::getOrganisationIPs($userinfo['organisationid']);
        $userips = Accesscontrol::getUserIPs($userinfo['userid']);
        $uips = array();
        foreach ($userips as $uip){
            $uips[] = $uip['ipaddress'];
        }
        $oips = array();
        foreach ($organisationips as $oip){
            $oips[] = $oip['ipaddress'];
            
        }        

        if ((in_array('%',$oips)) || (in_array($ipaddress,$oips))){
            if ((in_array('%',$uips)) || (in_array($ipaddress,$uips))){
                $structuredb = new StructureDB;
                $tokenexpiry = time() + 3600; // 1hr
                $token = Accesscontrol::generateToken();
                $expiry = $structuredb->addToken($userinfo['userid'],$userinfo['internalid'],$userinfo['organisationid'],$tokenexpiry,$ipaddress,$token);
                if ($expiry){
                    $perms = Structure::possiblePerms();
                   
                    return $token;
                }
                return 500;
            }
        }
        return 403;
    }

    static function getPassword($username){
        $structuredb = new StructureDB;
        $password = $structuredb->getPassword($username);
        return $password;
    }

    function getUserbyUsername($username){
        $organisationid = 1;
        $structuredb = new StructureDB;
        $user = $structuredb->getUserbyUsername($username);
        return $user;
    }

    public function isAllowed(){
        if(self::$token != null) return true;
        else return;
    }

    public function getOrganisationID(){
        $structuredb = new StructureDB;
        $tokeninfo = $structuredb->getToken(self::$token);
        $organiastionid = $tokeninfo['organisationid'];            
        return $organiastionid;
    }

    public function getInternalID(){
        $structuredb = new StructureDB;
        $tokeninfo = $structuredb->getToken(self::$token);
        $internalid = $tokeninfo['internalid'];            
        return $internalid;
    }

    public function getUserID(){
        $structuredb = new StructureDB;
        $tokeninfo = $structuredb->getToken(self::$token);
        $userid = $tokeninfo['userid'];            
        return $userid;
    }

    public static function getToken(){
        $structuredb = new StructureDB;
        $tokeninfo = $structuredb->getToken(self::$token);
        $ret = $tokeninfo['token'];            
        return $ret;
    }

    public function getTokenExpiry(){
        $structuredb = new StructureDB;
        $tokeninfo = $structuredb->getToken(self::$token);
        $tokenexpiry = $tokeninfo['tokenexpiry'];            
        return $tokenexpiry;
    }

    public function getPermission($permission){
        if (in_array($permission,$this->possiblePerms)){
            return $this->$permission;
        }
        return false;
    }


    public function isTokenValid($token){
        $structuredb = new StructureDB;
        $tokeninfo = $structuredb->getToken($token);
        if ($tokeninfo){
            /*
            $perms = $this->possiblePerms();
            $this->token = $tokeninfo['token'];
            $this->organiastionid = $tokeninfo['organisationid'];
            $this->internalid = $tokeninfo['internalid'];
            $this->userid = $tokeninfo['userid'];
            $this->tokenexpiry = $tokeninfo['tokenexpiry'];
            foreach ($perms as $p){
                $this->$p = $tokeninfo[$p];
            }
            */

            self::$token = $tokeninfo['token'];
            return $tokeninfo;
        }
        return false;
    }

    private function possiblePerms(){
        return array('manageusers','addpasswords','deletepasswords','changepasswords','edittags','editgroups');
    }

    public function checkPermissions($action = null,$relid=false){
        // Every action runs through this function
        return false;
    }

    public function generateToken(){
        $length = 15;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $hash = '';
        for ($i = 0; $i < $length; $i++) {
            $hash .= $characters[rand(0, $charactersLength - 1)];
        }
        return $hash;
    }

    public function allowIPforUser($userid,$ipaddress,$name,$seconds){
        $organisationid = 1;
        $structuredb = new StructureDB;
        if ($seconds){
            $expiry = time() + $seconds;
        } else {
            $expiry = 0;
        }
        if ($structuredb->allowIP("user",$organisationid,$userid,$name,$ipaddress,time(),$expiry)){
            return $this->getUserIPs($userid);
        }
    }

    public function allowIPforOrganisation($ipaddress,$name,$seconds){
        $organisationid = 1;
        $structuredb = new StructureDB;
        if ($seconds){
            $expiry = time() + $seconds;
        } else {
            $expiry = 0;
        }
        if ($structuredb->allowIP("organisation",$organisationid,$organisationid,$name,$ipaddress,time(),$expiry)){
            return $this->getOrganisationIPs();
        }
    }

    public function allowIPforGroup($groupid,$ipaddress,$name){
        $organisationid = 1;
        $structuredb = new StructureDB;
        if ($seconds){
            $expiry = time() + $seconds;
        } else {
            $expiry = 0;
        }
        $groups = new Groups;
        $groups->setRestriction($groupid,true);
        if ($structuredb->allowIP("group",$organisationid,$groupid,$name,$ipaddress,time(),0)){
            return $this->getGroupIPs($groupid);
        }
    }

    public function removeIPforOrganisation($ipaddress){
        $organisationid = 1;
        $structuredb = new StructureDB;
        if ($structuredb->removeIP($organisationid,"organisation",$organisationid,$ipaddress)){
            return $this->getOrganisationIPs();
        }
    }

    public function removeIPforUser($userid,$ipaddress){
        $organisationid = 1;
        $structuredb = new StructureDB;
        if ($structuredb->removeIP($organisationid,"user",$userid,$ipaddress)){
            return $this->getUserIPs($userid);
        }
    }

    public function removeIPforGroup($groupid,$ipaddress){
        $organisationid = 1;
        $structuredb = new StructureDB;
        if ($structuredb->removeIP($organisationid,"group",$groupid,$ipaddress)){
            // Check if only one then remove group iprestriction
            $hasips = false;
            $groupips = $this->getGroupIPs($groupid);
            foreach ($groupips as $g){
                $hasips = true;
            }
            if (!$hasips){
                $groups = new Groups;
                $groups->setRestriction($groupid,false);
            }
            return $groupips;
        }
    }

    public function getUserIPs($userid){
        $organisationid = 1;//AbstractMiddleware::getOrganisation();
        $structuredb = new StructureDB;
        $x = false;
        $ipallows = $structuredb->getIPAllows($organisationid,"user",$userid,0);
        foreach ($ipallows as $ip){
            $x = true;
        }
        if (!$x)   {
            return "none";
        }
        return $ipallows;
    }

    public function getOrganisationIPs(){
        $organisationid = 1;//AbstractMiddleware::getOrganisation();
        $structuredb = new StructureDB;
        $x = false;
        $ipallows = $structuredb->getIPAllows($organisationid,"organisation",$organisationid,0);
                foreach ($ipallows as $ip){
            $x = true;
        }
        if (!$x)   {
            return "none";
        }
        return $ipallows;
    }

    public function getGroupIPs($groupid){
        $organisationid = 1;//AbstractMiddleware::getOrganisation();
        $structuredb = new StructureDB;
        $ipallows = $structuredb->getIPAllows($organisationid,"group",$groupid,0);
        $x = false;
        foreach ($ipallows as $ip){
            $x = true;
        }
        if (!$x)   {
            return "none";
        }
        return $ipallows;
    }
    
}
?>