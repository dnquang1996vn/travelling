<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTrip extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
       return [
            'plans.*.from' => 'required',
            'plans.*.to' => 'required',
            'plans.*.time_start' => 'required|date|after:'.date('Y-m-d H:i:s'),
            'plans.*.time_end' => 'required|date|after:plans.*.time_start',
            'plans.*.vehicle' =>'required',
            'plans.*.activity' =>'required',
            'new_trip.name' => 'required',
            'new_trip.time_start' => 'required|date|after:'.date('Y-m-d H:i:s'),
            'new_trip.time_end' => 'required|date|after:new_trip.time_start',
            'trip_cover' => 'required|image|mimes:jpeg,jpg,png,gif',
        ];
    }
}
