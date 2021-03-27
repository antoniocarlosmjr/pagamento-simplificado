<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Throwable;

/**
 * Class TransacaoException
 * @package App\Exceptions
 * @author Antonio Martins
 */
class TransacaoException extends Exception
{
    /**
     * TransacaoException constructor.
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(
        string $message = 'Erro ao realização ação em transação',
        int $code = JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
