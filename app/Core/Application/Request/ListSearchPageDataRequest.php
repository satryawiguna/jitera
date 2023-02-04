<?php

namespace App\Core\Application\Request;


class ListSearchPageDataRequest extends ListSearchDataRequest
{
    public int $page = 1;

    public int $per_page = 10;

    public function rules()
    {
        return array_merge([
            'page' => ['integer', 'min:1'],
            'per_page' => ['integer', 'min:1']
        ], parent::rules());
    }

    public function messages()
    {
        return array_merge([
            'page.integer' => 'Page is integer expected',
            'page.min' => 'Page is not allow less than 1',
            'per_page.integer' => 'Per page is integer expected',
            'per_page.min' => 'Per page is not allow less than 1'
        ], parent::messages());
    }
}
