<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class CarteiraException
 * @package App\Exceptions
 * @author Antonio Martins
 */
class CarteiraException extends Exception
{
    /**
     * CarteiraException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = 'Erro ao realizar ação em carteira',
        int $code = JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
