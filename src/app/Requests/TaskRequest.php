<?php


namespace App\Requests;


use App\Core\FormRequest;

class TaskRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_name' => [self::RULE_REQUIRED],
            'user_email' => [self::RULE_REQUIRED, self::RULE_EMAIL],
            'text' => [self::RULE_REQUIRED, [self::RULE_MIN, 'min' => 5]],
            'completed' => [self::RULE_BOOLEAN, self::RULE_NULLABLE]
        ];
    }
}