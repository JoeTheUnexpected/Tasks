<?php


namespace App\Core;


class Session
{
    private const FLASH_KEY = 'flash_messages';
    private const ERROR_KEY = 'errors';
    private const OLD_KEY = 'old';

    public function __construct()
    {
        session_start();

        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            $flashMessage['remove'] = true;
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;

        $errors = $_SESSION[self::ERROR_KEY] ?? [];
        foreach ($errors as $key => &$error) {
            $error['remove'] = true;
        }
        $_SESSION[self::ERROR_KEY] = $errors;

        $oldValues = $_SESSION[self::OLD_KEY] ?? [];
        foreach ($oldValues as $key => &$value) {
            $value['remove'] = true;
        }
        $_SESSION[self::OLD_KEY] = $oldValues;
    }

    public function setFlash($key, $message)
    {
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false,
            'value' => $message
        ];
    }

    public function getFlash($key)
    {
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? null;
    }

    public function hasFlash($key): bool
    {
        return isset($_SESSION[self::FLASH_KEY][$key]);
    }

    public function setError($key, $message)
    {
        if (!isset($_SESSION[self::ERROR_KEY][$key])) {
            $_SESSION[self::ERROR_KEY][$key] = [
                'remove' => false,
                'values' => [$message]
            ];
        } else {
            $_SESSION[self::ERROR_KEY][$key]['values'][] = $message;
        }

    }

    public function getAllErrors(): array
    {
        return $_SESSION[self::ERROR_KEY] ?? [];
    }

    public function getErrors($key)
    {
        return $_SESSION[self::ERROR_KEY][$key]['values'] ?? null;
    }

    public function getFirstError($key)
    {
        return $_SESSION[self::ERROR_KEY][$key]['values'][0] ?? null;
    }

    public function hasErrors(): bool
    {
        return isset($_SESSION[self::ERROR_KEY]) && !empty($_SESSION[self::ERROR_KEY]);
    }

    public function setOld(string $key, $value)
    {
        $_SESSION[self::OLD_KEY][$key] = [
            'remove' => false,
            'value' => $value
        ];
    }

    public function unsetOld()
    {
        unset($_SESSION[self::OLD_KEY]);
    }

    public function old(string $key, $defaultValue = '')
    {
        return $_SESSION[self::OLD_KEY][$key]['value'] ?? $defaultValue;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    public function __destruct()
    {
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];
        foreach ($flashMessages as $key => &$flashMessage) {
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }
        $_SESSION[self::FLASH_KEY] = $flashMessages;

        $errors = $_SESSION[self::ERROR_KEY] ?? [];
        foreach ($errors as $key => &$error) {
            if ($error['remove']) {
                unset($errors[$key]);
            }
        }
        $_SESSION[self::ERROR_KEY] = $errors;

        $oldValues = $_SESSION[self::OLD_KEY] ?? [];
        foreach ($oldValues as $key => &$value) {
            if ($value['remove']) {
                unset($oldValues[$key]);
            }
        }
        $_SESSION[self::OLD_KEY] = $oldValues;
    }
}