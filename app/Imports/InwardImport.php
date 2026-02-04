<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use App\Models\Quality;
use App\Models\Inward;
use App\Models\InwardItem;
use Carbon\Carbon;

class InwardImport implements ToCollection, WithHeadingRow
{
    public array $rows = [];
    public array $errors = [];

    public function collection(Collection $rows)
    {
        $excelRow = 1;

        foreach ($rows as $row) {
            $excelRow++;

            $data = $row->toArray();
            $rowErrors = [];

            $original = [
                'quality'       => (string) ($data['quality'] ?? ''),
                'handling_unit' => (string) ($data['handling_unit'] ?? ''),
                'challan_no'    => (string) ($data['challan_no'] ?? ''),
                'e_way_bill_no' => (string) ($data['e_way_bill_no'] ?? ''),
            ];

            $data['handling_unit'] = $original['handling_unit'];

            if ($data['handling_unit'] !== '' && stripos($data['handling_unit'], 'e') !== false) {
                $rowErrors[] = "Handling Unit cannot be scientific notation ({$original['handling_unit']})";
            }

            if ($data['handling_unit'] !== '' &&
                InwardItem::where('handling_unit', $data['handling_unit'])->exists()) {
                $rowErrors[] = "Handling Unit already exists in system ({$original['handling_unit']})";
            }

            if (!empty($data['challan_no']) && !empty($data['e_way_bill_no']) &&
                Inward::where('challan_no', $data['challan_no'])
                    ->where('e_way_bill_no', $data['e_way_bill_no'])
                    ->exists()) {
                $rowErrors[] = "Challan number already exists in system ({$original['challan_no']})";
            }

            $validator = Validator::make($data, [
                'challan_from'  => 'required|string',
                'challan_date'  => 'required|date_format:d.m.Y',
                'challan_no'    => 'required',
                'e_way_bill_no' => 'required',
                'vehicle_no'    => 'required|string',
                'transport'     => 'required|string',
                'quality'       => 'required|string',
                'gsm'           => 'required|numeric',
                'width'         => 'required',
                'allocation'    => 'nullable',
                'weight'        => 'required',
                'core_dia'      => 'required',
                'reel_dia'      => 'required',
                'batch'         => 'required',
                'handling_unit' => 'required',
                'job_card_id'       => 'nullable|integer',
                'job_card_item_id'  => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                $rowErrors = array_merge($rowErrors, $validator->errors()->all());
            }

            $displayDate = null;
            $dbDate = null;

            if (!empty($data['challan_date'])) {
                try {
                    $date = Carbon::createFromFormat('d.m.Y', $data['challan_date']);
                    $today = Carbon::today();

                    if ($date->lt($today->copy()->subDays(15)) ||
                        $date->gt($today->copy()->addDays(15))) {
                        $rowErrors[] = "Challan date must be within 15 days past or future ({$data['challan_date']})";
                    }

                    $displayDate = $date->format('d F Y');
                    $dbDate = $date->format('Y-m-d');

                } catch (\Exception $e) {
                    $rowErrors[] = "Invalid challan date format ({$data['challan_date']})";
                }
            }

            $qualityExcel = trim($original['quality']);

            $quality = Quality::whereRaw('LOWER(name) = ?', [strtolower($qualityExcel)])
                ->orWhereRaw('LOWER(code) = ?', [strtolower($qualityExcel)])
                ->first();

            if (!$quality) {
                $rowErrors[] = "Quality not found: '{$qualityExcel}'";
            }

            $isValid = empty($rowErrors);

            $rowData = [
                '_excel_row'    => $excelRow,
                '_is_valid'     => $isValid,
                '_errors'       => $rowErrors,
                '_original'     => $original,
                '_display_date' => $displayDate,
            ];

            if ($isValid) {
                $clean = $validator->validated();
                $clean['challan_date'] = $dbDate;
                $clean['quality_id'] = $quality->id;
                $clean['quality'] = $quality->name . ' (' . $quality->code . ')';
                $rowData = array_merge($rowData, $clean);
            } else {
                $data['_display_date'] = $displayDate ?? $data['challan_date'];
                $rowData = array_merge($rowData, $data);
            }

            $this->rows[] = $rowData;

            if (!$isValid) {
                $this->errors[] = [
                    'row' => $excelRow,
                    'errors' => $rowErrors
                ];
            }
        }
    }
}