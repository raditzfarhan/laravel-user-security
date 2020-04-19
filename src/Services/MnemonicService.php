<?php

namespace RaditzFarhan\UserSecurity\Services;

use FurqanSiddiqui\BIP39\BIP39;
use RaditzFarhan\UserSecurity\Models\UserSecurity;

class MnemonicService
{
    /**
     * Generate mnemonic object.
     *
     * @return FurqanSiddiqui\BIP39\Mnemonic
     */
    public function generate()
    {
        $word_count = config('rfauthenticator.mnemonic.word_count');

        $mnemonic = BIP39::Generate($word_count);
        $hashed_entropy = self::hash($mnemonic->entropy);

        // ensure the mnemonic is unique
        while (UserSecurity::where('mnemonic_entropy', $hashed_entropy)->first()) {
            $mnemonic = BIP39::Generate($word_count);
            $hashed_entropy = self::hash($mnemonic->entropy);
        }

        return $mnemonic;
    }

    /**
     * Use mnemonic codes to find entropy.
     *
     * @return FurqanSiddiqui\BIP39\Mnemonic
     */
    public function words(array $words)
    {
        return BIP39::Words($words);
    }

    /**
     * Generate Mnemonic using specified Entropy.
     *
     * @return FurqanSiddiqui\BIP39\Mnemonic
     */
    public function entropy($entropy)
    {
        return BIP39::Entropy($entropy);
    }

    public function hash($value)
    {
        $hash_key = config('rfauthenticator.key');
        $hash_algo = config('rfauthenticator.algo');

        return hash($hash_algo, $value . $hash_key);
    }
}
