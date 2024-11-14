<?php
namespace Minuz\Prota\model\Bank;

use Minuz\Prota\model\Account\Account;
use Minuz\Prota\model\Bank\Interest\CompleteInterestCalc;
use Minuz\Prota\model\Bank\Interest\CompositeInterestCalc;
use Minuz\Prota\model\Bank\Interest\MonthlyInterestCalc;
use Minuz\Prota\model\Bank\Loan\Loan;
use Minuz\Prota\model\Bank\LoanBuilder\LoanBuilder;
use Minuz\Prota\Repo\RepoInterface;

class Bank
{
    protected static \DateInterval $PORTION_INTERVAL;
    protected const MONTH_FEE = 0.04;
    protected const INTEGRAL_FEE = 0.12;
    
    protected static RepoInterface $Repo;
    
    public function __construct(RepoInterface $repo)
    {
        self::$PORTION_INTERVAL = new \DateInterval('P1M');
        self::$Repo = $repo;
    }


    public static function newAccount(array $userinfo): Account
    {
        $acc = self::$Repo->createAccount($userinfo['owner'], $userinfo['password']);
        
        return $acc;        
    }


    public static function enterAccount(string $accID, string $password): Account
    {
        $acc = self::$Repo->acessAccount($accID, $password);        
        return $acc;
    }


    public static function requestLoan(string $accID, float $purchaseAmount, float $requiredPortionValue): Loan|false
    {
        $haveActiveLoan = self::$Repo->haveLoan($accID);
        if ( $haveActiveLoan ) {
            return false;
        }
        
        $loanID = self::$Repo->newLoanID($accID);
        $loanBuilder = new LoanBuilder(
            $purchaseAmount,
            $requiredPortionValue,
            new CompositeInterestCalc(
                new MonthlyInterestCalc(self::MONTH_FEE),
                new CompleteInterestCalc(self::INTEGRAL_FEE)
            ),
            self::$PORTION_INTERVAL
        );
        
        $loanProposal = $loanBuilder->recoverLoanProposal();
        $loanProposal->setLoanID($loanID);
        
        return $loanProposal;
    }


    public static function registerLoan(string $accID, Loan $loan): bool
    {
        $sucess = self::$Repo->registerLoan($loan);

        return $sucess;
    }
}