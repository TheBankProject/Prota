<?php
namespace Minuz\Prota\model\Bank\Interest;


interface InterestCalcBasics
{
    const INTEREST_TYPE = '';

    public function calculateInterest(array $portions): array;

    public function view(): array;

}