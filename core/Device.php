<?php

namespace Core;

class Device
{
    public static function isMobile(): bool
    {
        return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'mobile'));
    }

    public static function isTablet(): bool
    {
        return is_numeric(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), 'tablet'));
    }

    public static function isDesktop(): bool
    {
        return !static::isMobile() && !static::isTablet();
    }
}