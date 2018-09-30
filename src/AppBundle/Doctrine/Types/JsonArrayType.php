<?php

namespace AppBundle\Doctrine\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType as BaseJsonArrayType;

class JsonArrayType extends BaseJsonArrayType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return json_encode($value, JSON_UNESCAPED_UNICODE);
    }
}