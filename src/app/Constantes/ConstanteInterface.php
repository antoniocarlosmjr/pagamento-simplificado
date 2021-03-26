<?php


namespace App\Constantes;

/**
 * Interface ConstanteInterface
 * @package App\Constantes
 * @author Antonio Martins
 */
interface ConstanteInterface
{
    /**
     * Retorna as constantes definidas na classe como um array que pode montar um
     * combo, select, etc.
     *
     * @return mixed
     * @author Antonio Martins
     *
     */
    public static function getArrayCombo(): array;

    /**
     * Retorna as constantes definidas na classe como um json.
     *
     * @return string
     * @author Antonio Martins
     *
     */
    public static function getJson(): string;
}
