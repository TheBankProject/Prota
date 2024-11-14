<?php
namespace Minuz\Prota\model\Bank\Interest;

class CompleteInterestCalc implements InterestCalcBasics
{
    const INTEREST_TYPE = 'Applied to complete amount, divided by the portions';
    private float $interestPercentage;

    public function __construct(float $interestPercentage)
    {
        $this->interestPercentage = $interestPercentage;
    }


    public function calculateInterest(array $portions): array
    {
        $amount = array_sum($portions);
        $amountWithInterest = $amount * $this->interestPercentage;
        $interestValuePerPortion = $amountWithInterest / count($portions);

        $calculatedInterests = array_map(function ($portion) use ( $interestValuePerPortion) {
            return $portion + $interestValuePerPortion;
        }, $portions);

        return $calculatedInterests;
    }


    public function view(): array
    {
        return [
            'CI' => [
                'Interest type' => self::INTEREST_TYPE,
                'Interest value' => $this->interestPercentage
            ]
        ];
    }
}