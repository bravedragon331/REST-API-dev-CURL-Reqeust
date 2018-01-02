<?php

class Password {

    public function __construct()
        {
            $this->cipher="AES-128-CBC";
            $this->encryptionKey = "ramesesIsD0ggy";
        }

    private function generateString($length="20"){
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function encrypt($string,$hash,$iv){

        $encryptedLoginid =  openssl_encrypt($string . $this->encryptionKey,$this->cipher,$hash,OPENSSL_RAW_DATA,$iv);
        $hmac = hash_hmac('sha256', $encryptedLoginid, $this->encryptionKey, $as_binary=true);
        return base64_encode( $iv.$hmac.$encryptedLoginid );
    }

    function StorePassword($organisationid,$groupid="0",$label,$siteid,$username,$password,$notes,$preencrypt="0"){
        if ($preencrypt){
            $isencrypted = 1;
        } else {
            $isencrypted = 0;
        }
        $string = $this->generateString();
        $passwordid = $this->generateString();
        $loginsdb = new LoginsDB;
        $loginid = $loginsdb->storeUser($organisationid,$groupid,$siteid,$label,$string,$username,$notes,$isencrypted);
        if (!$loginid){
            return false;
        }
        // Now we encrypt the loginid with the string to get the hash
        $ivlen = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);
        $storediv = base64_encode($iv);
        $hash = $this->generateString();
        $hashdb = new HashDB;
        $hashdb->storeHash($loginid,$hash,$storediv,$passwordid);
        $passdb = new PasswordDB;
        $encryptedPassword = $this->encrypt($password,$hash,$iv);
        $passdb->storePass($passwordid,$encryptedPassword);
        return $passwordid;
    }

    function updatePassword($loginid,$password,$preencrypt=0){
        // Need to ensure permissions here
        $hashdb = new HashDB;
        $passdb = new PasswordDB;
        $hashinfo = $hashdb->getHash($loginid);
        $iv = base64_decode($hashinfo['iv']);
        $hash = $hashinfo['hash'];
        $passwordid = $this->generateString();
        $encryptedPassword = $this->encrypt($password,$hash,$iv);
        $passdb->storePass($passwordid,$encryptedPassword);
        $hashdb->updatePasswordID($loginid,$passwordid);
        $isencrypted = 0;
        if ($preencrypt){
            $isencrypted = 1;
        }
        $loginsdb = new loginsDB;
        $loginsdb->updatePreencrypt($loginid,$isencrypted);
        return true;
    }

    function deletePassword($loginid){
        $organisationid = Accesscontrol::getOrganisationID();
         $loginsdb = new LoginsDB;
         $logindetails = $loginsdb->getLogin($loginid);
         if (!$logindetails){ return false; }
         $loginsdb->deletePassword($organisationid,$loginid);
         return true;
    }

    function RetrieveLogin($loginid){
        $loginsdb = new LoginsDB;
        $userGroups = new userGroups;
        $groups = new groups;
        $labels = new labels;
        $mygroups = $userGroups->getGroupsArray(Accesscontrol::getInternalID());
        $login = $loginsdb->getLogin($loginid);
        if (!$login){ return false; }
        $logininfo = array();
        $logininfo['loginid'] = $login['loginid'];
        $logininfo['relid'] = $login['relid'];
        if ($logininfo['relid']){
            $logininfo['site'] = $loginsdb->getSiteUrl($logininfo['relid']);
        }
        $logininfo['username'] = $login['username'];
        $logininfo['label'] = $login['label'];
        $logininfo['groupid'] = $login['groupid'];
        if (($logininfo['groupid'] > 0)){
            if (in_array($logininfo['groupid'],$mygroups)) 
            { 
                return false;
            }
            $logininfo['group'] = $groups->getGroup($logininfo['groupid']);
        } else {
            $logininfo['group'] = "Uncategorised";
        }
        $logininfo['preencrypt'] = false;
        if ($login['preencrypt']){
            $logininfo['preencrypt'] = true;
        }             
        $logininfo['notes'] = $login['notes'];
        $logininfo['dateAdded'] = $login['dateAdded'];
        $logininfo['dateAddedFormat'] = date("jS F Y",$login['dateAdded']);
        $logininfo['labels'] = $labels->returnLoginLabels($loginid);
        return $logininfo;
    }

