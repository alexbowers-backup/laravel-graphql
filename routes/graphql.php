<?php

use AlexBowers\GraphQl\Http\Controllers\GraphqlController;
use Illuminate\Support\Facades\Route;

Route::get(config('graphql.endpoint'), GraphqlController::class . "@process");
Route::post(config('graphql.endpoint'), GraphqlController::class . "@process");
