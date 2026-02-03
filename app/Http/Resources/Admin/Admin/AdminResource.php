<?php

namespace App\Http\Resources\Admin\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AdminResource extends JsonResource
{

    public function toArray($request){

        $buttons = [];

        if (auth('admin')->user()?->hasAccess('edit_admin')) {
            $buttons[] = [
                'label' => 'Edit',
                'icon' => 'ri-pencil-fill',
                'class' => 'editData dropdown-item edit-item-btn',
                'data' => [
                    'data-url' => route('admin.admin.edit', $this->id),
                    'model-size' => 'modal-lg'
                ],
            ];
        }
        
        if (auth('admin')->user()?->hasAccess('read_admin')){
            $buttons[] = [
                'url' => route('admin.admin.show', $this->id),
                'label' => 'View',
                'icon' => 'ri-eye-line align-bottom me-2 text-muted',
                'class' => 'dropdown-item edit-item-btn'
            ];
        }

        if (auth('admin')->user()?->hasAccess('delete_admin')) {
            $buttons[] = [
                'label' => 'Delete',
                'icon' => 'ri-delete-bin-fill align-bottom me-2 text-muted',
                'class' => 'dropdown-item remove-item-btn',
                'button' => true,
                'onclick' => "deleteModel('".route('admin.admin.destroy', $this->id)."')",
            ];
        }

        return [
            'sn' => ++$request->start,
            'id' => $this->id,
            'role' => $this->role->role_name,
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'status' => status($this->status_id),
            'action' => actionDropdown($buttons),
        ];
    }
}
