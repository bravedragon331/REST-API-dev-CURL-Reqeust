<h1>Password Test</h1>
<?php
        $hostURL = "http://127.0.0.1";
        //$hostURL = "http://178.62.109.82";

		ini_set("display_errors","on");
        // create curl resource 
        $params=['username'=>"seb",'password'=>'ramesesXerxes'];

		$ch = curl_init(); 
        //curl_setopt($ch, CURLOPT_URL, "http://178.62.109.82/v1/authenticate"); 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/authenticate"); 
		curl_setopt($ch, CURLOPT_POST, true); 
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        var_dump($output);

        $output = json_decode($output);

        $token = $output->data;

        // var_dump($token);

        // close curl resource to free up system resources 
        curl_close($ch);      
?>

<h2>Users and Organisations</h2>

<h3>Get all logins</h3>

<?php

ini_set("display_errors","on");
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        //curl_setopt($ch, CURLOPT_URL, "http://178.62.109.82/v1/passwords/getAllPasswords"); 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/passwords/getAllPasswords"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        var_dump($output);

        // close curl resource to free up system resources 
        curl_close($ch);  
 
?>

<h3>Get organisation Info</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        //curl_setopt($ch, CURLOPT_URL, "http://178.62.109.82/v1/organisation/getOrganisationInfo"); 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/organisation/getOrganisationInfo"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        var_dump($output);

        // close curl resource to free up system resources 
        curl_close($ch);      
?>

<h3>Update Organisation Name</h3>

<?php 

	if (isset($_POST['neworganisationname'])){
		$params=['name'=>$_POST['neworganisationname']];

		$ch = curl_init(); 
        //curl_setopt($ch, CURLOPT_URL, "http://178.62.109.82/v1/organisation/updateOrganisationName"); 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/organisation/updateOrganisationName"); 
		curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
		$output = curl_exec($ch);
		curl_close($ch); 

		var_dump($output);
	} 

?>

<form method="post">
<input type="text" name="neworganisationname">
<input type="submit">
</form>

<h3>Add User</h3>

<?php

	if (isset($_POST['newuser_username'])){
		
		$params=['username'=>$_POST['newuser_username'],
			'email'=>$_POST['newuser_email'],
			'password'=>$_POST['newuser_password'],
			'manageusers'=>$_POST['newuser_manageusers'],
			'addpasswords'=>$_POST['newuser_addpasswords'],
			'deletepasswords'=>$_POST['newuser_deletepasswords'],
			'changepasswords'=>$_POST['newuser_changepasswords'],
			'edittags'=>$_POST['newuser_edittags'],
			'editgroups'=>$_POST['newuser_editgroups']];

		$ch = curl_init(); 
        //curl_setopt($ch, CURLOPT_URL, "http://178.62.109.82/v1/users/addUser"); 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/addUser"); 
		curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
		$output = curl_exec($ch); 
		curl_close($ch); 

		var_dump($output);
	} 

?>


<form method="post">
	Username: <input type="text" name="newuser_username"><br />
	Email: <input type="text" name="newuser_email"><br />
	Passwords: <input type="password" name="newuser_password"><br />
	<input type="checkbox" name="newuser_manageusers">Manage Users<br />
	<input type="checkbox" name="newuser_addpasswords">Add Passwords<br />
	<input type="checkbox" name="newuser_deletepasswords">Delete Passwords<br />
	<input type="checkbox" name="newuser_changepasswords">Change Passwords<br />
	<input type="checkbox" name="newuser_edittags">Edit Tags<br />
	<input type="checkbox" name="newuser_editgroups">Manage Groups<br />
	<input type="submit" value="Add User">
</form>

<h3>Get Users</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        //curl_setopt($ch, CURLOPT_URL, "http://178.62.109.82/v1/users"); 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

        $data = json_decode($output);
        $data = $data->data;

 
?>

<h3>getUserInfo</h3>

<form method="post">
	<select name="getuserinfo_id">
			<?php foreach ($data as $d){ ?>
				<option value="<?php echo $d->userid; ?>"><?php echo $d->username; ?></option>
			<?php } ?>
	</select>
	<input type="submit" value="getUserInfo">
