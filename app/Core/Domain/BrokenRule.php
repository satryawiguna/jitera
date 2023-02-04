<?php

namespace App\Core\Domain;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

trait BrokenRule
{
    private Collection $brokenRules;

    public function __construct()
    {
        $this->brokenRules = new Collection();
    }

    public function validate()
    {
        return Validator::make((array)$this, $this->rules(), $this->messages());
    }

    public function rules()
    {
        return [

        ];
    }

    public function messages()
    {
        return [

        ];
    }
}
