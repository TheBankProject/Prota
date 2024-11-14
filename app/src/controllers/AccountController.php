<?php
namespace Minuz\Prota\controllers;

use Minuz\Prota\services\Sessioner;
use Minuz\Prota\http\Request;
use Minuz\Prota\http\Response;

use Minuz\Prota\Tools\Parser;
use Minuz\Prota\attributes\Route;
use Minuz\Prota\model\Bank\Bank;
use Minuz\Prota\Repo\Repo;

class AccountController
{
    private Bank $Bank;
    public function __construct()
    {
        $this->Bank = new Bank(new Repo());
    }
    
    
    
    #[Route('/login', 'GET')]
    public function enterAccount(Request $request, Response $response): void
    {
        $authData = $request::auth();
        Parser::HydrateNulls($authData, '');

        if ( ! $acc = $this->Bank->enterAccount($authData['username'], $authData['password']) ) {
            $response::Response(400, 'Error', 'Wrong account id or password');
            return;
        }

        $this->loginProcess($request, $response, $acc->overview());
        return;
    }



    #[Route('/signup', 'POST')]
    public function createAccount(Request $request, Response $response): void
    {
        $userinfo =$request::body();
        if ( Parser::HaveEmptyVaLues($userinfo) ) {
            $response::Response(400, 'Warning', 'Please, dont put just whitespaces in any field');
    
            return;
        }

        $acc = $this->Bank::newAccount($userinfo);
        $this->loginProcess($request, $response, $acc->overview());

        return;
    }


    private function loginProcess(Request $request, Response $response, array $accOverview): void
    {
        $auth = $request::auth();
        $sessionToken = Sessioner::saveSession($auth);
        $contentResponse = [
            'Acess token' => $sessionToken,
            'Account overview' => $accOverview
        ];

        $response::Response(200, 'OK', 'You have logged in sucessfully', $contentResponse);
    
        return;
    }



}