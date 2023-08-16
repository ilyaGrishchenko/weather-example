<?php

namespace App\Models;

/**
 * Class City
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $latitude
 * @property string $longitude
 * @property int $timezone
 * @property int $locationKey
 */
class City
{
    /** @var array  */
    const CITIES = [
        [
            'id' => 1,
            'code' => 'spb',
            'title' => 'Saint-Petersburg',
            'latitude' => 59.937500,
            'longitude' => 30.308611,
            'timezone' => 4,
            'locationKey' => 295212
        ],
        [
            'id' => 2,
            'code' => 'msk',
            'title' => 'Moscow',
            'latitude' => 55.752220,
            'longitude' => 37.615560,
            'timezone' => 4,
            'locationKey' => 294021
        ],
    ];

    /**
     * City constructor.
     * @param $data
     */
    public function __construct($data)
    {
        $this->title = $data['title'];
        $this->latitude = $data['latitude'];
        $this->longitude = $data['longitude'];
        $this->timezone = $data['timezone'];
        $this->locationKey = $data['locationKey'];
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
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return int
     */
    public function getTimezone()
    {
        return $this->timezone;
    }

    /**
     * @return int
     */
    public function getLocationKey()
    {
        return $this->locationKey;
    }

    /**
     * @return array
     */
    public static function getList()
    {
        return self::CITIES;
    }

    /**
     * @param $id
     * @return City
     */
    public static function find($id)
    {
        $found = array_filter(self::getList(), function($item) use ($id) {
            return $item['id'] == intval($id);
        });
        $city = array_shift($found);

        return new self($city);
    }

    /**
     * @param $code
     * @return City
     */
    public static function findByCode($code)
    {
        $found = array_filter(self::getList(), function($item) use ($code) {
            return $item['code'] == strval($code);
        });
        $city = array_shift($found);

        return new self($city);
    }
}
