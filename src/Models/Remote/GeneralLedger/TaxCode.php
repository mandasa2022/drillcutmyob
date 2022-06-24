<?php

namespace Mandasa\Drillcutmyob\Models\Remote\GeneralLedger;

use Mandasa\Drillcutmyob\Models\BaseModel as Model;

class TaxCode extends Model
{
    public $endpoint = '/GeneralLedger/TaxCode';
 
    public function whereCode($value) 
    {
        $this->endpoint = $this->endpoint."/?\$filter=Code%20eq%20'".$value."'";
        return $this;
    }

    public function whereType($value) 
    {
        $this->endpoint = $this->endpoint."/?\$filter=Code%20eq%20'".$value."'";
        return $this;
    }
}