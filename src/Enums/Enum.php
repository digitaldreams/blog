<?php

namespace Blog\Enums;

use ReflectionClass;

/**
 * This class use code from BenSampo\Enum\Enum class.
 * To use full package visit: https://github.com/BenSampo/laravel-enum.
 */
abstract class Enum
{

    /**
     * Constants cache.
     *
     * @var array
     */
    protected static $constCacheArray = [];

    /**
     * Get all of the constants defined on the class.
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    protected static function getConstants(): array
    {
        $calledClass = get_called_class();

        if (!array_key_exists($calledClass, static::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            static::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return static::$constCacheArray[$calledClass];
    }

    /**
     * Get all of the enum keys.
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public static function getKeys(): array
    {
        return array_keys(static::getConstants());
    }

    /**
     * Get all of the enum values.
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public static function getValues(): array
    {
        return array_values(static::getConstants());
    }

    /**
     * Get the key for a single enum value.
     *
     * @param mixed $value
     *
     * @return string
     *
     * @throws \ReflectionException
     */
    public static function getKey($value): string
    {
        return array_search($value, static::getConstants(), true);
    }

    /**
     * Get the value for a single enum key.
     *
     * @param string $key
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public static function getValue(string $key)
    {
        return static::getConstants()[$key];
    }

    /**
     * Get a random key from the enum.
     *
     * @return string
     *
     * @throws \ReflectionException
     */
    public static function getRandomKey(): string
    {
        $keys = static::getKeys();

        return $keys[array_rand($keys)];
    }

    /**
     * Get a random value from the enum.
     *
     * @return mixed
     *
     * @throws \ReflectionException
     */
    public static function getRandomValue()
    {
        $values = static::getValues();

        return $values[array_rand($values)];
    }
}
