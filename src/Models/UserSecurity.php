<?php

namespace RaditzFarhan\UserSecurity\Models;

use Illuminate\Database\Eloquent\Model;

class UserSecurity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'model_type', 'security_pin', 'mnemonic_entropy', '2fa_key'
    ];
}
