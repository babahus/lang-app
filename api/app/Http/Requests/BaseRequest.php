<?php

namespace App\Http\Requests;

use App\Contracts\DTO;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    abstract public function getDTO(): DTO;
}
