<?php

namespace App\Core\Application\Response;

/**
 * @OA\Schema(
 *      schema="MetaListResponse",
 *      type="object"
 * )
 */
class MetaListResponse
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
     * @OA\Property(
     *      property="total_count",
     *      title="total_count",
     *      example="10",
     *      type="integer"
     * )
     */
    public int $total_count;

    /**
     * @param string $type
     * @param int $code_status
     * @param int $total_count
     */
    public function __construct(string $type, int $code_status, int $total_count)
    {
        $this->type = $type;
        $this->code_status = $code_status;
        $this->total_count = $total_count;
    }
}