</form>

<?php if (isset($_POST['getuserinfo_id'])){

        // create curl resource 
        $ch = curl_init(); 

        // set url 
        //$url = "http://178.62.109.82/v1/users/".$_POST['getuserinfo_id'];
        $url = $hostURL."/v1/users/".$_POST['getuserinfo_id'];
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

} ?>

<h3>getMyUserInfo</h3>
<?php

        // create curl resource 
        $ch = curl_init(); 

        // set url 
        //$url = "http://178.62.109.82/v1/users/self/";
        $url = $hostURL."/v1/users/self/";
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

?>


<h3>setPermissions</h3>

<?php if (isset($_POST['setuserperms_id'])){
	$params=['userid'=>$_POST['setuserperms_id'],
			'manageusers'=>$_POST['setuserperms_manageusers'],
			'addpasswords'=>$_POST['setuserperms_addpasswords'],
			'deletepasswords'=>$_POST['setuserperms_deletepasswords'],
			'changepasswords'=>$_POST['setuserperms_changepasswords'],
			'edittags'=>$_POST['setuserperms_edittags'],
			'editgroups'=>$_POST['setuserperms_editgroups']];
	$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/setPermissions"); 
		curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
		$output = curl_exec($ch); 
		curl_close($ch); 

		var_dump($output);
} ?>

<form method="post">
	<select name="setuserperms_id">
			<?php foreach ($data as $d){ ?>
				<option value="<?php echo $d->userid; ?>"><?php echo $d->username; ?></option>
			<?php } ?>
	</select><br />
	<input type="checkbox" name="setuserperms_manageusers">Manage Users<br />
	<input type="checkbox" name="setuserperms_addpasswords">Add Passwords<br />
	<input type="checkbox" name="setuserperms_deletepasswords">Delete Passwords<br />
	<input type="checkbox" name="setuserperms_changepasswords">Change Passwords<br />
	<input type="checkbox" name="setuserperms_edittags">Edit Tags<br />
	<input type="checkbox" name="setuserperms_editgroups">Manage Groups<br />
	<input type="submit" value="setUserPermissions">
</form>


<h3>deactivateUser</h3>

<?php if (isset($_POST['deactivateuser_id'])){
	$params=['userid'=>$_POST['deactivateuser_id']];
	$ch = curl_init(); 
        //curl_setopt($ch, CURLOPT_URL, "http://178.62.109.82/v1/users/deactivateUser"); 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/deactivateUser"); 
		curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
		$output = curl_exec($ch); 
		curl_close($ch); 

		var_dump($output);
} ?>

<form method="post">
	<select name="deactivateuser_id">
			<?php foreach ($data as $d){ ?>
				<option value="<?php echo $d->userid; ?>"><?php echo $d->username; ?></option>
			<?php } ?>
	</select><br />
	<input type="submit" value="deactivateUser">
</form>

<h3>Get Deactivated Users</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        //curl_setopt($ch, CURLOPT_URL, "http://178.62.109.82/v1/users/deactivated"); 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/deactivated"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

        $dedata = json_decode($output);
        $dedata = $dedata->data;

 
?>

<h3>reactivateUser</h3>

<?php if (isset($_POST['reactivateuser_id'])){
	$params=['userid'=>$_POST['reactivateuser_id']];
	$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/reactivateUser"); 
		curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
		$output = curl_exec($ch); 
		curl_close($ch); 

		var_dump($output);
} ?>

<form method="post">
	<select name="reactivateuser_id">
			<?php foreach ($dedata as $d){ ?>
				<option value="<?php echo $d->userid; ?>"><?php echo $d->username; ?></option>
			<?php } ?>
	</select><br />
	<input type="submit" value="reactivateUser">
</form>

<h2>Groups</h2>

<h3>List Groups</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/groups"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

        $gdata = json_decode($output);
        $gdata = $gdata->data;

 
?>

<h3>Get Group</h3>

