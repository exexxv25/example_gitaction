<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{

/**
 * @OA\Info(title="Neighbors", version="1.2.0")
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Header autorization toker JWT",
 *     name="Token Bearer",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="apiAuth",
 * ),
 */

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
