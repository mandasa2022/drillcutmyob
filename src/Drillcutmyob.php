<?php

namespace Mandasa\Drillcutmyob;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Mandasa\Drillcutmyob\Models\Remote\Myob;
use Mandasa\Drillcutmyob\Request\MyobRequest;
use Mandasa\Drillcutmyob\Authentication\MyobAuthenticate;
use Mandasa\Drillcutmyob\Models\Configuration\MyobConfiguration;

class Drillcutmyob
{
    public $authenticate; 
    public $myobRequest;

    public function __construct() 
    {
        $this->myobRequest = new MyobRequest;
        $this->authenticate = new MyobAuthenticate($this->myobRequest);
    }
    
    /**
     * Return the MYOB authentication class for usage
     *
     * @return MyobAuthenticate
     */
    public function authenticate() 
    {
        return $this->authenticate;
    }

    /**
     * Take a model that extends the base model.
     * Create that model, and then load the defaults
     *
     * @return MyobConfiguration || bool,
     */
    public function of($model) 
    {
        if($this->preflight()) {
            return App::make($model);
        }
    }

    /**
     * Take a model that extends the base model.
     * And attempt to persist to the database
     *
     * @return MyobConfiguration || bool,
     */
    public function save($model) 
    {
        if($this->preflight()) {
            return $model->post();
        }
    }

    /**
     * Send a raw GET request
     *
     * @return MyobConfiguration || bool,
     */
    public function rawGet($endpoint)
    {        
        if($this->preflight()) {
            $myob = new Myob($endpoint, []);
            return  $myob->get();
        }
    }

    /**
     * Send a raw POST request
     *
     * @return MyobConfiguration || bool,
     */
    public function rawPost($endpoint, $data)
    {
        if($this->preflight()) {
            $myob = new Myob($endpoint, $data);
            return $myob->post();
        }
    }

    /**
     * Preflight check for any request to see if we need to refresh the token
     *
     * @return MyobConfiguration || bool,
     */
    private function preflight() 
    {
        if(MyobConfiguration::first() && Carbon::now() > MyobConfiguration::first()->expires_at) {
            return $this->authenticate->getRefreshToken();
        } else {
            return true;
        }
    }
}
