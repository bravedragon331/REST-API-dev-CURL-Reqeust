<?php
class Auth {
    public function authenticate($user,$password){
        $hash = $this->getPassword($user);
        if (!password_verify($password,$hash)){
            return false;
        }
        $userinfo = $this->getUserbyUsername($user);     
      
        $ipaddress = $_SERVER['REMOTE_ADDR'];
        $oips = $this->getOrganisationIPs($userinfo['organisationid']);
        $uips = $this->getUserIPs($userinfo['userid']);

        if ((in_array('%',$oips)) || (in_array($ipaddress,$oips))){
            if ((in_array('%',$uips)) || (in_array($ipaddress,$uips))){
                $structuredb = new StructureDB;
                $tokenexpiry = time() + ConfigVariables::sessionLength(); // 1hr
                $token = $this->generateToken();
                $expiry = $structuredb->addToken($userinfo['userid'],$userinfo['internalid'],$userinfo['organisationid'],$tokenexpiry,$ipaddress,$token);
                if ($expiry){
                    return $token;
                }
                return 500;
            }
        }

        return 403;
    }

    private function getUserIPs($userid){
        $structuredb = new StructureDB;
        $x = false;
        $userips = $structuredb->getIPAllows($organisationid,"user",$userid,0);
        $uips = array();
        foreach ($userips as $uip){
            $uips[] = $uip['ipaddress'];
        }
        return $uips;
    }

    public function getOrganisationIPs(){
        $structuredb = new StructureDB;
        $organisationips = $structuredb->getIPAllows($organisationid,"organisation",$organisationid,0);
        $oips = array();
        foreach ($organisationips as $oip){
            $oips[] = $oip['ipaddress'];
        }
        return $oips;
    }

}
?>