<form method="post">
	<select name="getGroup">
		<?php foreach ($gdata as $g){ ?>
			<option value="<?=$g->groupid?>"><?=$g->groupname?></option>
		<?php } ?>
	</select>
	<input type="submit" value="getGroup">
</form>

<?php
        // create curl resource 
if (isset($_POST['getGroup'])){
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/groups/" . $_POST['getGroup']); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
}
?>

<h3>Add Group</h3>

<?php if (isset($_POST['addgroup_name'])){
	$params=['name'=>$_POST['addgroup_name']];
	$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/groups/addGroup"); 
		curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
		$output = curl_exec($ch); 
		curl_close($ch); 

		var_dump($output);

} ?>

<form method="post">
<input type="text" name="addgroup_name">
<input type="submit" value="Add Group">
</form>

<h3>Remove Group</h3>

<form method="post">
	<select name="removeGroup">
		<?php foreach ($gdata as $g){ ?>
			<option value="<?=$g->groupid?>"><?=$g->groupname?></option>
		<?php } ?>
	</select>
	<input type="submit" value="removeGroup">
</form>

<?php
        // create curl resource 
if (isset($_POST['removeGroup'])){
		$params=['groupid'=>$_POST['removeGroup']];
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/groups/removeGroup"); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        // $output contains the output string 
        $output = curl_exec($ch); 
        // close curl resource to free up system resources 
        curl_close($ch);   
        var_dump($output);
}
?>

<h3>Rename Group</h3>

