<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Queue\SerializesModels;

/**
 * Class TransferenciaRealizada
 * @author Antonio Martins
 */
class TransferenciaRecebida
{
    use SerializesModels;

    public User $usuarioBeneficiario;

    /**
     * TransacaoRecebida constructor.
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->usuarioBeneficiario = $user;
    }
}
