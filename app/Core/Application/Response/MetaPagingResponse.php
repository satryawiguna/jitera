<?php

namespace App\Core\Application\Response;

/**
 * @OA\Schema(
 *      schema="MetaPageResponse",
 *      type="object"
 * )
 */
class MetaPagingResponse
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
     * @OA\Property(
     *      property="per_page",
     *      title="per_page",
     *      example="5",
     *      type="integer"
     * )
     */
    public int $per_page;

    /**
     * @OA\Property(
     *      property="current_page",
     *      title="current_page",
     *      example="1",
     *      type="integer"
     * )
     */
    public int $current_page;

    /**
     * @param string $type
     * @param int $code_status
     * @param int $total_count
     * @param array $meta
     */
    public function __construct(string $type, int $code_status, int $total_count, array $meta)
    {
        $this->type = $type;
        $this->code_status = $code_status;
        $this->total_count = $total_count;
        $this->per_page = $meta['perPage'];
        $this->current_page = $meta['currentPage'];
    }
}
