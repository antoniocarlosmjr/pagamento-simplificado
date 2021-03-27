<?php

namespace App\Http\Controllers;

use App\Models\Carteira;

/**
 * Class CarteiraController
 * @package App\Http\Controllers
 * @author Antonio Martins
 */
class CarteiraController extends GenericoController
{
    protected $model;

    /**
     * CarteiraController constructor.
     * @param Carteira $carteiraModel
     */
    public function __construct(Carteira $carteiraModel)
    {
        $this->model = $carteiraModel;
    }
}
