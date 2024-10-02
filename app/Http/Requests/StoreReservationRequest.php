<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use App\Models\Reservation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Verificar si el usuario está autenticado y tiene el rol de cliente
        return Auth::check() && Auth::user()->role && Auth::user()->role->name === 'client';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'space_id' => 'required|exists:spaces,id',
            'event_name' => 'required|string|max:255',
            'start' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
            'end' => 'required|date_format:Y-m-d H:i:s|after:start',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'start' => $this->start ? Carbon::parse($this->start)->format('Y-m-d H:i:s') : null,
            'end' => $this->end ? Carbon::parse($this->end)->format('Y-m-d H:i:s') : null,
        ]);
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if ($this->hasOverlappingReservation()) {
                $validator->errors()->add('overlap', 'Ya existe una reserva para este espacio en el horario seleccionado.');
            }
        });
    }

    protected function hasOverlappingReservation()
    {
        $spaceId = $this->space_id;
        $startTime = $this->start;
        $endTime = $this->end;

        // Verificar que tengamos valores válidos antes de hacer la consulta
        if (!$spaceId || !$startTime || !$endTime) {
            return false;
        }

        return Reservation::where('space_id', $spaceId)
            ->where(function ($query) use ($startTime, $endTime) {
                $query->where(function ($q) use ($startTime, $endTime) {
                    $q->where('start', '<=', $endTime)
                      ->where('end', '>=', $startTime);
                });
            })
            ->exists();
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'errors' => $validator->errors(),
        ], 422));
    }

    protected function failedAuthorization()
    {
        throw new HttpResponseException(response()->json([
            'error' => 'No estás autorizado para realizar esta acción.',
        ], 403));
    }
}
