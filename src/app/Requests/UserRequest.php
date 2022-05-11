<?php


namespace App\Requests;


use App\Core\FormRequest;

class UserRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [self::RULE_REQUIRED],
            'email' => [self::RULE_REQUIRED, self::RULE_UNIQUE],
            'password' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 8] , self::RULE_PASSWORD]
        ];
    }

    public function validated(): array
    {
        $attributes = parent::validated();
        $attributes['password'] = password_hash($attributes['password'], PASSWORD_DEFAULT);

        return $attributes;
    }
}