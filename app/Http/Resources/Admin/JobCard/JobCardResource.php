<?php

namespace App\Http\Resources\Admin\JobCard;

use Illuminate\Http\Resources\Json\JsonResource;
class JobCardResource extends JsonResource
{

    public function toArray($request)
    {
        $buttons = [];

        if (auth('admin')->user()?->hasAccess('edit_job_card')) {
            $buttons[] = [
                'label' => 'Edit',
                'icon' => 'ri-pencil-fill',
                'class' => 'dropdown-item edit-item-btn',
                'url' => route('admin.purchase-order.edit', $this->id),
            ];
        }
        
        if (auth('admin')->user()?->hasAccess('read_job_card')){
            $buttons[] = [
                'url' => route('admin.purchase-order.show', $this->id),
                'label' => 'View',
                'icon' => 'ri-eye-line align-bottom me-2 text-muted',
                'class' => 'dropdown-item edit-item-btn'
            ];
        }

        if (auth('admin')->user()?->hasAccess('read_job_card')){
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
            'run_status' => status($this->run_number_status),

            'so_details' => ($this->purchaseOrder?->so_number || $this->purchaseOrder?->so_date)
                            ? '<p class="mb-1">'.($this->purchaseOrder?->so_number ?? '--').'</p>
                               <p class="m-0">'.($this->purchaseOrder?->so_date?->format('d F Y') ?? '--').'</p>'
                            : '--',

            'reference_details' => ($this->purchaseOrder?->po_number || $this->purchaseOrder?->po_date)
                            ? '<p class="mb-1">'.($this->purchaseOrder?->po_number ?? '--').'</p>
                               <p class="m-0">'.($this->purchaseOrder?->po_date?->format('d F Y') ?? '--').'</p>'
                            : '--',
            'total_ready' => ($this->total_weight || $this->ready_weight)
                            ? '<p class="mb-1">'.($this->total_weight ?? '--').' KG</p>
                               <p class="m-0">'.($this->ready_weight ?? '--').' KG</p>'
                            : '--',

            'pending_delivered' => ($this->ready_weight || $this->delivered_weight)
                            ? '<p class="mb-1">'.(($this->delivered_weight ?? 0) ? $this->delivered_weight.' KG' : '--').'</p>
                               <p class="m-0">'.(($this->ready_weight ?? 0) ? (($this->ready_weight - ($this->delivered_weight ?? 0)).' KG') : '--').'</p>'
                            : '--',

            'job_card_no' => $this->job_card_number,
            'job_card_type' => $this->job_card_type,
            'total_reel' => $this->items()->count()??0,
            'sold_to' => $this->soldTo?->company_name,
            'from' => $this->fromParty?->company_name,
            'created_at' => $this->created_at?->format('d F Y'),
            'status' => status($this->status_id),
            'action' => actionDropdown($buttons),
        ];
    }
}
