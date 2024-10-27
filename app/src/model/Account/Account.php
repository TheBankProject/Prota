<?php
namespace Minuz\Prota\model\Account;

class Account
{
    private float $balance = 0;
    

    public function __construct(
        float $balance,
        private string $name,
        private string $accountId
        
    ) {
        $this->balance = $balance;
    }


    public function __tostring(): string
    {
        return json_encode([
            'Account' => $this->name,
            'Balance' => $this->balance
        ]);
    }


    public function newLoan()
    {

    }


}