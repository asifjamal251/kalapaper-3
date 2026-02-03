<?php
namespace App\Imports;

use App\Models\MaterialInward;
use App\Models\MaterialInwardItem;
use App\Models\Mill;
use App\Models\Quality;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MaterialInwardImport implements ToCollection, WithHeadingRow
{
    protected $errors = [];
    protected $successCount = 0;

    protected $status;

    public function __construct($status)
    {
        $this->status = $status;
    }


    public function collection(Collection $rows)
    {
        DB::beginTransaction();

        try {
            $rowIndex = 2; // heading is row 1
            $processedInwardIds = [];
            foreach ($rows as $row) {
                $rowData = $row->toArray();

                // format date
                if (!empty($rowData['invoice_date'])) {
                    $rowData['invoice_date'] = $this->formatDate($rowData['invoice_date']);
                }

                // validate
                $validator = Validator::make($rowData, [
                    'mill'          => 'required|string',
                    'invoice_no'    => 'required',
                    'invoice_date'  => 'required|date',
                    'transporter'   => 'required|string',
                    'vehicle_no'    => 'required|string',
                    'lr_no'         => 'nullable|string',
                    'quality'       => 'required|string',
                    'job_card_id'   => 'nullable|integer',
                    'reel_number'   => 'nullable|string',
                    'gsm'           => 'required|integer',
                    'width'         => 'required|numeric',
                    'net_weight'    => 'required|numeric',
                    'media_id'      => 'nullable|integer',
                    'location'      => 'nullable|string',
                    'batch'         => 'nullable',
                ]);

                if ($validator->fails()) {
                    $this->errors[] = [
                        'line'  => $rowIndex,
                        'error' => implode(', ', $validator->errors()->all())
                    ];
                    $rowIndex++;
                    continue;
                }

                // check mill
                $mill = Mill::whereRaw('LOWER(name) = ?', [strtolower(trim($rowData['mill']))])->first();
                if (!$mill) {
                    $this->errors[] = [
                        'line'  => $rowIndex,
                        'error' => "Mill '{$rowData['mill']}' not found."
                    ];
                    $rowIndex++;
                    continue;
                }

                // check quality
                $quality = Quality::whereRaw('LOWER(name) = ?', [strtolower(trim($rowData['quality']))])->first();
                if (!$quality) {
                    $this->errors[] = [
                        'line'  => $rowIndex,
                        'error' => "Quality '{$rowData['quality']}' not found."
                    ];
                    $rowIndex++;
                    continue;
                }


                $inward = MaterialInward::where('mill_id', $mill->id)
                    ->where('invoice_no', $rowData['invoice_no'])
                    ->where('status_id', 3)
                    ->first();

                if ($inward) {
                    $this->errors[] = [
                        'line' => $rowIndex + 2,
                        'error' => "Invoice '{$rowData['invoice_no']}' for Mill '{$mill->name}' already exists."
                    ];
                    continue; // skip this row, do not insert
                }


                // create inward
                $inward = MaterialInward::updateOrCreate(
                    [
                        'invoice_no'  => $rowData['invoice_no'],
                        'mill_id'     => $mill->id,
                        'status_id'      => 1,
                    ],
                    [
                        'created_by' => auth('admin')->user()->id,
                        'invoice_date'   => $this->formatDate($rowData['invoice_date']),
                        'transporter'    => $rowData['transporter'] ?? null,
                        'lr_no'          => $rowData['lr_no'] ?? null,
                        'vehicle_no'  => $rowData['vehicle_no'],
                        'stock_date'     => !empty($rowData['stock_date']) ? $this->formatDateTime($rowData['stock_date']) : now(),
                        'financial_year' => $this->getFinancialYear($rowData['invoice_date']),
                    ]
                );

                // create item
                MaterialInwardItem::create([
                    'material_inward_id' => $inward->id,
                    'quality_id'         => $quality->id,
                    'reel_number'        => $rowData['reel_number'] ?? null,
                    'gsm'                => $rowData['gsm'] ?? null,
                    'width'              => $rowData['width'] ?? null,
                    'net_weight'         => $rowData['net_weight'] ?? null,
                    'balance_weight'     => $rowData['net_weight'] ?? null,
                    'status_id'          => $this->status == 3 ? 20 : $this->status,
                    'media_id'           => $rowData['media_id'] ?? null,
                    'batch'              => $rowData['batch'] ?? null,
                    'location'           => $rowData['location'] ?? null,
                    'stock_date'         => !empty($rowData['stock_date']) ? $this->formatDateTime($rowData['stock_date']) : now(),
                ]);

                $this->successCount++;
                $rowIndex++;
                $processedInwardIds[] = $inward->id;
            }

            MaterialInward::whereIn('id', $processedInwardIds)->update(['status_id' => $this->status]);

            if (!empty($this->errors)) {
                DB::rollBack();
            } else {
                DB::commit();
            }
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->errors[] = [
                'line'  => 'N/A',
                'error' => 'Unexpected error: ' . $e->getMessage()
            ];
        }
    }

    private function formatDate($value)
    {
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
        } catch (\Throwable $e) {
            return date('Y-m-d', strtotime($value));
        }
    }

    private function formatDateTime($value)
    {
        if (!$value) return null;
        try {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            return date('Y-m-d H:i:s', strtotime($value));
        }
    }

    private function getFinancialYear($date)
    {
        $date = is_numeric($date)
            ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)
            : new \DateTime($date);

        $year = $date->format('Y');
        $month = (int) $date->format('m');

        return $month < 4
            ? ($year - 1) . '-' . $year
            : $year . '-' . ($year + 1);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getSuccessCount()
    {
        return $this->successCount;
    }
}