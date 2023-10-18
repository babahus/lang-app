<?php

namespace App\Http\Requests\Courses;

use App\Contracts\DTO;
use App\DataTransfers\Courses\CreateCourseDTO;
use App\Http\Requests\BaseRequest;
use Auth;
use Illuminate\Support\Facades\Cache;

class CourseCreateRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = Auth::user();
        $userRoleId = Cache::get("users_role_" . $user->id);

        if ($userRoleId && ($userRoleId['role_id'] == 2 || $userRoleId['role_id'] == 3)) {
            return true;
        }

        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'       => 'required|string|max:225',
            'description' => 'required|string|max:255',
            'price'       => 'nullable|numeric|max:100000'
        ];
    }

    public function getDTO(): CreateCourseDTO
    {
        return new CreateCourseDTO(
            $this->input('title'),
            $this->input('description'),
            $this->input('price') ?? 0
        );
    }
}
