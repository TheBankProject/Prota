<?php
namespace Minuz\Prota\model\Bank\Interest;

use Minuz\Prota\model\Bank\Interest\InterestCalcBasics;

class MonthlyInterestCalc implements InterestCalcBasics
{
    const INTEREST_TYPE = 'Applied by each portion';
    
    private float $interestPercentage;
    public function __construct(float $interestPercentage)
    {
        $this->interestPercentage = $interestPercentage;
    }


    public function calculateInterest(array $portions): array
    {
        $calculatedInterests = [];
        foreach ( $portions as $portion ) {
            $calculatedInterests[] = $portion * (1+ $this->interestPercentage);
        }

        return $calculatedInterests;
    }


    public function view(): array
    {
        return [
            'MI' => [
                'Interest type' => self::INTEREST_TYPE,
                'Interest value' => $this->interestPercentage
            ]
        ];
    }
}