<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Core\Application\Response\BasicResponse;
use App\Core\Application\Response\GenericListResponse;
use App\Core\Application\Response\GenericListSearchPageResponse;
use App\Core\Application\Response\GenericListSearchResponse;
use App\Core\Application\Response\GenericObjectResponse;
use App\Presentation\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiBaseController extends Controller
{
    protected function getAllJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "messages" => $response->getMessageResponseAll()
        ], $response->getCodeStatus());
    }

    protected function getAllLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "message" => $response->getMessageResponseAllLatest()
        ], $response->getCodeStatus());
    }

    protected function getSuccessJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "messages" => $response->getMessageResponseSuccess()
        ], $response->getCodeStatus());
    }

    protected function getSuccessLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "message" => $response->getMessageResponseSuccessLatest()
        ], $response->getCodeStatus());
    }

    protected function getErrorJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "messages" => $response->getMessageResponseError()
        ], $response->getCodeStatus());
    }

    protected function getErrorLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "message" => $response->getMessageResponseErrorLatest()
        ], $response->getCodeStatus());
    }

    protected function getInfoJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "messages" => $response->getMessageResponseInfo()
        ], $response->getCodeStatus());
    }

    protected function getInfoLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "message" => $response->getMessageResponseInfoLatest()
        ], $response->getCodeStatus());
    }

    protected function getWarningJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "messages" => $response->getMessageResponseWarning()
        ], $response->getCodeStatus());
    }

    protected function getWarningLatestJsonResponse(BasicResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "message" => $response->getMessageResponseWarningLatest()
        ], $response->getCodeStatus());
    }

    /**
     * @OA\Schema(
     *  schema="ObjectJSONResponse",
     *  title="Object JSON Response",
     * 	@OA\Property(
     * 		property="type",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="code_status",
     * 		type="int"
     * 	),
     * 	@OA\Property(
     * 		property="data",
     * 		type="object"
     * 	)
     * )
     */
    protected function getObjectJsonResponse(GenericObjectResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "data" => $response->getDto()
        ], $response->getCodeStatus());
    }

    /**
     * @OA\Schema(
     *  schema="ListJSONResponse",
     *  title="List JSON Response",
     * 	@OA\Property(
     * 		property="type",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="code_status",
     * 		type="int"
     * 	),
     * 	@OA\Property(
     * 		property="datas",
     * 		type="object"
     * 	)
     * )
     */
    protected function getListJsonResponse(GenericListResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "datas" => $response->getDtoList()
        ], $response->getCodeStatus());
    }

    /**
     * @OA\Schema(
     *  schema="ListSearchJSONResponse",
     *  title="List Search JSON Response",
     * 	@OA\Property(
     * 		property="type",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="code_status",
     * 		type="int"
     * 	),
     * 	@OA\Property(
     * 		property="total_count",
     * 		type="int"
     * 	),
     * 	@OA\Property(
     * 		property="datas",
     * 		type="object"
     * 	)
     * )
     */
    protected function getListSearchJsonResponse(GenericListSearchResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "total_count" => $response->totalCount,
            "datas" => $response->getDtoListSearch()
        ], $response->getCodeStatus());
    }

    /**
     * @OA\Schema(
     *  schema="ListSearchPageJSONResponse",
     *  title="List Search Page JSON Response",
     * 	@OA\Property(
     * 		property="type",
     * 		type="string"
     * 	),
     * 	@OA\Property(
     * 		property="code_status",
     * 		type="int"
     * 	),
     * 	@OA\Property(
     * 		property="total_count",
     * 		type="int"
     * 	),
     * 	@OA\Property(
     * 		property="meta",
     * 		type="object"
     * 	),
     * 	@OA\Property(
     * 		property="datas",
     * 		type="object"
     * 	)
     * )
     */
    protected function getListSearchPageJsonResponse(GenericListSearchPageResponse $response): JsonResponse {
        return response()->json([
            "type" => $response->getType(),
            "code_status" => $response->getCodeStatus(),
            "total_count" => $response->totalCount,
            "meta" => $response->meta,
            "datas" => $response->getDtoListSearchPage()
        ], $response->getCodeStatus());
    }
}
