<?php
namespace Minuz\Prota\model\Account;

use Minuz\Prota\model\Bank\Loan\Loan;
use Minuz\Prota\model\Bank\BankAcess;

class Account
{
    
    public function __construct(
        private BankAcess $Bank,
        public readonly string $owner,
        public readonly string $accID,
        private float $balance,
        
    ) {
    }


    public function overview(): array
    {
        return [
            'Owner' => $this->owner,
            'Account Id' => $this->accID,
            'Balance' => $this->balance,
        ];
    }


    public function requestLoan(float $amount, float $requiredPortionValue): Loan|false
    {
        $loanProposal = $this->Bank::requestLoan($this->accID, $amount, $requiredPortionValue);
        return $loanProposal;
    }


    public function acceptProposal(Loan $loan): bool
    {
        $sucess = $this->Bank::registerLoan($this->accID, $loan);
        return $sucess;
    }
}