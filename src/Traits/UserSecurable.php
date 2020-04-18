<?php

namespace RaditzFarhan\UserSecurity\Traits;

use Illuminate\Support\Facades\Hash;

trait UserSecurable
{
    /**
     * Get the phone record associated with the user.
     */
    public function security()
    {
        return $this->hasOne('RaditzFarhan\UserSecurity\Models\UserSecurity', 'model_id')->where('model_type', get_class($this));
    }

    public function updateSecurityPin($security_pin)
    {
        if ($this->security) {
            $this->security->security_pin = Hash::make($security_pin);
            $this->security->save();
        } else {
            $this->security()->create(['model_type'=>get_class($this), 'security_pin'=>Hash::make($security_pin)]);
        }
    }

    public function updateEntropy($entropy)
    {
        if ($this->security) {
            $this->security->entropy = Hash::make($entropy);
            $this->security->save();
        } else {
            $this->security()->create(['model_type'=>get_class($this), 'entropy'=>Hash::make($entropy)]);
        }
    }

    public function updateMultipleAuthenticators($attributes)
    {
        $attributes = collect($attributes)->map(function ($value, $name) {
            if (in_array($name, ['security_pin', 'mnemonic_entropy'])) {
                return Hash::make($value);
            } elseif ($name === '2fa_key') {
                return encrypt($value);
            }

            return $value;
        });

        if ($this->security) {
            $fillable = collect($this->security->getFillable());

            // remove unwanted fields
            $attributes = $attributes->reject(function ($value, $name) use ($fillable) {
                if (!$fillable->contains($name)) {
                    return true;
                }
            });

            if ($attributes->count() > 0) {
                foreach ($attributes->toArray() as $key=>$val) {
                    $this->security->$key = $val;
                }
            }

            $this->security->save();
        } else {
            if ($attributes->count() > 0) {
                $this->security()->create(array_merge(['model_type'=>get_class($this)], $attributes->toArray()));
            }
        }
    }
}
