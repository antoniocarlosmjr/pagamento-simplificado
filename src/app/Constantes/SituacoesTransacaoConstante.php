<?php

namespace App\Constantes;

/**
 * Class SituacoesTransacaoEnum
 * @package App\Constantes
 * @author Antonio Martins
 */
class SituacoesTransacaoConstante implements ConstanteInterface
{
    const FINALIZADA = 'finalizada';
    const PENDENTE = 'pendente';
    const CANCELADA = 'cancelada';

    /**
     * Retorna as constantes definidas para as situações das transações.
     *
     * @return array
     * @author Antonio Martins
     *
     */
    public static function getArrayCombo(): array
    {
        return [
            'FINALIZADA' => ['id' => self::FINALIZADA, 'value' => "Finalizada"],
            'PENDENTE' => ['id' => self::PENDENTE, 'value' => "Pendente"],
            'CANCELADA' => ['id' => self::CANCELADA, 'value' => "Cancelada"],
        ];
    }

    /**
     * Retorna as constantes definidas na classe como um json.
     *
     * @return string
     * @author Antonio Martins
     *
     */
    public static function getJson(): string {
        return json_encode(self::getArrayCombo());
    }
}
