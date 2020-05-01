<?php

namespace RaditzFarhan\UserSecurity\Traits;

use Illuminate\Support\Facades\Hash;
use RaditzFarhan\UserSecurity\Services\MnemonicService;

trait UserSecurable
{
    /**
     * Get the security record associated with the user.
     */
    public function security()
    {
        return $this->hasOne('RaditzFarhan\UserSecurity\Models\UserSecurity', 'model_id')->where('model_type', get_class($this))->withDefault();
    }

    /**
     * Create or Update user security pin.
     *
     * @param string $security_pin
     * 
     * @return void
     */
    public function updateSecurityPin($security_pin)
    {
        if ($this->security) {
            $this->security->security_pin = Hash::make($security_pin);
            $this->security->save();
        } else {
            $this->security()->create(['model_type' => get_class($this), 'security_pin' => Hash::make($security_pin)]);
        }
    }

    /**
     * Create or Update user mnemonic entropy.
     *
     * @param string $entropy
     * 
     * @return void
     */
    public function updateEntropy($entropy)
    {
        $hashed_entropy = (new MnemonicService)->hash($entropy);

        if ($this->security) {
            $this->security->mnemonic_entropy = $hashed_entropy;
            $this->security->save();
        } else {
            $this->security()->create(['model_type' => get_class($this), 'mnemonic_entropy' => $hashed_entropy]);
        }
    }

    /**
     * Create or Update multiple authenticators.
     *
     * @param array $attributes
     * 
     * @return void
     */
    public function updateMultipleAuthenticators(array $attributes)
    {
        $mnemonicService = new MnemonicService;

        $attributes = collect($attributes)->map(function ($value, $name) use ($mnemonicService) {
            if (in_array($name, ['security_pin'])) {
                return Hash::make($value);
            } elseif ($name === 'mnemonic_entropy') {
                return $mnemonicService->hash($value);
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
                foreach ($attributes->toArray() as $key => $val) {
                    $this->security->$key = $val;
                }
            }

            $this->security->save();
        } else {
            if ($attributes->count() > 0) {
                $this->security()->create(array_merge(['model_type' => get_class($this)], $attributes->toArray()));
            }
        }
    }

    /**
     * Verify user mnemonic words.
     *
     * @param array $words
     * 
     * @return boolean
     */
    public function verifyMnemonicWords(array $words)
    {
        return (new MnemonicService)->verifyByWords($words, $this->security->mnemonic_entropy);
    }
}
