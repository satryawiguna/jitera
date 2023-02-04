<?php

namespace App\Core\Application\Request;

use App\Core\Domain\BrokenRule;

class ListDataRequest
{
    use BrokenRule;

    public string $order_by = "created_at";

    public string $sort = "ASC";

    public array $args = [];

    public function rules()
    {
        return [
            'order_by' => ['string'],
            'sort' => ['string', 'regex:(ASC|DESC)'],
            'args' => ['array'],
            'args.*' => ['string']
        ];
    }

    public function messages()
    {
        return [
            'order_by.string' => 'Order by is string expected',
            'sort.string' => 'Sort is string expected',
            'sort.regex' => 'Sort should be match string ASC or DESC',
            'args.array' => 'Arguments is array expected',
            'args.*.string' => 'Argument is string expected'
        ];
    }
}
