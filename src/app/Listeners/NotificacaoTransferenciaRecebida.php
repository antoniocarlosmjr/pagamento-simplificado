<?php

namespace App\Listeners;

use App\Events\TransferenciaRecebida;
use App\Exceptions\TransacaoException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

/**
 * Class NotificacaoTransferenciaRecebida
 * @author Antonio Martins
 */
class NotificacaoTransferenciaRecebida
{
    const URL_NOTIFICACAO_TRANSFERENCIA = 'https://run.mocky.io/v3/b19f7b9f-9cbf-4fc6-ad22-dc30601aec04';
    const MENSAGEM_ENVIADA = 'Enviado';

    /**
     * Realiza o processo de notificação de transferencia recebida para
     * um usuário beneficiário.
     *
     * @param TransferenciaRecebida $event
     * @return void
     * @throws RequestException|TransacaoException
     * @author Antonio Martins
     */
    public function handle(TransferenciaRecebida $event)
    {
        $usuario = $event->usuarioBeneficiario;
        $resposta = Http::post(self::URL_NOTIFICACAO_TRANSFERENCIA, $usuario->toArray());
        $resposta->throw();

        if ($resposta['message'] != self::MENSAGEM_ENVIADA) {
            throw new TransacaoException('Notificação de transação recebida não pode ser enviada');
        }
    }

}
