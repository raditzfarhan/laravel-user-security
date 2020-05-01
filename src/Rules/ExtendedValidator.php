<?php

declare(strict_types=1);

namespace RaditzFarhan\UserSecurity\Rules;

use Illuminate\Support\Arr;
use RaditzFarhan\UserSecurity\Models\UserSecurity;
use RaditzFarhan\UserSecurity\Services\MnemonicService;

class ExtendedValidator
{
    public function mnemonic($attribute, $value, $parameters, $validator)
    {
        $mnemonicService = new MnemonicService();

        $mnemonic_words = Arr::get($validator->getData(), 'mnemonic_words');
        $mnemonic_entropy = Arr::get($validator->getData(), 'mnemonic_entropy');

        try {
            $mnemonic = $mnemonicService->words($mnemonic_words);

            if ($mnemonic->entropy === $mnemonic_entropy) {
                return true;
            }
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }

        return false;
    }

    public function mnemonicExists($attribute, $value, $parameters, $validator)
    {
        $mnemonicService = new MnemonicService();

        if ($attribute === 'mnemonic_words') {
            $mnemonic = $mnemonicService->words($value);
            $hashed_mnemonic = $mnemonicService->hash($mnemonic->entropy);

            return !UserSecurity::where('mnemonic_entropy', $hashed_mnemonic)->count();
        } elseif ($attribute === 'mnemonic_entropy') {
            $hashed_mnemonic = $mnemonicService->hash($value);

            return !UserSecurity::where('mnemonic_entropy', $hashed_mnemonic)->count();
        }

        return true;
    }
}
