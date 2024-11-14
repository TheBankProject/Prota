<?php
namespace Minuz\Prota\services;

use Minuz\Prota\config\Cache\CacheExpires;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Minuz\Prota\model\Bank\Loan\Loan;

session_start();
class Sessioner
{
    public static function saveSession(array $auth): string
    {
        $acessToken = JWT::encode($auth, $_ENV['JWT_KEY'], 'HS256');
        $_SESSION['authData'] = $acessToken;
        $_SESSION['last_activity'] = time();
        return $acessToken;
    }


    public static function recoverToken(): string|false
    {
        
        if ( ! isset($_SESSION['last_activity']) ) {
            return false;
        }
        
        $inactive = time() - $_SESSION['last_activity'];
        if ( $inactive >=CacheExpires::fast ) {
            unset($_SESSION['last_activity'], $_SESSION['authData']);
            return false;
        }
        return $_SESSION['authData'];
    }


    public static function recoverSession(): \stdClass|false
    {
        try {
            $auth = JWT::decode($_SESSION['authData'], new Key($_ENV['JWT_KEY'], 'HS256'));
        } catch (\UnexpectedValueException $e) {
            return false;
        }
        
        return $auth;
    }


    public static function saveLoanProposal(Loan $loanProposal): void
    {
        $_SESSION[$loanProposal->loanID] = serialize($loanProposal);
        $_SESSION['loan_created_at'] = time();
        return;
    }


    public static function recoverLoanProposal(string $loanID): Loan|false
    {
        if ( ! array_key_exists($loanID, $_SESSION) ) {
            return false;
        }

        $creeatedTimeAgo = time() - $_SESSION['loan_created_at'];
        if ( $creeatedTimeAgo >=CacheExpires::fast ) {
            unset($_SESSION['loan_created_at'], $_SESSION[$loanID]);
            return false;
        }
        
        $loanProposal = unserialize($_SESSION[$loanID]);
        return $loanProposal;
    }


    public static function getLoanProposal(string $loanID): Loan|false
    {
        $loan = self::recoverLoanProposal($loanID);
        unset($_SESSION['loan_created_at'], $_SESSION[$loanID]);

        return $loan;
    }
}