<?php

namespace App\Http\Requests\Event;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $id = decrypt_data($this->id);
        return [
            'title' => 'required|string|max:255|unique:events,title,' . $id,
            'description' => 'required|string',
            'event_date' => 'required|date|after:today',
            'location' => 'required|string',
            'ticket_types' => 'required|array',
            'ticket_types.*.name' => 'required|string|max:255',
            'ticket_types.*.price' => 'required|numeric|min:0',
            'ticket_types.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'title.unique' => 'An event with this title already exists.',
            'title.required' => 'Event title is required.',
            'description.required' => 'Event description is required.',
            'event_date.required' => 'Event date is required.',
            'event_date.after_or_equal' => 'The event date must be today or a future date.',
            'location.required' => 'Event location is required.',
            'ticket_types.required' => 'At least one ticket type is required.',
            'ticket_types.*.name.required' => 'Ticket name is required.',
            'ticket_types.*.name.max' => 'Ticket name cannot exceed 255 characters.',
            'ticket_types.*.price.required' => 'Ticket price is required.',
            'ticket_types.*.price.numeric' => 'Ticket price must be a number.',
            'ticket_types.*.price.min' => 'Ticket price cannot be negative.',
            'ticket_types.*.quantity.required' => 'Ticket quantity is required.',
            'ticket_types.*.quantity.integer' => 'Ticket quantity must be an integer.',
            'ticket_types.*.quantity.min' => 'Ticket quantity must be at least 1.',
        ];
    }


}
