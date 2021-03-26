<?php

namespace App\Constantes;

/**
 * Class TiposUsuarios
 * @package App\Constantes
 * @author Antonio Martins
 */
class TiposUsuariosConstante implements ConstanteInterface
{
    const USUARIO_COMUM = 'comum';
    const USUARIO_LOJISTA = 'lojista';

    /**
     * Retorna as constantes definidas para os tipos de usuarários.
     *
     * @return array
     * @author Antonio Martins
     *
     */
    public static function getArrayCombo(): array
    {
        return [
            'USUARIO_COMUM' => ['id' => self::USUARIO_COMUM, 'value' => "Comum"],
            'USUARIO_LOJISTA' => ['id' => self::USUARIO_LOJISTA, 'value' => "Lojista"],
        ];
    }

    /**
     * Retorna as constantes definidas na classe como um json.
     *
     * @return string
     * @author Antonio Martins
     *
     */
    public static function getJson(): string
    {
        return json_encode(self::getArrayCombo());
    }
}
