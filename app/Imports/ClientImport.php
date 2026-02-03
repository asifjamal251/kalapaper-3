<?php

namespace App\Imports;

use App\Models\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ClientImport implements ToCollection, WithHeadingRow
{
    public array $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {

            $rowNumber = $index + 2;
            $data = $row->toArray();


            $validator = Validator::make($data, [
                'company_name' => 'required|string|max:255',

                'email' => [
                    'nullable',
                    function ($attr, $value, $fail) {
                        foreach (explode(',', $value) as $email) {
                            if (!filter_var(trim($email), FILTER_VALIDATE_EMAIL)) {
                                $fail("Invalid email: {$email}");
                            }
                        }
                    }
                ],

                'contact_no' => [
                    'nullable',
                    function ($attr, $value, $fail) {
                        foreach (explode(',', $value) as $no) {
                            if (!preg_match('/^[0-9]{10}$/', trim($no))) {
                                $fail("Invalid contact number: {$no}");
                            }
                        }
                    }
                ],

                'gst' => [
                    'required',
                    function ($attr, $value, $fail) {
                        if (Client::where('gst', $value)->exists()) {
                            $fail('GST already exists');
                        }
                    }
                ],

                'pincode' => 'nullable|digits:6',
                'state' => 'nullable|max:255',
                'district' => 'nullable|string|max:255',
                'city' => 'nullable|max:255',
                'address' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'errors' => $validator->errors()->toArray(),
                    'data' => $data,
                ];
                continue;
            }

            $email = $data['email'] ?? null;
            $contact = $data['contact_no'] ?? null;

            $emailArray = null;
            $contactArray = null;

            if (!empty($email)) {
                $decoded = json_decode($email, true);
                $emailArray = is_array($decoded)
                    ? array_map('trim', $decoded)
                    : array_map('trim', explode(',', $email));
            }

            if (!empty($contact)) {
                $decoded = json_decode($contact, true);
                $contactArray = is_array($decoded)
                    ? array_map('trim', $decoded)
                    : array_map('trim', explode(',', $contact));
            }

            Client::create([
                'company_name' => $data['company_name'] ?? null,
                'email'        => $emailArray,
                'contact_no'   => $contactArray,
                'gst' => $data['gst'],
                'pincode' => $data['pincode'] ?? null,
                'state' => $data['state'] ?? null,
                'district' => $data['district'] ?? null,
                'city' => $data['city'] ?? null,
                'address' => $data['address'] ?? null,
                'status_id' => $data['status'] ?? 14,
                'stock_on_email' => $data['stock_on_email'] ?? 15,
                'login_status' => $data['login_status'] ?? 15,
            ]);
        }
    }
}