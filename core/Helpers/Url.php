<?php

namespace Core\Helpers;

class Url
{
    public static function getNormalizedRoute(string $reqUri): array
    {
        $param = null;

        if (str_contains($reqUri, '?')) {
            $reqUri = substr($reqUri, 0, strpos($reqUri, '?'));
        }

        preg_match_all('([1-9][0-9]*)', $reqUri, $id);

        if (!empty($id[0])) {
            $param = $id[0][0];
        }

        return [
            'uri' => preg_replace('([1-9][0-9]*)', '{id}', $reqUri),
            'param' => $param
        ];
    }
}