<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SanphamsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
        'name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Ví dụ: Tối đa 2MB, là ảnh
        'mota' => 'required|string',

        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
           'name.required' => 'Trường tên là bắt buộc.',
        'name.string' => 'Trường tên phải là một chuỗi.',
        'name.max' => 'Trường tên không được dài quá 255 ký tự.',
        'price.required' => 'Trường giá là bắt buộc.',
        'price.numeric' => 'Trường giá phải là một số.',
        'price.min' => 'Trường giá phải lớn hơn hoặc bằng 0.',
        'image.image' => 'Tệp tải lên phải là một hình ảnh.',
        'image.mimes' => 'Hình ảnh phải có định dạng jpeg, png, jpg, gif hoặc svg.',
        'image.max' => 'Hình ảnh không được lớn hơn 2048KB.',
        'mota.required' => 'Trường mô tả là bắt buộc.',
        'mota.string' => 'Trường mô tả phải là một chuỗi.',
        ];
    }
}
