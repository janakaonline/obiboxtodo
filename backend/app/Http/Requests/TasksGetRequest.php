<?php

namespace App\Http\Requests;

use App\Enums\SortOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class TasksGetRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'sort_priority' => ['nullable', new Enum(SortOrder::class)],
            'sort_due_date' => ['nullable', new Enum(SortOrder::class)],
            'filter_priority' => ['nullable', new Enum(TaskPriority::class)],
            'filter_completed' => ['nullable', 'in:true,false'],
        ];
    }
}
