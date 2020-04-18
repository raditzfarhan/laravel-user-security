<?php

namespace RaditzFarhan\UserSecurity\Facades;

use Illuminate\Support\Facades\Facade;

class RFAuthenticator extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'RFAuthenticator';
    }
}
