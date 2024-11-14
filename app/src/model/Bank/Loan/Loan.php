<?php
namespace Minuz\Prota\model\Bank\Loan;

final class Loan
{
    public const PROPOSAL_VIEW = true;

    private float $paymentAmount;
    public readonly string $owner;
    public readonly string $loanID;

    public function __construct(
        private float $purchasedAmount,
        private array $portions,
        private array $appliedInterests,
    ) {
        $this->paymentAmount = array_reduce($portions, function ($carry, $portion) {
            return $carry += $portion->value;
        });
    }


    public function __serialize(): array
    {
        $serializedPortions = array_map(function ($portion) {
            return serialize($portion);
        }, $this->portions);

        $portionsQtd = count($this->portions);

        return [
            'Purchased amount'       => $this->purchasedAmount,
            'Payment amount'         => $this->paymentAmount,
            'Portions'               => $portionsQtd, 
            'Portions description'   => $serializedPortions,
            'Applied interests'      => $this->appliedInterests,
        ];
    }


    public function __unserialize(array $data): void
    {
        $serializedPrtions     = $data['Portions'];
        
        $this->purchasedAmount      = $data['Purchased amount'];
        $this->paymentAmount        = $data['Payment amount'];
        $this->appliedInterests     = $data['Applied interests'];
        $this->portions             = array_map(function ($serializedPortion) {
                                        return unserialize($serializedPortion);
                                    }, $serializedPrtions);
    }


    public function setAccID(string $accID): void
    {
        if ( isset($this->accID) ) {
            return;
        }
        $this->owner = $accID;
    }


    public function setLoanID(string $loanID): void
    {
        if ( isset($this->loanID) ) {
            return;
        }
        $this->loanID = $loanID;
    }


    // Array modeling of the object to be json encoded
    public function view(bool $proposalView = false): array
    {
        $portions = array_map(function ($portion) use ($proposalView){
            return $portion->view($proposalView);
        }, $this->portions);

        $view = [
            'Amount purchased'  => round($this->purchasedAmount, 2),
            'Portions'          => $portions,
            'Amount to pay'     => round($this->paymentAmount, 2),
            'Applied Interests' => $this->appliedInterests
        ];

        if ( $proposalView ) {
            return $view;
        }

        $metadata = [
            'Owner'     => $this->owner,
            'Loan id'   => $this->loanID
        ];
        return array_merge($metadata, $view);
    }


}