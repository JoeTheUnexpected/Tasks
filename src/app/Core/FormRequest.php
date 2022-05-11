<?php


namespace App\Core;


abstract class FormRequest
{
    protected const RULE_REQUIRED = 'required';
    protected const RULE_EMAIL = 'email';
    protected const RULE_MIN = 'min';
    protected const RULE_MAX = 'max';
    protected const RULE_NULLABLE = 'nullable';
    protected const RULE_BOOLEAN = 'boolean';
    protected const RULE_PASSWORD = 'password';
    protected const RULE_UNIQUE = 'unique';

    protected Model $model;
    protected array $errors = [];

    abstract public function rules(): array;

    public function __construct(Model $model)
    {
        $this->model = $model;

        $this->loadData();
    }

    protected function loadData(): void
    {
        $data = [];

        foreach ($_POST as $key => $value) {
            if ($key === '_method') {
                continue;
            }

            $data[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
        }

        foreach ($data as $key => $value) {
            if (property_exists($this->model, $key)) {
                App::getInstance()->session->setOld($key, $value);
                $this->model->{$key} = $value;
            }
        }
    }

    public function validated(): array
    {
        $validated = [];

        foreach ($this->rules() as $attribute => $rules) {
            $validated[$attribute] = $this->model->{$attribute};

            foreach ($rules as $rule) {
                if (!is_string($rule)) {
                    $ruleName = $rule[0];
                } else {
                    $ruleName = $rule;
                }

                if ($ruleName === self::RULE_REQUIRED && !$validated[$attribute]) {
                    $this->addError($attribute, self::RULE_REQUIRED);
                }

                if ($ruleName === self::RULE_EMAIL && !filter_var($validated[$attribute], FILTER_VALIDATE_EMAIL)) {
                    $this->addError($attribute, self::RULE_EMAIL);
                }

                if ($ruleName === self::RULE_MIN && strlen($validated[$attribute]) < $rule['min']) {
                    $this->addError($attribute, self::RULE_MIN, $rule);
                }

                if ($ruleName === self::RULE_MAX && strlen($validated[$attribute]) > $rule['max']) {
                    $this->addError($attribute, self::RULE_MAX, $rule);
                }

                if ($ruleName === self::RULE_BOOLEAN) {
                    if (isset($_POST[$attribute])) {
                        $validated[$attribute] = true;
                    } else {
                        $validated[$attribute] = false;
                    }
                }

                if ($ruleName === self::RULE_PASSWORD && !preg_match('/[A-Za-z0-9' . preg_quote('.%^&()$#@!/-_+', '/') . ']+/', $validated[$attribute])) {
                    $this->addError($attribute, self::RULE_PASSWORD);
                }

                if ($ruleName === self::RULE_UNIQUE) {
                    $stmt = App::getInstance()->db->pdo->prepare("select * from {$this->model->tableName()} where $attribute = :$attribute");
                    $stmt->bindValue(":$attribute", $validated[$attribute]);
                    $stmt->execute();

                    if ($stmt->fetch()) {
                        $this->addError($attribute, self::RULE_UNIQUE);
                    }
                }
            }
        }

        if (!empty($this->errors)) {
            App::getInstance()->response->back();
        }

        return $validated;
    }

    private function addError(string $attribute, string $rule, array $params = []): void
    {
        $message = $this->errorMessages()[$rule] ?? '';
        foreach ($params as $key => $value) {
            $message = str_replace("{{$key}}", $value, $message);
        }
        $message = str_replace('{field_name}', "\"{$this->model->labels()[$attribute]}\"", $message);
        App::getInstance()->session->setError($attribute, $message);
        $this->errors[$attribute][] = $message;
    }

    private function errorMessages(): array
    {
        return [
            self::RULE_REQUIRED => 'Поле {field_name} обязательно',
            self::RULE_EMAIL => 'Некорректный email',
            self::RULE_MIN => 'Поле {field_name} должно содержать минимум {min} символов',
            self::RULE_MAX => 'Поле {field_name} должно содержать максимум {max} символов',
            self::RULE_PASSWORD => 'Пароль должен состоять из латинских букв и символов .%^&()$#@!/-_+',
            self::RULE_UNIQUE => 'Это значение {field_name} занято',
        ];
    }
}