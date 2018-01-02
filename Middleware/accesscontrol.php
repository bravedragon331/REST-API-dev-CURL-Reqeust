<?php

// This library class identifies and finds password ids

class Access_Middleware {

	// returns

	function AllowIPforUser(){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->allowIPforUser($_POST['userid'],$_POST['ipaddress'],$_POST['name'],$_POST['seconds']);
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to edit IP allows");
	}

	function AllowIPforOrganisation(){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->allowIPforOrganisation($_POST['ipaddress'],$_POST['name'],$_POST['seconds']);
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to edit IP allows");
		
	}

	function AllowIPforGroup(){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->allowIPforGroup($_POST['groupid'],$_POST['ipaddress'],$_POST['name']);
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to edit IP allows");	
	}

	function RemoveIPforUser(){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->removeIPforUser($_POST['userid'],$_POST['ipaddress']);
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to edit IP allows");
	}

	function RemoveIPforOrganisation(){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->removeIPforOrganisation($_POST['ipaddress']);
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to edit IP allows");
		
	}

	function RemoveIPforGroup(){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->removeIPforGroup($_POST['groupid'],$_POST['ipaddress']);
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to edit IP allows");
		
	}

	function getUserIPs($userid){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->getUserIPs($userid);
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to view IP allows");
	}

	function getOrganisationIPs(){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->getOrganisationIPs();
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to edit IP allows");
		
	}

	function getGroupIPs($groupid){
		$accesscontrol = new accesscontrol;
		$ipallows = $accesscontrol->getGroupIPs($groupid);
		if ($ipallows){
			Output::Success($ipallows);
		}
		Output::Forbidden("You do not have permission to edit IP allows");
	}



	
}