<?php

// This library class identifies and finds password ids

class Groups_Middleware {

	// returns

	function getGroups(){
		// Return all groups in organisation
		$groups = new groups;
		$allgroups = $groups->getGroups();
		if ($allgroups){
			Output::Success($allgroups);
		}
		Output::Forbidden("You do not have permission to view groups");
	}

	function addGroup(){
		// Return all my groups
		$groups = new groups;
		$groupid = $groups->addGroup($_POST['name']);
		if ($groupid){
			Groups_Middleware::getGroup($groupid);
		}
		Output::Forbidden("You do not have permission to add this group");
	}

	function getGroup($groupid){
		$groups = new groups;
		$groupinfo = $groups->getGroup($groupid);
		if ($groupinfo){
			Output::Success($groupinfo);
		}
		Output::Forbidden("You do not have permission to view this group");
	}

	function removeGroup(){
		$groups = new groups;
		$groupinfo = $groups->removeGroup($_POST['groupid']);
		if ($groupinfo){
			Output::Success($groupinfo);
		}
		Output::Forbidden("You do not have permission to remove this group");
	}

	function renameGroup(){
		$groups = new groups;
		$groupinfo = $groups->renameGroup($_POST['groupid'],$_POST['name']);
		if ($groupinfo){
			Output::Success($groupinfo);
		}
		Output::Forbidden("You do not have permission to remove this group");
	}

	function addUsertoGroup(){
		$usergroups = new userGroups;
		$groups = $usergroups->addUsertoGroup($_POST['userid'],$_POST['groupid']);
		if ($groups){
			Output::Success($groups);
		}
		Output::Forbidden("You do not have permission to add users to this group");
	}

	function removeUserfromGroup(){
		$usergroups = new userGroups;
		$groups = $usergroups->removeUserfromGroup($_POST['userid'],$_POST['groupid']);
		if ($groups){
			Output::Success($groups);
		}
		Output::Forbidden("You do not have permission to add users to this group");
	}

	function getUserGroups($userid=""){
		$usergroups = new userGroups;
		$groups = $usergroups->userGroups($userid);
		if ($groups){
			Output::Success($groups);
		}
		Output::Forbidden("You do not have permission to view user groups");
	}

	function getNotUserGroups($userid=""){
		$usergroups = new userGroups;
		$groups = $usergroups->notUserGroups($userid);
		if ($groups){
			Output::Success($groups);
		}
		Output::Forbidden("You do not have permission to view user groups");
	}


	
}