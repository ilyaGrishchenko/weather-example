<?php

namespace App\Services;

use App\Models\Source;

/**
 * Class ServiceFactory
 * @package App\Services
 */
class ServiceFactory
{
    /**
     * @param $id
     * @param $city
     * @return SourceService
     * @throws \Throwable
     */
    public static function createService($id, $city)
    {
        $id = intval($id);
        $source = array_filter(Source::getList(), function ($item) use ($id) {
            return $item['id'] == $id;
        });
        $source = array_shift($source);

        if (!$source['apiService']) {
            throw new \Exception('Weather source with ID = ' . $id . ' has no apiService');
        }

        $sourceObject = new Source($source);

        $className = $sourceObject->getApiService();
        /** @var SourceService $obj */
        $obj = new $className($sourceObject, $city);
        return $obj;
    }
}
