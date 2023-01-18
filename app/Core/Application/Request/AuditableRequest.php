<?php

namespace App\Core\Application\Request;

use Illuminate\Foundation\Http\FormRequest;

abstract class AuditableRequest extends FormRequest
{
    public ?string $request_by;

    /**
     * @return string|null
     */
    public function getRequestBy(): ?string
    {
        return $this->request_by;
    }

    /**
     * @param string|null $request_by
     */
    public function setRequestBy(?string $request_by): void
    {
        $this->request_by = $request_by;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
