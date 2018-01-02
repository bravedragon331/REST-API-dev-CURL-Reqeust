<?php
$router = new Router();

function ProcessMatch($match){
	global $userid;
	if($match) {
		if (is_callable( $match['target'] ) ) {
			call_user_func_array( $match['target'], $match['params'] ); 
		} else {
			list( $controller, $action ) = explode( '#', $match['target'] );
		    if (is_callable(array($controller, $action)) ) {
		        call_user_func_array(array($controller,$action), $match['params']);
		    } else {
		    	Output::NotFound("API Request not recognised");
		    }
		}
	} else {
		Output::NotFound("API Request not recognised");
	}
}

$accesscontrol = new Accesscontrol;
//echo(Accesscontrol::$token);
$router->map( 'POST', '/v1/authenticate', 'Users_Middleware#Authenticate'); // Search for passwords

$headers = apache_request_headers();

if(isset($headers['Authorization'])){
	if ($accesscontrol->isTokenValid($headers['Authorization']) == 'expired'){
		Output::Error("Your token has expired");
	}
}

if (/*$accesscontrol->getToken()*/Accesscontrol::isAllowed()){
	// Organisation
	
	$router->map( 'POST', '/v1/logins/addLogin', 'Logins_Middleware#addLogin'); // Add new password
	$router->map( 'GET', '/v1/logins/deleteLogin/[a:id]', 'Logins_Middleware#deleteLogin'); // Add new password
	$router->map( 'POST', '/v1/logins/updatePassword', 'Logins_Middleware#updatePassword'); // Add new password
	$router->map( 'GET', '/v1/logins/group/[a:id]', 'Logins_Middleware#returnLoginsinGroup');
	$router->map( 'POST', '/v1/logins/url', 'Logins_Middleware#returnLoginsforURL');
	$router->map( 'GET', '/v1/logins/[a:id]/[a:bid]', 'Logins_Middleware#returnLogin');
	$router->map( 'POST', '/v1/logins/decryptPassword', 'Logins_Middleware#returnCipheredLogin');
	$router->map( 'POST', '/v1/logins/search', 'Logins_Middleware#search');
	$router->map( 'POST', '/v1/logins/search/exact', 'Logins_Middleware#exactsearch');
	$router->map( 'GET', '/v1/logins/[a:id]', 'Logins_Middleware#returnLogin');
	$router->map( 'GET', '/v1/logins/[a:id]/password', 'Logins_Middleware#returnPassword');
	$router->map( 'GET', '/v1/logins/all/', 'Logins_Middleware#returnAllLogins');
	$router->map( 'GET', '/v1/logins/lastAdded/[i:id]/', 'Logins_Middleware#returnLastAdded');
	//$router->map( 'GET', '/v1/logins/[a:id]/password/[b:id]', 'Logins_Middleware#returnCipheredPassword');)

	// Labels
	$router->map( 'POST', '/v1/labels/add/', 'Labels_Middleware#addLabel');
	$router->map( 'GET', '/v1/labels/', 'Labels_Middleware#returnLabels');
	$router->map( 'GET', '/v1/labels/[i:id]', 'Labels_Middleware#returnLoginLabels');
	$router->map( 'POST', '/v1/labels/remove/', 'Labels_Middleware#removeLabel');
	$router->map( 'POST', '/v1/labels/removeEvery/', 'Labels_Middleware#removeLabelfromEvery');
	$router->map( 'GET', '/v1/labels/search/[a:id]', 'Labels_Middleware#searchByLabel');


	$router->map( 'GET', '/v1/organisation/getOrganisationInfo', 'Users_Middleware#getOrganisationInfo'); // Search for passwords
	$router->map( 'GET', '/v1/passwords/getAllPasswords', 'BookCase_Middleware#returnEntireBookcase'); // Search for passwords
	$router->map( 'POST', '/v1/organisation/updateOrganisationName', 'Users_Middleware#updateOrganisationName'); // Search for passwords
	$router->map( 'GET', '/v1/users', 'Users_Middleware#getUsers'); // Get all Users
	$router->map( 'GET', '/v1/users/deactivated', 'Users_Middleware#getDeactivatedUsers'); // Get all Users

	//$router->map( 'GET', '/users/', 'Users_Middleware#getUsers'); // Get specific user info

	$router->map( 'GET', '/v1/users/[a:id]', 'Users_Middleware#getUser'); // Add a New User
	$router->map( 'GET', '/v1/users/self/', 'Users_Middleware#getMyUser'); // Add a New User
	$router->map( 'POST', '/v1/users/addUser', 'Users_Middleware#addUser'); // Add a New User
	$router->map( 'POST', '/v1/users/deactivateUser', 'Users_Middleware#deactivateUser'); // Add a New User
	$router->map( 'POST', '/v1/users/reactivateUser', 'Users_Middleware#reactivateUser'); // Add a New User
	$router->map( 'POST', '/v1/users/setPermissions', 'Users_Middleware#setPermissions'); // Set User Permissions

	// GROUPS/SHELVES

	$router->map( 'POST', '/v1/users/addUsertoGroup', 'Groups_Middleware#addUsertoGroup'); // Add User to Group
	$router->map( 'POST', '/v1/users/removeUserfromGroup', 'Groups_Middleware#removeUserfromGroup'); // Remove User from Group

	$router->map( 'GET', '/v1/users/self/groups', 'Groups_Middleware#getUserGroups'); // Add a New User
	$router->map( 'GET', '/v1/users/[a:id]/groups', 'Groups_Middleware#getUserGroups'); // Add a New User
	$router->map( 'GET', '/v1/users/[a:id]/notGroups', 'Groups_Middleware#getNotUserGroups'); // Add a New User

	$router->map( 'GET', '/v1/groups', 'Groups_Middleware#getGroups'); // Add a New User
	$router->map( 'GET', '/v1/groups/[a:id]', 'Groups_Middleware#getGroup'); // Add a New User
	$router->map( 'POST', '/v1/groups/addGroup', 'Groups_Middleware#addGroup'); // Add a New User
	$router->map( 'POST', '/v1/groups/removeGroup', 'Groups_Middleware#removeGroup'); // Add a New User
	$router->map( 'POST', '/v1/groups/renameGroup', 'Groups_Middleware#renameGroup'); // Add a New User

	$router->map('POST', '/v1/accesscontrol/allowIPforUser', 'Access_Middleware#AllowIPforUser');
	$router->map('POST', '/v1/accesscontrol/allowIPforOrganisation', 'Access_Middleware#AllowIPforOrganisation');
	$router->map('POST', '/v1/accesscontrol/allowIPforGroup', 'Access_Middleware#AllowIPforGroup');
	$router->map('POST', '/v1/accesscontrol/removeIPforUser', 'Access_Middleware#RemoveIPforUser');
	$router->map('POST', '/v1/accesscontrol/removeIPforOrganisation', 'Access_Middleware#RemoveIPforOrganisation');
	$router->map('POST', '/v1/accesscontrol/removeIPforGroup', 'Access_Middleware#RemoveIPforGroup');
	$router->map('GET', '/v1/users/[a:id]/allowedips', 'Access_Middleware#getUserIPs');
	$router->map('GET', '/v1/organisation/allowedips', 'Access_Middleware#getOrganisationIPs');
	$router->map('GET', '/v1/groups/[a:id]/allowedips', 'Access_Middleware#getGroupIPs');
	/*
	$router->map( 'POST', '/v1/logins/addLogin', 'Logins_Middleware#addLogin'); // Add new password
	$router->map( 'GET', '/v1/logins/group/[a:id]', 'Logins_Middleware#returnLoginsinGroup');
	$router->map( 'POST', '/v1/logins/url', 'Logins_Middleware#returnLoginsforURL');
	$router->map( 'GET', '/v1/logins/[a:id]/[a:bid]', 'Logins_Middleware#returnLogin');
	$router->map( 'POST', '/v1/logins/decryptPassword', 'Logins_Middleware#returnCipheredLogin');
	$router->map( 'POST', '/v1/logins/search', 'Logins_Middleware#search');
	$router->map( 'POST', '/v1/logins/search/exact', 'Logins_Middleware#exactsearch');
	$router->map( 'GET', '/v1/logins/[a:id]', 'Logins_Middleware#returnLogin');
	$router->map( 'GET', '/v1/logins/[a:id/password', 'Logins_Middleware#returnPassword');
	$router->map( 'GET', '/v1/logins/all', 'Logins_Middleware#returnAllLogins');
	$router->map( 'GET', '/v1/logins/lastAdded/[i:id]/', 'Logins_Middleware#returnLastAdded');
	//$router->map( 'GET', '/v1/logins/[a:id]/password/[b:id]', 'Logins_Middleware#returnCipheredPassword');)

	$router->map( 'GET', '/v1/organisation/getOrganisationInfo', 'Users_Middleware#getOrganisationInfo'); // Search for passwords
	$router->map( 'GET', '/v1/passwords/getAllPasswords', 'BookCase_Middleware#returnEntireBookcase'); // Search for passwords
	$router->map( 'POST', '/v1/organisation/updateOrganisationName', 'Users_Middleware#updateOrganisationName'); // Search for passwords
	$router->map( 'GET', '/v1/users', 'Users_Middleware#getUsers'); // Get all Users
	$router->map( 'GET', '/v1/users/deactivated', 'Users_Middleware#getDeactivatedUsers'); // Get all Users

	// Labels
	$router->map( 'POST', '/v1/labels/add/', 'Labels_Middleware#addLabel');
	$router->map( 'GET', '/v1/labels/', 'Labels_Middleware#returnLabels');
	$router->map( 'GET', '/v1/labels/[i:id]', 'Labels_Middleware#returnLoginLabels');
	$router->map( 'POST', '/v1/labels/remove/', 'Labels_Middleware#removeLabel');
	$router->map( 'POST', '/v1/labels/removeEvery/', 'Labels_Middleware#removeLabelfromEvery');
	$router->map( 'GET', '/v1/labels/search/[a:id]', 'Labels_Middleware#searchByLabel');


	//$router->map( 'GET', '/users/', 'Users_Middleware#getUsers'); // Get specific user info

	$router->map( 'GET', '/v1/users/[a:id]', 'Users_Middleware#getUser'); // Add a New User
	$router->map( 'GET', '/v1/users/self/', 'Users_Middleware#getMyUser'); // Add a New User
	$router->map( 'POST', '/v1/users/addUser', 'Users_Middleware#addUser'); // Add a New User
	$router->map( 'POST', '/v1/users/deactivateUser', 'Users_Middleware#deactivateUser'); // Add a New User
	$router->map( 'POST', '/v1/users/reactivateUser', 'Users_Middleware#reactivateUser'); // Add a New User
	$router->map( 'POST', '/v1/users/setPermissions', 'Users_Middleware#setPermissions'); // Set User Permissions

	// GROUPS/SHELVES

	$router->map( 'POST', '/v1/users/addUsertoGroup', 'Groups_Middleware#addUsertoGroup'); // Add User to Group
	$router->map( 'POST', '/v1/users/removeUserfromGroup', 'Groups_Middleware#removeUserfromGroup'); // Remove User from Group

	$router->map( 'GET', '/v1/users/self/groups', 'Groups_Middleware#getUserGroups'); // Add a New User
	$router->map( 'GET', '/v1/users/[a:id]/groups', 'Groups_Middleware#getUserGroups'); // Add a New User
	$router->map( 'GET', '/v1/users/[a:id]/notGroups', 'Groups_Middleware#getNotUserGroups'); // Add a New User

	$router->map( 'GET', '/v1/groups', 'Groups_Middleware#getGroups'); // Add a New User
	$router->map( 'GET', '/v1/groups/[a:id]', 'Groups_Middleware#getGroup'); // Add a New User
	$router->map( 'POST', '/v1/groups/addGroup', 'Groups_Middleware#addGroup'); // Add a New User
	$router->map( 'POST', '/v1/groups/removeGroup', 'Groups_Middleware#removeGroup'); // Add a New User
	$router->map( 'POST', '/v1/groups/renameGroup', 'Groups_Middleware#renameGroup'); // Add a New User

	$router->map('POST', '/v1/accesscontrol/allowIPforUser', 'Access_Middleware#AllowIPforUser');
	$router->map('POST', '/v1/accesscontrol/allowIPforOrganisation', 'Access_Middleware#AllowIPforOrganisation');
	$router->map('POST', '/v1/accesscontrol/allowIPforGroup', 'Access_Middleware#AllowIPforGroup');
	$router->map('POST', '/v1/accesscontrol/removeIPforUser', 'Access_Middleware#RemoveIPforUser');
	$router->map('POST', '/v1/accesscontrol/removeIPforOrganisation', 'Access_Middleware#RemoveIPforOrganisation');
	$router->map('POST', '/v1/accesscontrol/removeIPforGroup', 'Access_Middleware#RemoveIPforGroup');
	$router->map('GET', '/v1/users/[a:id]/allowedips', 'Access_Middleware#getUserIPs');
	$router->map('GET', '/v1/organisation/allowedips', 'Access_Middleware#getOrganisationIPs');
	$router->map('GET', '/v1/groups/[a:id]/allowedips', 'Access_Middleware#getGroupIPs');
	*/
}

$match = $router->match();
ProcessMatch($match);
?>