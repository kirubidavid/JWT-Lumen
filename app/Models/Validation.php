<?php

namespace App\Models;
use Validator;


class Validation
{

    protected $rules = array();
    protected $errors;

    public function validateAuthentication($request)
    {
        $authenticationRules = array('email' => 'required|email', 'password'=>'required');
        return $this->validate($request, $authenticationRules);
    }

    public function validate($data, $rules)
    {
        // make a new validator object
        $v = Validator::make($data, $rules);

        // check for failure
        if ($v->fails())
        {
            // set errors and return false
           # $this->errors = $v->errors;

          
            return false;
        }
        // validation pass
        return true;
    }

    public function errors()
    {
        return $this->errors;
    }

}
