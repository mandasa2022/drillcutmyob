<?php

namespace Mandasa\Drillcutmyob\Models\Remote;

use Mandasa\Drillcutmyob\Models\BaseModel as Model;

class CompanyFile extends Model
{
    //Base URL for company file is default so we override
    public $endpoint = '';

    public function __construct() {
        parent::__construct();
        $this->baseurl = 'https://api.myob.com/accountright';
    }
}