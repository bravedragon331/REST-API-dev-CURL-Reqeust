<?php

class UserGroups extends AbstractMiddleware{

    function addUsertoGroup($userid,$groupid){
        $structure = new Structure;
        $userinfo = $structure->getUserbyID($userid);
        $groups = new groups;
        $groupinfo = $groups->getGroupbyId($groupid);
        $groupsDB = new GroupsDB;
        $time = time();
        if ($groupsDB->addUsertoGroup($userinfo['internalid'],$groupinfo['internalid'],$time)){
            return $this->getUserGroups($userinfo['internalid']);
        }
    }

    function removeUserfromGroup($userid,$groupid){
        $structure = new Structure;
        $userinfo = $structure->getUserbyID($userid);
        $groups = new groups;
        $groupinfo = $groups->getGroupbyId($groupid);
        $groupsDB = new GroupsDB;
        $time = time();
        if ($groupsDB->removeUserfromGroup($userinfo['internalid'],$groupinfo['internalid'])){
            return $this->getUserGroups($userinfo['internalid']);
        }
    }

    function checkGroupPerm($internalid,$groupid){
        $usergroups = $this->getUserGroups($internalid);
        if (in_array($groupid,$usergroups)){
            return true;
        }
        return false;
    }

    function getGroupsArray($internalid){
        $usergroups = $this->getUserGroups($internalid);
        $grouparray = array();
        foreach ($usergroups as $u){
            $grouparray[] = $u['groupid'];
        }
        return $grouparray;
    }

    function getUserGroups($internalid){
        $groupsDB = new GroupsDB;
        $groups = $groupsDB->getUserGroups($internalid);
        $gr = array();
        foreach ($groups as $g){
            $g2= array();
            $groupinfo = $groupsDB->getGroupbyInternalID($g['groupid']);
            $g2['groupid'] = $groupinfo['groupid'];
            $g2['groupname'] = $groupinfo['groupname'];
            $g2['dateAdded'] = $g['dateAdded'];
            $g2['dateFormatted'] = date("Y-m-d",$g['dateAdded']);
            $gr[] = $g2;
        }
        return $gr;
    }

    function userGroups($userid=""){
        if ($userid){
            $structure = new Structure;
            $userinfo = $structure->getUserbyID($userid);
            $internalid = $userinfo['internalid'];
        } else {
            $internalid = 1;
        }
        return $this->getUserGroups($internalid);
    }


    function notUserGroups($userid=""){
        if ($userid){
            $structure = new Structure;
            $userinfo = $structure->getUserbyID($userid);
            $internalid = $userinfo['internalid'];
        } else {
            $internalid = 1;
        }
        $groups = $this->getUserGroups($internalid);
        $ingroups = array();
        foreach ($groups as $g){
            $ingroups[] = $g['groupid'];
        }
        $groups = new groups;
        $allgroups = $groups->getGroups();
        $notin = array();
        foreach ($allgroups as $ag){
            if (!in_array($ag['groupid'],$ingroups)){
                $notin[] = $ag;
            }
        }
        return $notin;
    }

    

}

?>