<?php

class Validation{

    function validateEmail($emailaddress){
        if (filter_var($emailaddress, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    function validateURL($url){
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        }
        return false;
    }

    function validateCurrency($currency){
        $currencies = Configuration::currencies();
        $cur = array();
        foreach ($currencies as $c){
            $cur[] = $c['iso'];
        }
        if (!in_array($currency,$cur)){
            return false;
        }
        return true;
    }

    function validateBillingPeriod($period){
        $periods = Configuration::billingPeriods();
        if (!in_array($period,$periods)){
            return false;
        }
        return true;
    }

    function validatePaymentMethod($paymentmethod){
        $paymentmethods = Configuration::paymentMethods();
        $pay = array();
        foreach ($paymentmethods as $p){
            $pay[] = $p['gateway'];
        }
        if (!in_array($paymentmethod,$pay)){
            return false;
        }
        return true;
    }

    function validatePassword($password){
        if (strlen($password) > 4){
            return true;
        }
        return false;
    }

    function dateFormat($timestamp){
        $nicedate = date("jS F Y",$timestamp);
        return $nicedate;
    }

    function dateFormatLong($timestamp){
        if ((time() - $timestamp) < 60400){
            $nicedate = date('g:ia',$timestamp);
        } else if ((time() - $timestamp) > (86400*300)){
            $nicedate = Validation::dateFormat($timestamp);
        } else {
            $nicedate = date("jS F, ga",$timestamp);
        }
        return $nicedate;
    }

    function niceURL($url){
        if (strpos($url,"//")){
            $url = substr($url,(strpos($url,"//")+2));
        } 
        if (strpos($url,"w.")){
            $url = substr($url,(strpos($url,"www.")+4));
        }
        return $url;
    }


}

?>