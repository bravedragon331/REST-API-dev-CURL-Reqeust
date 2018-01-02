<?php

class Groups extends AbstractMiddleware{

    public function getGroups(){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        $allgroups = $groupsdb->getGroups($organisationid);
        $gr = array();
        foreach ($allgroups as $g){
            $x = 1;
            $g2 = array();
            $g2['groupid'] = $g['groupid'];
            $g2['groupname'] = $g['groupname'];
            $g2['dateCreated'] = $g['dateCreated'];
            $g2['dateFormat'] = date("Y-m-d",$g['dateCreated']);
            $gr[] = $g2;
        }
        if (!$x) { return "none"; }
        return $gr;
    }

    public function getGroup($groupid){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        $g = $groupsdb->getGroup($organisationid,$groupid);
        if (!$g) { return false; }

            $g2 = array();
            $g2['groupid'] = $g['groupid'];
            $g2['groupname'] = $g['groupname'];
            $g2['dateCreated'] = $g['dateCreated'];
            $g2['dateFormat'] = date("Y-m-d",$g['dateCreated']);
            if ($g['deactivated']){
                $g2['deactivated'] = $g['deactivated'];
                $g2['deactivatedFormat'] = date("Y-m-d",$g['deactivated']);
            }            


        return $g2;
    }

    public function getGroupbyId($hash){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        $user = $groupsdb->getGroupbyId($organisationid,$hash);
        return $user;
    }

    public function getGroupbyInternalId($groupid){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        $group = $groupsdb->getGroupbyInternalId($groupid);
        return $group;
    }

    private function getGroupbyName($groupname){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        $user = $groupsdb->getGroupbyName($organisationid,$groupname);
        return $user;
    }


    public function addGroup($name,$restrict="0"){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        if ($this->getGroupbyName($name)){
            return false;
        }
        $hash = $this->generateHash();
        $timeadded = time();
        $groupid = $groupsdb->addGroup($organisationid,$name,$hash,$timeadded,$restrict);
        return $hash;
    }

    public function removeGroup($groupid){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        $time = time();
        if ($groupsdb->removeGroup($organisationid,$groupid,$time)){
            return $this->getGroup($groupid);
        }
        return false;
    }

    public function renameGroup($groupid,$name){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        if ($groupsdb->renameGroup($organisationid,$groupid,$name)){
            return $this->getGroup($groupid);
        }
    }

    public function setRestriction($groupid,$setting){
        $organisationid = 1;
        $groupsdb = new GroupsDB;
        if ($groupsdb->setRestriction($organisationid,$groupid,$setting)){
            return true;
        }
    }

    private function generateHash(){
        $length = 15;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $hash = '';
        for ($i = 0; $i < $length; $i++) {
            $hash .= $characters[rand(0, $charactersLength - 1)];
        }
        if ($this->getGroupbyID($hash)){
            return $this->generateHash();
        }
        return $hash;
    }
}

?>