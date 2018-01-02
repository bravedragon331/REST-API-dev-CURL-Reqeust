<?php

// This library class identifies and finds password ids

class Logins_Middleware {

	// returns
	function addLogin(){
		$logins = new logins;
		$loginid = $logins->addLogin($_POST);
		if ($loginid){
			Output::Success($loginid);
		}
		Output::Forbidden("Could not be added");
	}

	function returnLogin($loginid,$alsopassword=false){
		$logins = new logins;
		$logininfo = $logins->returnLogin($loginid,$alsopassword);
		if ($logininfo){
			Output::Success($logininfo);
		}
		Output::Error("No result found");
	}

	function returnPassword($loginid){
		$logins = new logins;
		$logininfo = $logins->returnPassword($loginid);
		if ($logininfo){
			Output::Success($logininfo);
		}
		Output::Error("No result found");
	}

	function returnLoginsinGroup($groupid){
		if (!$groupid){$groupid = 0;}
		$logins = new logins;
		$grouplogins = $logins->returnLoginsinGroup($groupid);
		if ($grouplogins){
			Output::Success($grouplogins);
		}
		Output::Error("No results found");
	}

	function returnAllLogins(){
		$logins = new logins;
		$alllogins = $logins->returnAllLogins();
		if ($alllogins){
			Output::Success($alllogins);
		}
		Output::Error("No results found");
	}

	function returnLoginsforURL($url=""){
		$url = $_POST['url'];
   		if (!$url){ Output::Error("Invalid URL"); }
		$password = new Password;
		$urllogins = $password->returnLoginsforURL($url);
		if ($urllogins){
			Output::Success($urllogins);
		}
		Output::Error("No results found");
	}

	function returnCipheredLogin(){
		$logins = new logins;
		$login = $logins->returnCipheredlogin($_POST['loginid'],$_POST['preencrypt']);
		if ($login){
			Output::Success($login);
		}
		Output::Error("Deencryption Failed");
	}

	function search(){
		$logins = new logins;
		$logininfo = $logins->search($_POST['search']);
		if ($logininfo){
			Output::Success($logininfo);
		}
		Output::Error("No results found");
	}

	function exactsearch(){
		$logins = new logins;
		$logininfo = $logins->exactsearch($_POST['search']);
		if ($logininfo){
			Output::Success($logininfo);
		}
		Output::Error("No results found");
	}

	function returnLastAdded($number){
		$logins = new logins;
		$logininfo = $logins->returnLastAdded($number);
		if ($logininfo){
			Output::Success($logininfo);
		}
		Output::Error("No results found");
	}

	function updatePassword(){
		$password = new Password;
		$logininfo = $password->updatePassword($_POST['loginid'],$_POST['password'],$_POST['preencrypt']);
		if ($logininfo){
			Output::Success($logininfo);
		}
		Output::Error("No results found");
	}

	function deleteLogin($loginid){
		$password = new Password;
		$logininfo = $password->deletePassword($loginid);
		if ($logininfo){
			Output::Success("Deleted");
		}
		Output::Error("No results found");
	}


	
}