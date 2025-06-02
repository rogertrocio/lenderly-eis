<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class UsersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    /**
     * Constructor of the class.
     *
     * @param array|null $filters
     */
    public function __construct(?array $filters = null)
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = User::query()->select('id', 'first_name', 'last_name', 'email', 'phone', 'job')
            ->commonFilters($this->filters)
            ->orderBy('last_name');

        return $query->get();
    }

    /**
     * Return array headers for excel.
     *
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Last Name',
            'First Name',
            'Email Address',
            'Phone Number',
            'Job Title',
        ];
    }

    /**
     * Map data return for exporting.
     *
     * @param [type] $data
     * @return array
     */
    public function map($data): array
    {
        return [
            $data['id'],
            $data['last_name'],
            $data['first_name'],
            $data['email'],
            $data['phone'],
            $data['email'],
        ];
    }
}
