<?php

namespace RaditzFarhan\UserSecurity;

use Illuminate\Support\Str;

class RFAuthenticator
{
    public $mnemonic;

    public function test()
    {
        echo 'test';
    }

    public function __call($method, $arguments)
    {
        $property_name = strtolower(Str::snake($method));

        if (property_exists($this, $property_name)) {
            $reformat_property_name = ucfirst(Str::camel($method));
            $service_name = '\\RaditzFarhan\\UserSecurity\\Services\\' . $reformat_property_name . 'Service';
            $this->$property_name = new $service_name();

            return $this->$property_name;
        }
    }
}
