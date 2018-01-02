<?php
class AbstractMiddleware{    

    public function __construct(){
        //Output::Error("Your token has expired");
        $headers = apache_request_headers();
        if(isset($headers['Authorization'])){
            if (Accesscontrol::isTokenValid($headers['Authorization']) == 'expired'){
                Output::Error("Your token has expired");
            }
            else{
                if(Accesscontrol::checkPermissions())
                    Output::Error("Permission is not allowed");
            }
        }else{
            Output::NotFound("Illegal Activity");
        }
    }
}
?>