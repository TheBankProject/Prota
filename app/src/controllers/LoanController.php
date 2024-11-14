<?php
namespace Minuz\Prota\controllers;

use Minuz\Prota\attributes\Route;

use Minuz\Prota\http\Request;
use Minuz\Prota\http\Response;

use Minuz\Prota\Repo\Repo;
use Minuz\Prota\services\Sessioner;
use Minuz\Prota\Tools\Parser;

use Minuz\Prota\model\Account\Account;
use Minuz\Prota\model\Bank\Bank;
use Minuz\Prota\model\Bank\Loan\Loan;

class LoanController
{
    private Bank $Bank;
    public function __construct()
    {
        $this->Bank = new Bank(new Repo());
    }


    // Recieves a loan request with key info 'Amount' and 'Required portion value'.
    // Checks via 'enterAccountProcess' method if the use is loggd,
    // call for the model wich calculates an serializesa a loan proposal
    // | | |
    // V V V
    #[Route('/loan', 'POST')]
    public function loanOrder(Request $request, Response $response): void                     
    {
        if ( ! $acc = $this->enterAccountProcess($request, $response) ) {
            return;
        }
        
        $loanRequest = $request::body();
        Parser::HaveValues($loanRequest);
        
        if ( ! $loanRequest || ! $loanRequest['Amount'] || ! $loanRequest['Required portion value'] ) {
            $response::Response(400, 'Warning', 'Invalid info');
            return;
        }
        
        $loanProposal = $acc->requestLoan(
            $loanRequest['Amount'],
            $loanRequest['Required portion value']
        );

        if ( ! $loanProposal ) {
            $response::Response(
                400,
                'Loan request',
                'You can\'t request a loan right now'
            );
            return;
        }
        Sessioner::saveLoanProposal($loanProposal);
        
        $loanID = $loanProposal->loanID;
        $response::Response(201, 'OK', 'Loan request recieved, check it on the link ', [
            'Loan id' => $loanProposal->loanID
        ], header: ['Location' => "https://localhost:8080/loan/$loanID"]);
        
        return;
    }
    
    
    #[Route('/loan/{id}', 'GET')]
    public function loanView(Request $request, Response $response, string $loanID): void
    {
        $loanProposal = Sessioner::recoverLoanProposal($loanID);
        if ( ! $loanProposal ) {
            $response::Response(
                400,
                'Proposal',
                'This prpoposal don\'t exist or has expired. Please, try again'
            );
            return;
        }
        
        $response::Response(
            200,
            'OK',
            'This is our proposal',
            ['Proposal' => $loanProposal->view(Loan::PROPOSAL_VIEW)]
        );
    }


    #[Route('/accept/{id}?{query}', 'GET')]
    public function accept(Request $request, Response $response, string $id, array $query): void
    {
        if ( ! $acc = $this->enterAccountProcess($request, $response) ) {
            return;
        }
        
        $accept = $query['accept'] == 'true';
        if ( ! $accept ) {
            $response::Response(200, 'OK', 'Thanks for the choice');
            return;
        }
        
        $loanProposal = Sessioner::getLoanProposal($id);
        $acc->acceptProposal($loanProposal);
    }
    
    
    
    private function enterAccountProcess(Request $request, Response $response): bool|Account
    {
        $userToken = $request::session();
        $sessionToken = Sessioner::recoverToken();

        if ( !$sessionToken ) {
            $response::Response(400, 'Warning', 'Your session has expired, please make a new login');
            return false;
        }
        if ( $userToken != $sessionToken) {
            $response::Response(400, 'Warning', 'Your acess token is invalid');
            return false;
        }
        
        $sessionInfo = Sessioner::recoverSession();
        $acc = $this->Bank::enterAccount($sessionInfo->username, $sessionInfo->password);
        return $acc;
    }
}