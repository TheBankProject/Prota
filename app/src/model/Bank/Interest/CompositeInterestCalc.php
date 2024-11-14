<?php
namespace Minuz\Prota\model\Bank\Interest;

use Minuz\Prota\model\Bank\Interest\InterestCalcBasics;

class CompositeInterestCalc implements InterestCalcBasics
{
    private array $interestCalculators;
    public function __construct(InterestCalcBasics ...$interestCalculators)
    {
        $this->interestCalculators = $interestCalculators;
    }


    public function calculateInterest(array $portions): array
    {
        $calculatedInterests = [];

        foreach ( $this->interestCalculators as $interestCalculator ) {
            $calculatedInterests += array_map(function ($interest, $portion) {
                return $portion + $interest;
            }, $interestCalculator->calculateInterest($portions), $portions);
        }
        return $calculatedInterests;
    }


    public function view(): array
    {
        $compositeInterestView = [];

        foreach ( $this->interestCalculators as $interestCalculator ) {
            $compositeInterestView = array_merge($compositeInterestView, $interestCalculator->view());
        }

        return $compositeInterestView;
    }
}