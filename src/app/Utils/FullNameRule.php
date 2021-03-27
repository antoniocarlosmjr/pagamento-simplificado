<?php

namespace App\Utils;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class FullnameRule
 * @package App\Rules
 * @author Antonio Martins
 */
class FullnameRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determina as regras de validação.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if(preg_match('/^[a-zA-Z]+(?:\s[a-zA-Z]+)+$/', $value)){
            return true;
        }

        return false;
    }

    /**
     * Retorna a mensagem de validação.
     *
     * @return string
     */
    public function message()
    {
        return 'Deve ser informado o nome completo.';
    }
}
