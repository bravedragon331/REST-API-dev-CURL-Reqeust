<?php

// This library class identifies and finds password ids

class Users_Middleware {

	// returns

	function Authenticate(){
		//$accesscontrol = new Accesscontrol;
		$token = Accesscontrol::Authenticate($_POST['username'],$_POST['password']);
		if ($token){
			Output::Success($token);
		}
		Output::Error("Login details incorrect");
	}

	function addUser(){
		$errors = array();
		if (isset($_POST['username'])){
			if ((strlen($_POST['username'])) < 3){
				$errors[] = "Username is too short";
			}
			if ((strlen($_POST['email'])) < 3){
				$errors[] = "Email is too short";
			}
			if ((strlen($_POST['password'])) < 3){
				$errors[] = "Password is too short";
			}
		} else {
			$errors[] = "Username does not appear valid";
		}
		if ($errors){
			foreach ($errors as $e){
				$message = $e . ". ";
			}
			Output::Error($message);
		}
		$structure = new Structure;
		$adduser = $structure->addUser($_POST['username'],$_POST['email'],$_POST['password']);
		if (!$adduser){
			Output::Error("There was an error adding the user. Make sure details are unique");
		}
		$structure->filterPerms($adduser['userid'],$_POST);
		if ($adduser){
			Output::Success($adduser);
		}
		Output::Forbidden("You do not have permission to add a user");
	}

	function setPermissions(){
		$structure = new Structure;
		$structure->filterPerms($_POST['userid'],$_POST);
		Users_Middleware::getUser($_POST['userid']);
	}

	function getUsers(){
		$structure = new Structure;
		$users = $structure->getUsers();
		if ($users){
			Output::Success($users);
		}
		Output::Forbidden("You do not have permission to view users");
	}

	function getDeactivatedUsers(){
		$structure = new Structure;
		$users = $structure->getDeactivatedUsers();
		if ($users){
				if ($users == "none"){
					Output::Success("");
				}
			Output::Success($users);
		}
		Output::Forbidden("You do not have permission to view users");
	}

	function getUser($userid){
		$structure = new Structure;
		$user = $structure->getUser($userid);
		if ($user){
			Output::Success($user);
		}
		Output::Forbidden("You do not have permission to view this user");
	}

	function getMyUser(){
		$structure = new Structure;
		$user = $structure->getMyUser();
		if ($user){
			Output::Success($user);
		}
		Output::Forbidden("You do not have permission to view this user");
	}

	function deactivateUser(){
		$structure = new Structure;
		if ($structure->deactivateUser($_POST['userid'])){
			Users_Middleware::getUser($_POST['userid']);
		}
		Output::Forbidden("You do not have permission to deactivate this user");
	}

	function reactivateUser(){
		$structure = new Structure;
		if ($structure->reactivateUser($_POST['userid'])){
			Users_Middleware::getUser($_POST['userid']);
		}
		Output::Forbidden("You do not have permission to reactivate this user");
	}	

	function getOrganisationInfo(){
		$structure = new Structure;
		$organisationinfo = $structure->getOrganisationInfo();
		if ($organisationinfo){ 
			Output::Success($organisationinfo);
		}
		Output::Error("Organisation Info could not be returned");
	}

	function updateOrganisationName(){
		if (!isset($_POST['name'])){
			Output::Error("No organisation name specified");
		}
		if ((strlen($_POST['name']))<2){
			Output::Error("This organisation name is too short.");
		}
		$structure = new Structure;
		$newinfo = $structure->updateOrganisationName($_POST['name']);
		if ($newinfo){
			Output::Success($newinfo);
		} 
		Output::Error("Organisation Info could not be returned");
	}
	
}