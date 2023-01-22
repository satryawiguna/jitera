<?php

namespace App\Presentation\Http\Controllers;

use App\Core\Application\Request\AuditableRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Jitera Api Documentation",
 *     description="Jitera Api Documentation",
 *     @OA\Contact(
 *          email="satrya@freshcms.net"
 *     ),
 *     @OA\License(
 *          name="Apache 2.0",
 *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *     )
 * )
 *
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="Jitera api host server"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="apiKey",
 *     type="apiKey",
 *     name="Authorization",
 *     in="header"
 * )
 *
 * @OA\PathItem(path="/api")
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function setRequestAuthor(AuditableRequest $request)
    {
        if (Auth::user()) {
            $request->request_by = Auth::user()->username;
        } else {
            $request->request_by = 'system';
        }
    }

    protected function setRequestData($request, $data)
    {
        foreach ($request->all() as $key => $value) {
            if (property_exists($data, $key)) {
                if (is_string($value) || is_null($value)) {
                    $data->{$key} = (string) $value;
                }

                if (is_int($value)) {
                    $data->{$key} = (int) $value;
                }
            }
        }

        return $data;
    }
}
