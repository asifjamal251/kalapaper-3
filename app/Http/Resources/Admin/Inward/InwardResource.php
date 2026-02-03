<?php

namespace App\Http\Resources\Admin\Inward;

use Illuminate\Http\Resources\Json\JsonResource;

class InwardResource extends JsonResource
{
    public function toArray($request)
    {
        $buttons = [];

        $admin = auth('admin')->user();

        if ($admin?->hasAccess('read_inward')) {
            $buttons[] = [
                'url'   => route('admin.inward.show', $this->id),
                'label' => 'View',
                'icon'  => 'ri-eye-line align-bottom me-2 text-muted',
                'class' => 'dropdown-item edit-item-btn'
            ];
        }

        if ($admin?->hasAccess('delete_inward')) {
                $buttons[] = [
                    'label'   => 'Cancel',
                    'icon'    => 'ri-close-circle-fill align-bottom me-2 text-muted',
                    'class'   => 'dropdown-item',
                    'button'  => true,
                    'onclick' => "updateDataConfirm(
                        '".route('admin.inward.destroy', $this->id)."',
                        { status: 15 },
                        null,
                        'Are you sure?',
                        'Do you want to cancel this inward, Never back this'
                    )",
                ];
        }

        return [
            'sn'              => ++$request->start,
            'id'              => $this->id,
            'items'           => $this->items->count(),
            'challan_from'    => $this->challan_from,
            'challan_date'    => $this->challan_date?->format('d F Y'),
            'challan_no'      => $this->challan_no,
            'e_way_bill_no'   => $this->e_way_bill_no,
            'vehicle_no'      => $this->vehicle_no,
            'transport'       => $this->transport,
            'weights'         => $this->items->sum('weight'),
            'status_id'       => $this->status_id,
            'status'          => status($this->status_id),
            'action' => actionDropdown($buttons),
        ];
    }
}