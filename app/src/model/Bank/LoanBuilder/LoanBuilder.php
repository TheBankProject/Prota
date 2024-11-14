<?php
namespace Minuz\Prota\model\Bank\LoanBuilder;

use Minuz\Prota\model\Bank\Loan\Loan;
use Minuz\Prota\model\Bank\Interest\InterestCalcBasics;
use Minuz\Prota\model\Bank\Loan\Portion\Portion;


class LoanBuilder
{
    const ACCEPTANCE_MARGIN = 0.05;

    private static \DateINterval $PORTION_INTERVAL;
    private Loan $loanProposal;

    public function __construct(
        float $purchasedAmount,
        float $requiredPortionValue,
        private InterestCalcBasics $compositeInterestCalc,
        \DateInterval $PORTION_INTERVAL

    ) {
        self::$PORTION_INTERVAL = $PORTION_INTERVAL;
        $this->buildLoan($purchasedAmount, $requiredPortionValue);
    }


    // Unifies all the processes, creating a Loan object
    private function buildLoan(float $purchasedAmount, float $requiredPortionValue): void
    {
        $portions = $this->buildPortions($purchasedAmount, $requiredPortionValue);
        $appliedInterests = $this->compositeInterestCalc->view();

        $this->loanProposal = new Loan($purchasedAmount, $portions, $appliedInterests);
    }


    // Constructs all the portions based on the amount and portion required by the client.
    // Here comes the calculation, adjust of numbre of portions and the interests
    // and the creation of the Portion objects.
    private function buildPortions(float $amount, float $requiredPortionValue): array
    {
        $portions = $this->calculatePortions($amount, $requiredPortionValue);
        $this->adjustPortions($amount, $requiredPortionValue,$portions);
        $this->applyInterests($portions);

        $nextDeadline = new \DateTimeImmutable('now');
        $portions = array_map(
            function ($portion) use (&$nextDeadline) {
                $nextDeadline = $nextDeadline->add(self::$PORTION_INTERVAL);
                return new Portion($nextDeadline, $portion); 
        }, $portions);

        return $portions;
    }


    // Calculates the amoutn for each portion based on the amount purchased, dividiing and rounding the required value
    // to the next integer.
    private function calculatePortions(float $amount, ?float $requiredPortionValue = null, ?int $portionQtd = null): array
    {
        if ( ! $portionQtd ) {
            $portionQtd = ceil($amount / $requiredPortionValue);
        }
        $valuePerPortion = $amount / $portionQtd;

        $portions = array_fill(0, $portionQtd, $valuePerPortion);
        return $portions;
    }
    
    
    // Adjusts portions with the accepntance margin. While the portion value is greater than the delimited, it will grow
    // the number of portions to lower the portion value.
    private function adjustPortions(float $amount, float $requiredPortionValue, array &$portions): void
    {
        $portionValue = $portions[0];
        
        while ( $this->acceptanceMargin($portionValue, $requiredPortionValue) > self::ACCEPTANCE_MARGIN ) {
            $portions = $this->calculatePortions($amount, $portionValue, count($portions)+1);
            $portionValue = $portions[0];
        }
        
        return;
    }


    // The function that applies the interest over all the portions
    private function applyInterests(&$portions): void
    {
        $portions = $this->compositeInterestCalc->calculateInterest($portions);
        return;
    }


    // Calculates the corresponding acceptance margin percentage
    private function acceptanceMargin(float $portionValue, float $requiredPortionValue): float
    {
        $margin = (100* $portionValue / $requiredPortionValue -100) *1;
        return $margin;
    }


    public function recoverLoanProposal(): Loan
    {
        return $this->loanProposal;
    }
}