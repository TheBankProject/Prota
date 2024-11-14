<?php
namespace Minuz\Prota\Repo;

use Minuz\Prota\config\ConnectionDB\ConnectionDB;

use Minuz\Prota\model\Account\Account;
use Minuz\Prota\model\Bank\BankAcess;
use Minuz\Prota\model\Bank\Loan\Loan;

class Repo implements RepoInterface
{    
    private \PDO $PDO;
    private BankAcess $BankAcess;

    public function __construct()
    {
        $this->PDO = ConnectionDB::connect();
        $this->BankAcess = new BankAcess();
    }
    

    public function acessAccount(string $accID, string $pass): Account|false
    {
        $stmt = $this->PDO->prepare(self::QUERY_ACCESS_ACCOUNT);
        $stmt->execute([
            ':accID' => $accID,
            ':pass' => $pass
        ]);
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        $acc = new Account(
            $this->BankAcess,
            $data['Owner'],
            $data['AccID'],
            $data['Balance'],
        );
        return $acc;
    }
    
    
    public function createAccount(string $owner, string $pass): Account
    {
        $accID = $this->PDO
            ->query(self::QUERY_NEXT_ACC_ID)
            ->fetch(\PDO::FETCH_ASSOC)['nextAccID'];
        $accID = str_pad($accID, 20, 0, STR_PAD_LEFT);
        $accID = "1233-$accID";

        $stmt = $this->PDO->prepare(self::QUERY_CREATE_ACCOUNT);
        $stmt->execute([
            ':accID' => $accID,
            ':owner' => $owner,
            ':pass' => $pass
        ]);

        $acc = $this->acessAccount($accID, $pass);
        return $acc;
    }


    public function haveLoan(string $accID): bool
    {
        $stmt = $this->PDO->prepare(self::QUERY_HAVE_LOAN);
        $stmt->execute([
            ':accID' => $accID,
        ]);

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result['CurrentLoan'] == 0;
    }


    public function currentLoan(string $accID): Loan|false
    {
        $stmt = $this->PDO->prepare(self::QUERY_GET_LOAN);
        $stmt->execute([
            'accID' => $accID
        ]);

        return false;
    }


    public function newLoanID(string $accID): string
    {
        $loanID = $this->PDO
            ->query(self::QUERY_NEXT_LOAN_ID)
            ->fetch(\PDO::FETCH_ASSOC)['nextLoanID'];

        return str_pad($loanID, 20, 0, STR_PAD_LEFT);
    }


    public function registerLoan(Loan $loan)
    {
        // TODO resolve this part to finish the API basics
    }


    private const QUERY_ACCESS_ACCOUNT = '
        SELECT
            a.Owner,
            a.AccID,
            a.Balance
        FROM prota_db.Accounts a
        WHERE a.AccID = :accID
            AND a.Pass = :pass;
    ';


    private const QUERY_CREATE_ACCOUNT= '
        INSERT INTO prota_db.Accounts (
            Owner, AccID, Pass
            
        )
        VALUES (:owner, :accID, :pass);
    ';


    private const QUERY_GET_LOAN = '
        SELECT
            l.PurchasedAmount,
            p.Deadline, p.Value,
            i.InterestType,
            i.InterestValue
        FROM prota_db.Loans l
        INNER JOIN prota_db.Accounts a
            ON a.AccID = l.AccID
        RIGHT JOIN prota_db.Portions p
            ON l.LoanID = p.LoanID
        WHERE a.AccID = :accID;
    ';


    private const QUERY_NEXT_LOAN_ID = '
        SELECT COUNT(l.loanID) +1 
        AS nextLoanID
        FROM prota_db.Loans l;
        ';
        
        
        private const QUERY_NEXT_ACC_ID = '
        DECLARE nextAccID CHAR(11);
        SELECT COUNT(a.AccID) +1 INTO nextAccID
        FROM prota_db.Accounts a;
    ';


    private const QUERY_HAVE_LOAN = '
        SELECT a.CurrentLoan
        FROM prota_db.Accounts a
        WHERE a.AccID = :accID;
    ';


}