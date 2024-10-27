<?php
namespace Minuz\Prota\controllers;

use Minuz\Prota\attributes\Route;
use Minuz\Prota\http\Request;
use Minuz\Prota\http\Response;

use Minuz\Prota\apis\GovAPI;
use Minuz\PRota\model\Bank\Bank;

class AccessController
{
    #[Route('/signup', 'POST')]
    public function createAccount(Request $request, Response $response): void
    {
        $userInfo =$request::body();
        if (! GovAPI::checkData($userInfo)) {
            return $response::Response(200, 'Bad info', 'Your information about yourself dont match up with our security');
        }

        $acc = Bank::newAccount($userInfo);
        Bank
    }



    public function enterAccount(Request $request, Response $response): void
    {

    }
}