<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreVehicleRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Rute su već zaštićene 'auth' + 'admin' middleware-om.
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', 'in:van,car'],
            'name' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_recommended' => ['boolean'],
            'is_featured' => ['boolean'],
            'is_active' => ['boolean'],
            'features' => ['array'],
            'features.*' => ['integer', 'exists:features,id'],
            // Opcione vrednosti po specifikaciji (npr. "8+1"): { feature_id: value }
            'feature_values' => ['array'],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Checkbox polja: pretvori u boolean (nedostajuća = false).
        $this->merge([
            'is_recommended' => $this->boolean('is_recommended'),
            'is_featured' => $this->boolean('is_featured'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }

    public function attributes(): array
    {
        return [
            'name' => 'naziv',
            'type' => 'tip',
        ];
    }
}
