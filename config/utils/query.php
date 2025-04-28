<?php

if (!function_exists('getPagination')) {
    function getPagination($query): array
    {
        $page = isset($query['page']) ? abs($query['page']) : 1;
        $limit = isset($query['count']) ? abs($query['count']) : 10;
        $skip = ($page - 1) * $limit;

        return [
            'skip' => $skip,
            'limit' => $limit
        ];
    }
    return [
        'getPagination' => 'getPagination'
    ];
}
