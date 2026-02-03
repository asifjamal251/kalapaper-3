<?php

namespace App\Http\Resources\Admin\Party;

use Illuminate\Http\Resources\Json\JsonResource;
class PartyResource extends JsonResource
{

    public function toArray($request)
    {
        $buttons = [];

        if (auth('admin')->user()?->hasAccess('edit_client')) {
            $buttons[] = [
                'label' => 'Edit',
                'icon' => 'ri-pencil-fill',
                'class' => 'editData dropdown-item edit-item-btn',
                'data' => [
                    'data-url' => route('admin.parties.edit', $this->id),
                    'model-size' => 'modal-xl'
                ],
            ];
        }
        
        if (auth('admin')->user()?->hasAccess('read_client')){
            $buttons[] = [
                'url' => route('admin.parties.show', $this->id),
                'label' => 'View',
                'icon' => 'ri-eye-line align-bottom me-2 text-muted',
                'class' => 'dropdown-item edit-item-btn'
            ];
        }

        if (auth('admin')->user()?->hasAccess('delete_client')) {
            if($this->status_id == 14){
                $buttons[] = [
                    'label' => 'Inactive',
                    'icon' => 'ri-close-circle-fill align-bottom me-2 text-muted',
                    'class' => 'dropdown-item',
                    'button' => true,
                    'onclick' => "updateDataConfirm('".route('admin.parties.change.status', $this->id)."', { status: 15 }, null, 'Are you sure?', 'Do you want to In-active this Party')",
                ];
            }

            if($this->status_id == 15){
                $buttons[] = [
                    'label' => 'Active',
                    'icon' => 'ri-checkbox-circle-fill align-bottom me-2 text-muted',
                    'class' => 'dropdown-item',
                    'button' => true,
                    'onclick' => "updateDataConfirm('".route('admin.parties.change.status', $this->id)."', { status: 14 }, null, 'Are you sure?', 'Do you want to active this Party')",
                ];
            }
        }

        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'type' => $this->type,
            'company_name' => $this->company_name,
            'email' => collect($this->email)->filter()->implode(', ') ?: 'N/A',
            'contact_no' => collect($this->contact_no)->filter()->implode(', ') ?: 'N/A',
            'gst' => $this->gst ?? 'N/A',
            'city' => $this->city,
            'status' => status($this->status_id),
            'action' => actionDropdown($buttons),
        ];
    }
}