    function RetrievePassword($loginid,$preencrypt=false){
        $loginsdb = new LoginsDB;
        $logindetails = $loginsdb->getLogin($loginid);
        $string = $logindetails['randomString'];
        $hashdb = new HashDB;
        $hashinfo = $hashdb->getHash($loginid);

        if ($logindetails['preencrypt'] == 1){
            if (!$preencrypt) { return false; }
            // decrypt password here
        }

        $iv = base64_decode($hashinfo['iv']);
        $hash = $hashinfo['hash'];

        $passdb = new PasswordDB;
        $password = base64_decode($passdb->getPassword($hashinfo['passwordid']));

        $ivlen = openssl_cipher_iv_length($this->cipher);
        $hmac = substr($password, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($password, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $this->cipher, $hash, OPENSSL_RAW_DATA, $iv);

        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->encryptionKey, $as_binary=true);
        if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
        {
            $position = strpos($original_plaintext,$this->encryptionKey);
            $password = substr($original_plaintext,0,$position);
            return $password;
        }
        return false;
    }

    function getGroupLogins($groupid){
        $organisationid = Accesscontrol::getOrganisationID();
        $loginsdb = new LoginsDB;
        return $this->formatLogins($loginsdb->getGroupLogins($organisationid,$groupid));
    }

    function returnLoginsinGroup($groupid){
        $password = new Password;
        return $this->formatLogins($password->getGroupLogins($groupid));
    }

   function returnLoginsforURL($url){
        $loginsdb = new LoginsDB;
        $siteid = $loginsdb->getSiteId($url);
        return $this->formatLogins($this->returnLoginsforSiteID($siteid));
   }

    function returnLoginsforSiteID($siteid){
        $loginsdb = new LoginsDB;
        return $loginsdb->getLoginsbySite(Accesscontrol::getOrganisationID(),$siteid);
    }

    function search($string){
        $loginsdb = new LoginsDB;
        return $this->formatLogins($loginsdb->search(Accesscontrol::getOrganisationID(),$string));
    }

    function exactsearch($string){
       $loginsdb = new LoginsDB;
        return $this->formatLogins($loginsdb->searchExact(Accesscontrol::getOrganisationID(),$string));
    }

    function returnAllLogins(){
        $loginsdb = new LoginsDB;
        //return $this->formatLogins($loginsdb->all($_SESSION['organisationid']));
        return $this->formatLogins($loginsdb->all(Accesscontrol::getOrganisationID()));
    }

    function returnLoginsbyLabel($label){
        $loginsdb = new LoginsDB;
        return $this->formatLogins($loginsdb->returnLoginsforLabel(Accesscontrol::getOrganisationID(),$label));
    }
    
    function formatLogins($logindetails){
        $loginsdb = new LoginsDB;
        $userGroups = new userGroups;
        $groups = new groups;
        $mygroups = $userGroups->getGroupsArray(Accesscontrol::getInternalID());
        $newlogins = array();
        foreach ($logindetails as $login){
            $logininfo = array();
            $logininfo['loginid'] = $login['loginid'];
            $logininfo['relid'] = $login['relid'];
            if ($logininfo['relid']){
                $logininfo['site'] = $loginsdb->getSiteUrl($logininfo['relid']);
            }
            $logininfo['username'] = $login['username'];
            $logininfo['label'] = $login['label'];
            $logininfo['groupid'] = $login['groupid'];
            if (($logininfo['groupid'] > 0)){
                if (in_array($logininfo['groupid'],$mygroups)) 
                { 
                    continue; 
                }
                $logininfo['group'] = $groups->getGroup($logininfo['groupid']);
            } else {
                $logininfo['group'] = "Uncategorised";
            }
            $logininfo['preencrypt'] = false;
            if ($login['preencrypt']){
                $logininfo['preencrypt'] = true;
            }             
            $logininfo['notes'] = $login['notes'];
            $logininfo['dateAdded'] = $login['dateAdded'];
            $logininfo['dateAddedFormat'] = date("jS F Y",$login['dateAdded']);
            $newlogins[] = $logininfo;
        } 
        return $newlogins;
    }
}

?>