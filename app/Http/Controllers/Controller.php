<?php

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(title: "My API", version: "1.0.0")]
#[OA\SecurityScheme(
    securityScheme: 'sanctum',
    type: 'http',
    scheme: 'bearer',
    description: "Enter your Sanctum token (e.g. 1|abcdef...)"
)]

abstract class Controller
{
    //
}
