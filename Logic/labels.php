<?php

class labels {

    public function addLabel($loginid,$label){
        // we need to check they have access to the login
        $logins = new logins;
        $logininfo = $logins->returnLogin($loginid);
        if (!$logininfo) { return false; }
        $loginsdb = new LoginsDB;
        // now we split by label
        $labels = $this->multiexplode(array(",",".","|",":"," "),$label);
        foreach ($labels as $label){
            if (strlen($label) < 2) { continue; }
            $x = 1;
            $loginsdb->addLabel($loginid,$label,Accesscontrol::getOrganisationID());
        }
        if ($x == 1) {
            return true;
        }
        return false;
    }

    public function removeLabel($loginid,$label){
        // we need to check they have access to the login
        $logins = new logins;
        $logininfo = $logins->returnLogin($loginid);
        if (!$logininfo) { return false; }
        $loginsdb = new LoginsDB;
        // now we split by label
        $labels = $this->multiexplode(array(",",".","|",":"," "),$label);
        foreach ($labels as $label){
            if (strlen($label) < 2) { continue; }
            $x = 1;
            $loginsdb->removeLabel($loginid,$label,Accesscontrol::getOrganisationID());
        }
        if ($x == 1) {
            return true;
        }
        return false;
    }

    function multiexplode ($delimiters,$string) {
        $ready = str_replace($delimiters, $delimiters[0], $string);
        $launch = explode($delimiters[0], $ready);
        return  $launch;
    }

    public function removeLabelfromEvery($label){
        $logins = $this->searchByLabel($label);
        foreach ($logins as $l){
            $this->removeLabel($l['loginid'],$label);
        }
        return true;
    }

    public function searchByLabel($label){
        $password = new Password;
        return $password->returnLoginsbyLabel($label);
    }

    public function returnLabels(){
        $loginsdb = new LoginsDB;
        $alllabels = $loginsdb->returnLabels(Accesscontrol::getOrganisationID());
        return $alllabels;
    }

    public function returnLoginLabels($loginid){
        $loginsdb = new LoginsDB;
        $alllabels = $loginsdb->returnLoginLabels(Accesscontrol::getOrganisationID(),$loginid);
        return $alllabels;
    }

    

}

?>