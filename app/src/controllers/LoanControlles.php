<?php
namespace Minuz\Prota\controllers;

use Minuz\Prota\attributes\Route;
use Minuz\Prota\http\Request;
use Minuz\Prota\http\Response;

class LoanControlles
{
    #[Route('/loan', 'POST')]
    public function loanOrder(Request $request, Response $response)
    {

    }

    #[Route('/loan', 'GET')]
    public function getLoanData(Request $request, Response $response, string $id)
    {
        
    }
}