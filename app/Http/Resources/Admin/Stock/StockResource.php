<?php

namespace App\Http\Resources\Admin\Stock;

use Illuminate\Http\Resources\Json\JsonResource;

class StockResource extends JsonResource{

    protected function addToJobCard($id){
        if (session('job_card_item')) {
            foreach (session('job_card_item') as $item) {
                if ($item['job_card_item_id'] === $id) {
                    return 1;
                }
            }
        }
        return 0;
    }

    public function toArray($request)
    {
        $buttons = [];

        $admin = auth('admin')->user();

        if ($admin?->hasAccess('read_stock')) {
            $buttons[] = [
                'url'   => route('admin.client.show', $this->id),
                'label' => 'View',
                'icon'  => 'ri-eye-line align-bottom me-2 text-muted',
                'class' => 'dropdown-item edit-item-btn'
            ];
        }

        if (auth('admin')->user()?->hasAccess('add_stock')) {
            if($this->status_id == 25){

                $buttons[] = [
                    'label'   => 'Cancel Split',
                    'icon'    => 'ri-close-circle-fill align-bottom me-2 text-muted',
                    'class'   => 'dropdown-item',
                    'button'  => true,
                    'onclick' => "updateDataConfirm(
                        '".route('admin.stock.split.cancel', $this->id)."',
                        { status: 15 },
                        null,
                        'Are you sure?',
                        'Do you want to cancel this split reel'
                    )",
                ];

            } else{
                $buttons[] = [
                    'label' => 'Split',
                    'icon' => 'ri-pencil-fill',
                    'class' => 'create dropdown-item',
                    'data' => [
                        'data-url' => route('admin.stock.edit', $this->id),
                        'model-size' => 'modal-lg'
                    ],
                ];
            }
        }

        $checkboxHtml = '';
        if (in_array($this->status_id, [3])) {
            //$checkboxHtml = $this->jobCard?->job_card_number;
        } else {
            $checkedAttr = $this->addToJobCard($this->id) ? 'checked' : '';
            $checkboxHtml = '
                <div class="form-check form-check-success mb-0">
                    <input class="form-check-input addToJobCard" type="checkbox" value="' . $this->id . '" id="checkbox_' . $this->id . '" ' . $checkedAttr . '>
                    <label class="form-check-label" for="checkbox_' . $this->id . '"></label>
                </div>';
        }

        return [
            'sn'              => '<span class="d-flex gap-2 justify-content-between">' . ++$request->start . $checkboxHtml . '</span>',
            'id'              => $this->id,
            'quality'         => $this->quality->name_with_code,
            'gsm'             => $this->gsm,
            'width'           => $this->width,
            'weight'          => $this->weight,
            'batch'           => $this->batch,
            'handling_unit'   => $this->handling_unit,
            'vehicle_no'      => $this->inward?->vehicle_no,
            'challan_no'      => $this->inward?->challan_no,
            'challan_date'    => $this->inward?->challan_date?->format('d F Y'),
            'job_card_no'     => $this->job_card_no??'--',
            'booked_date'     => $this->booked_date??'--',
            'aging'           => age($this->stock_date),
            'status_id'       => $this->status_id,
            'status'          => status($this->status_id),
            'action'          => actionDropdown($buttons),
            'item_added'      => $this->addToJobCard($this->id),
        ];
    }
}