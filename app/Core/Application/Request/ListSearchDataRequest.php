<?php

namespace App\Core\Application\Request;


class ListSearchDataRequest extends ListDataRequest
{
    public string $search = "";

    public function rules()
    {
        return array_merge([
            'search' => ['string']
        ], parent::rules());
    }

    public function messages()
    {
        return array_merge([
            'search.string' => 'Search is string expected'
        ], parent::messages());
    }
}
