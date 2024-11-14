<?php
namespace Minuz\Prota\Repo;

use Minuz\Prota\model\Account\Account;
use Minuz\Prota\model\Bank\Loan\Loan;

interface RepoInterface
{
    public function acessAccount(string $accID, string $pass): Account|false;

    public function createAccount(string $owner, string $password): Account;

    public function currentLoan(string $accID):Loan|false;

    public function haveLoan(string $accID): bool;

    public function newLoanID(string $accID): string;

    public function registerLoan(Loan $loan);
}