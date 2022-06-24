<?php

namespace Mandasa\Drillcutmyob\Models\Remote;

use Mandasa\Drillcutmyob\Models\BaseModel as Model;

class Myob extends Model
{
    public $endpoint;
    public $data;

    public function __construct($endpoint, $data) {
        parent::__construct();
        $this->endpoint = $endpoint;
        $this->data = $data;
    }
}