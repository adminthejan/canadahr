<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BankDetailsExport implements FromCollection, WithHeadings
{
    protected $payrolls;

    public function __construct($payrolls)
    {
        $this->payrolls = $payrolls;
    }

    public function collection()
    {
        return collect($this->payrolls);
    }

    public function headings(): array
    {
        return [
            'Company Ref',
            'Beneficiary Name',
            'Account Number',
            'Bank Code',
            'Branch Code',
            'Amount'
        ];
    }
}
