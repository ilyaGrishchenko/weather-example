<?php

namespace App\Models;

use App\Services\AccuWeather;
use App\Services\SevenTimer;

/**
 * Class Source
 * @package App\Models
 *
 * @property string $title
 * @property string $apiService
 */
class Source
{
    /** @var array  */
    const SOURCES = [
        [
            'id' => 1,
            'title' => '7Timer!',
            'apiService' => SevenTimer::class
        ],
        [
            'id' => 2,
            'title' => 'AccuWeather',
            'apiService' => AccuWeather::class
        ]
    ];

    /**
     * Source constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->apiService = $data['apiService'];
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getApiService()
    {
        return $this->apiService;
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::SOURCES;
    }
}
