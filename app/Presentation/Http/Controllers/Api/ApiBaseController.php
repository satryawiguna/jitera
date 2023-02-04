<?php

namespace App\Presentation\Http\Controllers\Api;

use App\Core\Application\Response\BasicResponse;
use App\Core\Application\Response\GenericListResponse;
use App\Core\Application\Response\GenericListSearchPageResponse;
use App\Core\Application\Response\GenericListSearchResponse;
use App\Core\Application\Response\GenericObjectResponse;
use App\Core\Application\Response\MetaListResponse;
use App\Core\Application\Response\MetaPagingResponse;
use App\Core\Application\Response\MetaResponse;
use App\Presentation\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use League\Fractal\Serializer\DataArraySerializer;
use League\Fractal\Serializer\SerializerAbstract;
use League\Fractal\TransformerAbstract;

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


    /**
     * @OA\Schema(
     *  schema="SuccessJsonResponse",
     *  type="object",
     * 	@OA\Property(
     * 		property="messages",
     *      title="messages",
     * 		type="array",
     *      example={"Message success"},
     *      @OA\Items(type="string")
     * 	)
     * )
     */
    protected function getSuccessJsonResponse(BasicResponse $response): JsonResponse {
        $meta = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        return response()->json([
            "meta" => $meta,
            "messages" => $response->getMessageResponseSuccess()
        ], $response->getCodeStatus());
    }

    /**
     * @OA\Schema(
     *  schema="SuccessLatestJsonResponse",
     *  type="object",
     * 	@OA\Property(
     * 		property="message",
     *      title="message",
     *      example="Message success",
     * 		type="string"
     * 	)
     * )
     */
    protected function getSuccessLatestJsonResponse(BasicResponse $response): JsonResponse {
        $meta = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        return response()->json([
            "meta" => $meta,
            "message" => $response->getMessageResponseSuccessLatest()
        ], $response->getCodeStatus());
    }


    /**
     * @OA\Schema(
     *  schema="ErrorJsonResponse",
     *  type="object",
     * 	@OA\Property(
     * 		property="messages",
     *      title="messages",
     * 		type="array",
     *      example={"Message error"},
     *      @OA\Items(type="string")
     * 	)
     * )
     */
    protected function getErrorJsonResponse(BasicResponse $response): JsonResponse {
        $meta = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        return response()->json([
            "meta" => $meta,
            "messages" => $response->getMessageResponseError()
        ], $response->getCodeStatus());
    }

    /**
     * @OA\Schema(
     *  schema="ErrorLatestJsonResponse",
     *  type="object",
     * 	@OA\Property(
     * 		property="message",
     *      title="message",
     *      example="Message error",
     * 		type="string"
     * 	)
     * )
     */
    protected function getErrorLatestJsonResponse(BasicResponse $response): JsonResponse {
        $meta = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        return response()->json([
            "meta" => $meta,
            "message" => $response->getMessageResponseErrorLatest()
        ], $response->getCodeStatus());
    }


    /**
     * @OA\Schema(
     *  schema="InfoJsonResponse",
     *  type="object",
     * 	@OA\Property(
     * 		property="messages",
     *      title="messages",
     * 		type="array",
     *      example={"Message info"},
     *      @OA\Items(type="string")
     * 	)
     * )
     */
    protected function getInfoJsonResponse(BasicResponse $response): JsonResponse {
        $meta = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        return response()->json([
            "meta" => $meta,
            "messages" => $response->getMessageResponseInfo()
        ], $response->getCodeStatus());
    }

    /**
     * @OA\Schema(
     *  schema="InfoLatestJsonResponse",
     *  type="object",
     * 	@OA\Property(
     * 		property="message",
     *      title="message",
     *      example="Message info",
     * 		type="string"
     * 	)
     * )
     */
    protected function getInfoLatestJsonResponse(BasicResponse $response): JsonResponse {
        $meta = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        return response()->json([
            "meta" => $meta,
            "message" => $response->getMessageResponseInfoLatest()
        ], $response->getCodeStatus());
    }


    /**
     * @OA\Schema(
     *  schema="WarningJsonResponse",
     *  type="object",
     * 	@OA\Property(
     * 		property="messages",
     *      title="messages",
     * 		type="array",
     *      example={"Message warning"},
     *      @OA\Items(type="string")
     * 	)
     * )
     */
    protected function getWarningJsonResponse(BasicResponse $response): JsonResponse {
        $meta = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        return response()->json([
            "meta" => $meta,
            "messages" => $response->getMessageResponseWarning()
        ], $response->getCodeStatus());
    }

    /**
     * @OA\Schema(
     *  schema="WarningLatestJsonResponse",
     *  type="object",
     * 	@OA\Property(
     * 		property="message",
     *      title="message",
     *      example="Message warning",
     * 		type="string"
     * 	)
     * )
     */
    protected function getWarningLatestJsonResponse(BasicResponse $response): JsonResponse {
        $meta = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        return response()->json([
            "meta" => $meta,
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
     * 	)
     * )
     */
    protected function getObjectJsonResponse(GenericObjectResponse $response,
                                             ?TransformerAbstract $transformer = null,
                                             SerializerAbstract $serializer = new DataArraySerializer(),
                                             ?array $meta = null,
                                             ?array $include = null,
                                             ?array $exclude = null): JsonResponse {

        $metaInit = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        if ($meta) {
            $meta = array_merge($metaInit, $meta);
        } else {
            $meta = $metaInit;
        }

        if ($transformer) {
            $fractal = fractal($response->getDto(), $transformer, $serializer);

            $fractal = $fractal->addMeta($meta);

            if ($include) {
                $fractal = $fractal->parseIncludes($include);
            }

            if ($exclude) {
                $fractal = $fractal->parseExcludes($exclude);
            }

            return $fractal->respond($response->getCodeStatus());
        }

        return response()->json([
            "meta" => $meta,
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
     * 	)
     * )
     */
    protected function getListJsonResponse(GenericListResponse $response,
                                           ?TransformerAbstract $transformer = null,
                                           SerializerAbstract $serializer = new DataArraySerializer(),
                                           ?array $meta = null,
                                           ?array $include = null,
                                           ?array $exclude = null): JsonResponse {
        $metaInit = (array) new MetaResponse($response->getType(),
            $response->getCodeStatus());

        if ($meta) {
            $meta = array_merge($metaInit, $meta);
        } else {
            $meta = $metaInit;
        }

        if ($transformer) {
            $fractal = fractal($response->getDtoList(), $transformer, $serializer);

            $fractal = $fractal->addMeta($meta);

            if ($include) {
                $fractal = $fractal->parseIncludes($include);
            }

            if ($exclude) {
                $fractal = $fractal->parseExcludes($exclude);
            }

            return $fractal->respond($response->getCodeStatus());
        }

        return response()->json([
            "meta" => $meta,
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
     * 	)
     * )
     */
    protected function getListSearchJsonResponse(GenericListSearchResponse $response,
                                                 ?TransformerAbstract $transformer = null,
                                                 SerializerAbstract $serializer = new DataArraySerializer(),
                                                 ?array $meta = null,
                                                 ?array $include = null,
                                                 ?array $exclude = null): JsonResponse {
        $metaInit = (array) new MetaListResponse($response->getType(),
            $response->getCodeStatus(),
            $response->getTotalCount());

        if ($meta) {
            $meta = array_merge($metaInit, $meta);
        } else {
            $meta = $metaInit;
        }

        if ($transformer) {
            $fractal = fractal($response->getDtoListSearch(), $transformer, $serializer);

            $fractal = $fractal->addMeta($meta);

            if ($include) {
                $fractal = $fractal->parseIncludes($include);
            }

            if ($exclude) {
                $fractal = $fractal->parseExcludes($exclude);
            }

            return $fractal->respond($response->getCodeStatus());
        }

        return response()->json([
            "meta" => $meta,
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
     * 	)
     * )
     */
    protected function getListSearchPageJsonResponse(GenericListSearchPageResponse $response,
                                                     ?TransformerAbstract $transformer = null,
                                                     SerializerAbstract $serializer = new DataArraySerializer(),
                                                     ?array $meta = null,
                                                     ?array $include = null,
                                                     ?array $exclude = null): JsonResponse {
        $metaInit = (array) new MetaPagingResponse($response->getType(),
            $response->getCodeStatus(),
            $response->getTotalCount(),
            $response->getMeta());

        if ($meta) {
            $meta = array_merge($metaInit, $meta);
        } else {
            $meta = $metaInit;
        }

        if ($transformer) {
            $fractal = fractal($response->getDtoListSearchPage(), $transformer, $serializer);

            $fractal = $fractal->addMeta($meta);

            if ($include) {
                $fractal = $fractal->parseIncludes($include);
            }

            if ($exclude) {
                $fractal = $fractal->parseExcludes($exclude);
            }

            return $fractal->respond($response->getCodeStatus());
        }

        return response()->json([
            "meta" => $meta,
            "datas" => $response->getDtoListSearchPage()
        ], $response->getCodeStatus());
    }
}
