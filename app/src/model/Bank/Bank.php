<?php
namespace Minuz\PRota\model\Bank;

use Minuz\Prota\model\Account\Account;

class Bank
{
    public static function newAccount(array $userInfo): Account
    {
        $userName = $userInfo['name'];
        $userAccountId = $userInfo['account id'];
        $account = new Account();
    }
}