<?php if (isset($_POST['renameGroup'])){
	$params=['groupid'=>$_POST['renameGroup'],'name'=>$_POST['renameGroupName']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/groups/renameGroup"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
	<select name="renameGroup">
		<?php foreach ($gdata as $g){ ?>
			<option value="<?=$g->groupid?>"><?=$g->groupname?></option>
		<?php } ?>
	</select>
	<input type="text" name="renameGroupName">
	<input type="submit" value="renameGroup">
</form>

<h3>List My Groups</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/self/groups"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

 
?>

<h3>List User Group (6)</h3>
<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/8oug1kbj2r3efwo8ilgbj1r3fqw/groups"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
        $gxdata = json_decode($output);
        $gxdata = $gxdata->data;

 
?>

<h3>List User Not Groups (6)</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/8oug1kbj2r3efwo8ilgbj1r3fqw/notGroups"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

        $gdata = json_decode($output);
        $gdata = $gdata->data;

 
?>



<h3>addUsertoGroup (6)</h3>

<?php if (isset($_POST['addUsertoGroup_User'])){
	$params=['groupid'=>$_POST['addUsertoGroup_Group'],'userid'=>$_POST['addUsertoGroup_User']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/addUsertoGroup"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
	<select name="addUsertoGroup_User">
		<?php foreach ($data as $d){ ?>
			<option value="<?=$d->userid?>"><?=$d->username?></option>
		<?php } ?>
	</select>
	<select name="addUsertoGroup_Group">
		<?php foreach ($gdata as $g){ ?>
			<option value="<?=$g->groupid?>"><?=$g->groupname?></option>
		<?php } ?>
	</select>
	<input type="submit" value="addUsertoGroup">
</form>

<h3>removeUserfromGroup (6)</h3>

<?php if (isset($_POST['removeUserfromGroup_User'])){
	$params=['userid'=>$_POST['removeUserfromGroup_User'],'groupid'=>$_POST['removeUserfromGroup_Group']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/removeUserfromGroup"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
	<input type="hidden" name="removeUserfromGroup_User" value="8oug1kbj2r3efwo8ilgbj1r3fqw">
	<select name="removeUserfromGroup_Group">
		<?php foreach ($gxdata as $g){ ?>
			<option value="<?=$g->groupid?>"><?=$g->groupname?></option>
		<?php } ?>
	</select>
	<input type="submit" value="removeUserfromGroup">
</form>

<h3>Get User Allowed IPs (6)</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/users/utYYwS4QuxPzMgQ/allowedips"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

        $uipdata = json_decode($output);
        $uipdata = $uipdata->data;


 
?>


<h3>Get Group Allowed IPs</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/groups/CRhPZbJYL0vuApM/allowedips"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

        $gipdata = json_decode($output);
        $gipdata = $gipdata->data;

 
?>


<h3>Get Organisation Allowed IPs</h3>

<?php
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/organisation/allowedips"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);

        $oipdata = json_decode($output);
        $oipdata = $oipdata->data;

 
?>


<h3>Add IP for Organisation</h3>

<?php if (isset($_POST['addOrganisation_IP'])){

	$params=['ipaddress'=>$_POST['addOrganisation_IP'],'name'=>$_POST['addOrganisation_Name'],'seconds'=>$_POST['addOrganisation_Seconds']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/accesscontrol/allowIPforOrganisation"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);

} ?>

<form method="post">
	IP: <input type="text" name="addOrganisation_IP"><br />
	Name: <input type="text" name="addOrganisation_Name"><br />
	Seconds: <input type="text" name="addOrganisation_Seconds"><br />
	<input type="submit" value="addIPtoOrganisation">
</form>

<h3>Add IP for User</h3>

<?php if (isset($_POST['addUserIP_User'])){

	$params=['userid'=>$_POST['addUserIP_User'],'ipaddress'=>$_POST['addUserIP_IP'],'name'=>$_POST['addUserIP_Name'],'seconds'=>$_POST['addUserIP_Seconds']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/accesscontrol/allowIPforUser"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);

} ?>

<form method="post">
	<select name="addUserIP_User">
		<?php foreach ($data as $d){ ?>
			<option value="<?=$d->userid?>"><?=$d->username?></option>
		<?php } ?>
	</select>
	IP: <input type="text" name="addUserIP_IP"><br />
	Name: <input type="text" name="addUserIP_Name"><br />
	Seconds: <input type="text" name="addUserIP_Seconds"><br />

	<input type="submit" value="addIPtoUser">
</form>


<h3>Add IP for Group</h3>

<?php if (isset($_POST['addUserIP_Group'])){

	$params=['groupid'=>$_POST['addUserIP_Group'],'ipaddress'=>$_POST['addUserIP_IP'],'name'=>$_POST['addUserIP_Name']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/accesscontrol/allowIPforGroup"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);

} ?>

<form method="post">
	<select name="addUserIP_Group">
		<?php foreach ($gdata as $g){ ?>
			<option value="<?=$g->groupid?>"><?=$g->groupname?></option>
		<?php } ?>
	</select>
	IP: <input type="text" name="addUserIP_IP"><br />
	Name: <input type="text" name="addUserIP_Name"><br />

	<input type="submit" value="addIPtoGroup">
</form>

<h3>Delete User IPs (utYYwS4QuxPzMgQ)</h3>

<?php if (isset($_POST['deleteUserIP_userid'])){
	$params=['userid'=>$_POST['deleteUserIP_userid'],'ipaddress'=>$_POST['deleteUserIP']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/accesscontrol/removeIPforUser"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
	<input type="hidden" name="deleteUserIP_userid" value="utYYwS4QuxPzMgQ">
	<select name="deleteUserIP">
		<?php foreach ($uipdata as $ip){ ?>
			<option value="<?=$ip->ipaddress?>"><?=$ip->name?> (<?=$ip->ipaddress?>)</option>
		<?php } ?>
	</select>
	<input type="submit" value="removeUserIP">
</form>

<h3>Delete Group IPs (CRhPZbJYL0vuApM)</h3>


<?php if (isset($_POST['deleteGroupIP_groupid'])){
	$params=['groupid'=>$_POST['deleteGroupIP_groupid'],'ipaddress'=>$_POST['deleteGroupIP']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/accesscontrol/removeIPforGroup"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
	<input type="hidden" name="deleteGroupIP_groupid" value="CRhPZbJYL0vuApM">
	<select name="deleteGroupIP">
		<?php foreach ($gipdata as $ip){ ?>
			<option value="<?=$ip->ipaddress?>"><?=$ip->name?> (<?=$ip->ipaddress?>)</option>
		<?php } ?>
	</select>
	<input type="submit" value="removeGroupIP">
</form>

<h3>Delete Organisation IPs (utYYwS4QuxPzMgQ)</h3>


<?php if (isset($_POST['deleteOrganisationIP'])){
	$params=['ipaddress'=>$_POST['deleteOrganisationIP']];
	$ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/accesscontrol/removeIPforOrganisation"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
	curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
	<select name="deleteOrganisationIP">
		<?php foreach ($oipdata as $ip){ ?>
			<option value="<?=$ip->ipaddress?>"><?=$ip->name?> (<?=$ip->ipaddress?>)</option>
		<?php } ?>
	</select>
	<input type="submit" value="removeOrganisationIP">
</form>

<h2>Logins</h2>

<h3>Add Password</h3>

<?php if (isset($_POST['addLogin_Name'])){
    $params=['loginname'=>$_POST['addLogin_Name'],'username'=>$_POST['addLogin_Username'],'password'=>$_POST['addLogin_Password'],'url'=>$_POST['addLogin_url'],
'group'=>$_POST['addLogin_Group'],'notes'=>$_POST['addLogin_notes'],'preencrypt'=>$_POST['addLogin_preencrypt']];
    $ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/logins/addLogin"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
        Name: <input type="text" name="addLogin_Name"><br />
        Username: <input type="text" name="addLogin_Username"><br />
        Password: <input type="password" name="addLogin_Password"><br />
        Preencryption Key: <input type="text" name="addLogin_preencrypt"><br />
        URL (optional): <input type="text" name="addLogin_url"><br />
        Group (optional): 
        <select name="addLogin_Group">
            <option value="">Not set</option>
            <?php foreach ($gdata as $g){ ?>
                <option value="<?=$g->groupid?>"><?=$g->groupname?></option>
            <?php } ?>
        </select><br />
        Notes (optional): <textarea name="addLogin_notes"></textarea>
        <input type="submit" value="addLogin">
</form>


<h3>return All in Group</h3>

<?php if (isset($_POST['returnGroup_Group'])){
        
   // create curl resource 
        $ch = curl_init(); 
        $returngroupid = (int)$_POST['returnGroup_Group'];

        // set url 
        $url = $hostURL."/v1/logins/group/".$returngroupid;
        //echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
        $gpwddata = json_decode($output);
        $gpwddata = $gpwddata->data;

}   else {

    $ch = curl_init(); 
        $returngroupid = (int)$_POST['returnGroup_Group'];

        // set url 
        $url = $hostURL."/v1/logins/group/0";
        //echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
        $gpwddata = json_decode($output);
        $gpwddata = $gpwddata->data;

}?>

<form method="post">
    <select name="returnGroup_Group">
        <option value="0">Ungrouped</option>
        <?php foreach ($gdata as $g){ ?>
            <option value="<?=$g->groupid?>"><?=$g->groupname?></option>
        <?php } ?>
    </select>
    <input type="submit" value="returnAllinGroup">
</form>

<h3>return specific login</h3>

<p>Return all in group first to build array</p>

<?php if (isset($_POST['returnLogin_Login'])){
    // create curl resource 
        $ch = curl_init(); 

        // set url 
        $url = $hostURL."/v1/logins/".$_POST['returnLogin_Login'];
       // echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
} ?>

<form method="post">
    <select name="returnLogin_Login">
        <?php foreach ($gpwddata as $gpwd){ ?>
            <option value="<?=$gpwd->loginid?>"><?=$gpwd->label?></option>
        <?php } ?>
    </select>
    <input type="submit" value="returnLogin">
</form>


<h3>return specific login with password</h3>

<p>Return all in group first to build array</p>

<?php if (isset($_POST['returnLogin_LoginPass'])){
    // create curl resource 
        $ch = curl_init(); 

        // set url 
        $url = $hostURL."/v1/logins/".$_POST['returnLogin_LoginPass']."/true";
       // echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
} ?>

<form method="post">
    <select name="returnLogin_LoginPass">
        <?php foreach ($gpwddata as $gpwd){ 
             if ($gpwd->preencrypt){ 
                continue; 
            }
        ?>
            <option value="<?=$gpwd->loginid?>"><?=$gpwd->label?></option>
        <?php } ?>
    </select>
    <input type="submit" value="returnLogin">
</form>

<h3>Return logins for URL</h3>

<?php if (isset($_POST['returnLogins_URL'])){

        $params=['url'=>$_POST['returnLogins_URL']];
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/logins/url"); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        // $output contains the output string 
        $output = curl_exec($ch); 
        // close curl resource to free up system resources 
        curl_close($ch);   
        var_dump($output);

} ?>

<form method="post">
    <input type="text" name="returnLogins_URL">
    <input type="submit" value="returnLoginsforURL">
</form>

<h3>Return decrypted password</h3>

<?php if (isset($_POST['decryptionkey_key'])){

        $params=['preencrypt'=>$_POST['decryptionkey_key'],'loginid'=>$_POST['decryptionkey_loginid']];
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/logins/decryptPassword"); 
        curl_setopt($ch, CURLOPT_POST, true); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        // $output contains the output string 
        $output = curl_exec($ch); 
        // close curl resource to free up system resources 
        curl_close($ch);   
        var_dump($output);

} ?>

<form method="post">
    <input type="text" name="decryptionkey_key">
        <select name="decryptionkey_loginid">
        <?php foreach ($gpwddata as $gpwd){ 
            if (!$gpwd->preencrypt){ continue; }?>
            <option value="<?=$gpwd->loginid?>"><?=$gpwd->label?></option>
        <?php } ?>
        </select>
        <input type="submit" value="DecryptPass">
    </form>

<h3>Search</h3>

<?php if (isset($_POST['search'])){
    $params=['search'=>$_POST['search']];
    $ch = curl_init(); 
        // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/logins/search"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        // $output contains the output string 
    $output = curl_exec($ch); 
        // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
    Search: <input type="text" name="search">
    <input type="submit" value="search">
</form>

<h3>Exact Search</h3>

<?php if (isset($_POST['exactsearch'])){
    $params=['search'=>$_POST['exactsearch']];
    $ch = curl_init(); 
        // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/logins/search/exact"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        // $output contains the output string 
    $output = curl_exec($ch); 
        // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
    Search: <input type="text" name="exactsearch">
    <input type="submit" value="exactsearch">
</form>
<h3>return All Logins</h3>

<?php
    // create curl resource 
        $ch = curl_init(); 

        // set url 
        $url = $hostURL."/v1/logins/all";
       // echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
?>

<h3>Get the last added</h3>


<?php
    // create curl resource 
        $ch = curl_init(); 

        // set url 
        $url = $hostURL."/v1/logins/lastAdded/50/";
       // echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
?>

<h2>Labels/Tags</h2>

<h3>Add One Label</h3>

<?php if (isset($_POST['addLabel_Login'])){
    $params=['loginid'=>$_POST['addLabel_Login'],'label'=>$_POST['addLabel_label']];
    $ch = curl_init(); 
        // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/labels/add/"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        // $output contains the output string 
    $output = curl_exec($ch); 
        // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
    <select name="addLabel_Login">
        <?php foreach ($gpwddata as $gpwd){  ?>
            <option value="<?=$gpwd->loginid?>"><?=$gpwd->label?></option>
        <?php } ?>
    </select>
    <input type="text" name="addLabel_label">
    <input type="submit" value="addLabel">
</form>


<h3>Return All Labels</h3>

<?php
    // create curl resource 
        $ch = curl_init(); 

        // set url 
        $url = $hostURL."/v1/labels/";
       // echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
        $labeldata = json_decode($output);
        $labeldata = $labeldata->data;
?>

<h3>Return Labels for Login</h3>

<?php
if (isset($_POST['getLoginLabels_login'])){
    // create curl resource 
        $ch = curl_init(); 

        // set url 
        $url = $hostURL."/v1/labels/".$_POST['getLoginLabels_login'];
       // echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
}
?>

<form method="post">
    <select name="getLoginLabels_login">
        <?php foreach ($gpwddata as $gpwd){  ?>
            <option value="<?=$gpwd->loginid?>"><?=$gpwd->label?></option>
        <?php } ?>
    </select>
    <input type="submit" value="addLabel">
</form>

<h3>Remove One Label</h3>

<?php if (isset($_POST['removeLabel_Login'])){
    $params=['loginid'=>$_POST['removeLabel_Login'],'label'=>$_POST['removeLabel_label']];
    $ch = curl_init(); 
        // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/labels/remove/"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        // $output contains the output string 
    $output = curl_exec($ch); 
        // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
    <select name="removeLabel_Login">
        <?php foreach ($gpwddata as $gpwd){  ?>
            <option value="<?=$gpwd->loginid?>"><?=$gpwd->label?></option>
        <?php } ?>
    </select>
    <input type="text" name="removeLabel_label">
    <input type="submit" value="removeLabel">
</form>

<h3>RemoveLabelfromEvery</h3>

<?php if (isset($_POST['removeLabelEvery_label'])){
    $params=['label'=>$_POST['removeLabelEvery_label']];
    $ch = curl_init(); 
        // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/labels/removeEvery/"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
        // $output contains the output string 
    $output = curl_exec($ch); 
        // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
    <select name="removeLabelEvery_label">
        <?php foreach ($labeldata as $lbld){  ?>
            <option value="<?=$lbld?>"><?=$lbld?></option>
        <?php } ?>
    </select>
    <input type="submit" value="removeLabelfromEvery">
</form>

<h3>Search By Labels</h3>

<?php if (isset($_POST['searchByLabel_label'])){
    // create curl resource 
        $ch = curl_init(); 

        // set url 
        $url = $hostURL."/v1/labels/search/".$_POST['searchByLabel_label'];
       // echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
} ?>

<form method="post">
    <select name="searchByLabel_label">
        <?php foreach ($labeldata as $lbld){  ?>
            <option value="<?=$lbld?>"><?=$lbld?></option>
        <?php } ?>
    </select>
    <input type="submit" value="searchByLabel">
</form>

<h2>Passwords</h2>

<h3>Update Password</h3>

<?php if (isset($_POST['updatePassword_Login'])){
    $params=['loginid'=>$_POST['updatePassword_Login'],'password'=>$_POST['updatePassword_Password'],'preencrypt'=>$_POST['updatePassword_Preencrypt']];
    $ch = curl_init(); 
    // set url 
    curl_setopt($ch, CURLOPT_URL, $hostURL."/v1/logins/updatePassword"); 
    curl_setopt($ch, CURLOPT_POST, true); 
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params); 
    // $output contains the output string 
    $output = curl_exec($ch); 
    // close curl resource to free up system resources 
    curl_close($ch);   
    var_dump($output);
} ?>

<form method="post">
    <select name="updatePassword_Login">
        <?php foreach ($gpwddata as $gpwd){  ?>
            <option value="<?=$gpwd->loginid?>"><?=$gpwd->label?></option>
        <?php } ?>
    </select><br />
    New Pass: <input type="password" name="updatePassword_Password"><br />
    Preencrypt: <input type="text" name="updatePassword_Preencrypt">
    <input type="submit" value="updatePassword">
</form>

<h3>Delete Password</h3>

<?php if (isset($_POST['deletePassword_Login'])){
// create curl resource 
        $ch = curl_init(); 

        // set url 
        $url = $hostURL."/v1/logins/deleteLogin/".$_POST['deletePassword_Login'];
       // echo $url;
        curl_setopt($ch, CURLOPT_URL, $url); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Authorization:".$token));

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);   

        var_dump($output);
} ?>

<form method="post">
    <select name="deletePassword_Login">
        <?php foreach ($gpwddata as $gpwd){  ?>
            <option value="<?=$gpwd->loginid?>"><?=$gpwd->label?></option>
        <?php } ?>
    </select>
    <input type="submit" value="deletePassword">
</form>

