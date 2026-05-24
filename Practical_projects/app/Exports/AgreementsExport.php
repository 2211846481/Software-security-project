<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AgreementsExport implements FromCollection, WithHeadings
{
    protected $agreements;

    public function __construct(Collection $agreements)
    {
        $this->agreements = $agreements;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Title',
            'Department',
            'Effective Date',
            'Expiry Date',
            'Status',
        ];
    }

    public function collection()
    {
        return $this->agreements->map(function($agreement) {
            return [
                'id' => $agreement->id,
                'title' => $agreement->title,
                'department' => $agreement->department->name ?? 'N/A',
                'effective_date' => $agreement->effective_date,
                'expiry_date' => $agreement->expiry_date,
                'status' => $this->getStatusText($agreement->status),
            ];
        });
    }
    
    private function getStatusText($status)
    {
        switch ($status) {
            case 1: return 'Active';
            case 0: return 'Draft';
            case 2: return 'Expired';
            default: return 'Data Error';
        }
    }
}