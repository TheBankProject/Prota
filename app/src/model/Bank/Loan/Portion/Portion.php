<?php
namespace Minuz\Prota\model\Bank\Loan\Portion;

class Portion
{
    public const PROPOSAL_VIEW = true;
    private bool $paid = false;

    public function __construct(
        private \DateTimeImmutable $deadline,
        public readonly float $value,
        bool $paid =false
    ) {
        $this->paid = $paid;
    }


    public function view(bool $proposalView): array
    {
        $isPaidCheck = $this->paid == true ? 'Yes' : 'No';
        $view = [
            'Deadline date' => $this->deadline->format('d/m/Y'),
            'Value'         => round($this->value, 2)
        ];

        if ( $proposalView ) {
            return $view;
        }

        $metadata = ['Is paid' => $isPaidCheck];
        return array_merge($view, $metadata);
    }


    public function __serialize(): array
    {
        return [
            'Deadline'  => $this->deadline->format('d/m/Y'),
            'Value'     => $this->value,
        ];
    }


    public function __unserialize(array $data): void
    {
        $this->deadline   = \DateTimeImmutable::createFromFormat('d/m/Y', $data['Deadline']);
        $this->value      = $data['Value'];
    }
}