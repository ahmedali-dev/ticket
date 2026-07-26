<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Regular users only — administrators cannot create tickets.
        return $this->user() !== null && ! $this->user()->isAdmin();
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:5000'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => __('ticket.validation.title_required'),
            'description.required' => __('ticket.validation.description_required'),
            'image.image' => __('ticket.validation.image_image'),
            'image.mimes' => __('ticket.validation.image_mimes'),
            'image.max' => __('ticket.validation.image_max'),
        ];
    }
}
