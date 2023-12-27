<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'username' => $this->username,
            'firstName' => $this->first_name,
            'lastName' => $this->last_name,
            'avatar' => $this->avatar,
            'gender' => $this->gender,
            'phone' => $this->phone,
            'email' => $this->email,
            'company' => CompanyResource::make($this->company),
            'company_id' => $this->company_id,
            'address' => $this->address,
            "state" => $this->state,
            'city' => $this->city,
            'country' => $this->country,
            'postalCode' => $this->zip,
            'date_of_birth' => $this->date_of_birth,
            "email_verified_at"=>$this->email_verified_at,
            "cart_number"=>$this->cart_number,
            "is_cart_verified"=>$this->is_cart_verified==1?true:false,
            'currency_id' => $this->currency_id,
            'lang_code' => $this->lang_code,
            'status' => $this->status==1? 'Active' : 'Inactive',
            'notification_preference' => $this->notification_preference,
            'is_active' => $this->is_active==1?true:false,
            'subscription_package_id' => $this->subscription_package_id,
            'role' => $this->role,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
