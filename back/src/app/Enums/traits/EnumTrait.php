<?php

namespace App\Enums\traits;

trait EnumTrait
{
    /**
     * Локализация статусов
     *
     * @return string
     */
    public function translate(): string
    {
        return trans("enums/" . self::LOCALIZATION . '.' . $this->value);
    }

    /**
     * Список перечислений
     *
     * @return array
     */
    public static function options(): array
    {
        $options = [];
        foreach (self::cases() as $case) {
            $options[$case->value] = $case->translate();
        }

        return $options;
    }
}
