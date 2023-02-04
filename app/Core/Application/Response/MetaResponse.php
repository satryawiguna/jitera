<?php

namespace App\Core\Application\Response;

/**
 * @OA\Schema(
 *      schema="MetaResponse",
 *      type="object"
 * )
 */
class MetaResponse
{
    /**
     * @OA\Property(
     *      property="type",
     *      title="type",
     *      example="SUCCESS",
     *      type="string"
     * )
     */
    public string $type;

    /**
     * @OA\Property(
     *      property="code_status",
     *      title="code_status",
     *      example="200",
     *      type="integer"
     * )
     */
    public int $code_status;

    /**
     * @param string $type
     * @param int $code_status
     */
    public function __construct(string $type, int $code_status)
    {
        $this->type = $type;
        $this->code_status = $code_status;
    }
}
