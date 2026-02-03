<?php

namespace App\Http\Resources\Admin\PurchaseOrder;

use Illuminate\Http\Resources\Json\JsonResource;
class PurchaseOrderResource extends JsonResource
{

    public function toArray($request)
    {
        $buttons = [];

        if (auth('admin')->user()?->hasAccess('edit_purchase_order')) {
            $buttons[] = [
                'label' => 'Edit',
                'icon' => 'ri-pencil-fill',
                'class' => 'editData dropdown-item edit-item-btn',
                'data' => [
                    'data-url' => route('admin.purchase-order.edit', $this->id),
                    'model-size' => 'modal-lg'
                ],
            ];
        }
        
        if (auth('admin')->user()?->hasAccess('read_purchase_order')){
            $buttons[] = [
                'url' => route('admin.purchase-order.show', $this->id),
                'label' => 'View',
                'icon' => 'ri-eye-line align-bottom me-2 text-muted',
                'class' => 'dropdown-item edit-item-btn'
            ];
        }

        if (auth('admin')->user()?->hasAccess('read_purchase_order')){
            $buttons[] = [
                'url' => route('admin.purchase-order.download.excel', $this->id),
                'label' => 'Download Excel',
                'icon' => 'ri-eye-line align-bottom me-2 text-muted',
                'class' => 'dropdown-item edit-item-btn'
            ];
        }



        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'po_no' => $this->po_number,
            'from' => $this->fromParty?->company_name,
            'bill_to' => $this->billTo?->company_name,
            'ship_to' => $this->shipTo?->company_name,
            'consignee' => $this->consigneeParty?->company_name,
            'status' => status($this->status_id),
            'action' => actionDropdown($buttons),
        ];
    }
}
