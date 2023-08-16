<?php

namespace App\Services;

/**
 * Class SevenTimer
 * @package App\Services
 */
class SevenTimer extends SourceService
{
    /** @var int  */
    const DAY_HOURS = 24;
    /** @var int  */
    const TP_INTERVAL = 3;
    /** @var int  */
    const TP_LENGTH = 8;
    /** @var string  */
    const ENV_BASE_URL_KEY = '7TIMER_BASE_URL';
    /** @var array  */
    const DEFAULT_QUERY_PARAMS = [
        'output' => 'json',
        'product' => 'astro',
        'unit' => 'metric'
    ];
    /** @var array  */
    const CLOUD_MEANINGS = [
        1 => [0, 6],
        2 => [6, 19],
        3 => [19, 31],
        4 => [31, 44],
        5 => [44, 56],
        6 => [56, 69],
        7 => [69, 81],
        8 => [81, 94],
        9 => [94, 100]
    ];
    /** @var array  */
    const WIND_MEANINGS = [
        1 => 'Calm',
        2 => 'Light',
        3 => 'Moderate',
        4 => 'Fresh',
        5 => 'Strong',
        6 => 'Gale',
        7 => 'Storm',
        8 => 'Hurricane'
    ];

    /**
     * @throws \Throwable
     */
    protected function prepareQueryParams()
    {
        $this->queryParams = [
            'lat' => $this->city->getLatitude(),
            'lon' => $this->city->getLongitude()
        ];
    }

    /**
     *
     */
    protected function prepareApiFullUrl()
    {
        $this->fullApiUrl = $this->getBaseUrl() . '?' . http_build_query($this->queryParams);
        # dd($this->fullApiUrl);
    }

    /**
     *
     */
    protected function checkResponseErrors()
    {
        parent::checkResponseErrors();

        if ($this->errors) {
            return;
        }

        // 7Timer returns string "ERR: no product specified" with 200 status code when error
        // Find str ERR in response
        if (strpos($this->rawServiceResponse->body(), 'ERR') !== false) {
            $this->errors[] = $this->rawServiceResponse->body();
        }
    }

    /**
     * @return string|void
     */
    protected function parseServiceResponse()
    {
        $serviceResponse = $this->rawServiceResponse->json();

        $initDay = $serviceResponse['init'];
        // Get two last chars of date string - its a init hour of forecast
        $initHour = intval(substr($initDay, -2));

        // Calc timepoints of rest of day
        $tzHour = $initHour + $this->city->getTimezone();
        $tpOffset = floor((self::DAY_HOURS - $tzHour) / self::TP_INTERVAL);
        $nextDay = array_slice($serviceResponse['dataseries'], $tpOffset, self::TP_LENGTH);

        /**
         * offsets values:
         * 0 - for morning
         * 2 - day
         * 4 - evening
         * 6 - night
         */
        $this->result = [
            'morning' => $this->getData($nextDay, 0),
            'day' => $this->getData($nextDay, 2),
            'evening' => $this->getData($nextDay, 4),
            'night' => $this->getData($nextDay, 6)
        ];
    }

    /**
     * @param $array
     * @return float|int
     */
    protected function getAverage($array)
    {
        if (count($array) == 0) {
            return 0;
        }

        return array_sum($array) / count($array);
    }

    /**
     * @param $array
     * @return string
     */
    protected function getPrecipitation($array)
    {
        $precType = array_column($array, 'prec_type');
        return implode(', ', array_unique($precType));
    }

    /**
     * @param $array
     * @return mixed
     */
    protected function getCloudiness($array)
    {
        $res = [];
        $cloud = array_column($array, 'cloudcover');
        $cloud = array_unique($cloud);
        foreach ($cloud as $item) {
            $res = array_merge($res, self::CLOUD_MEANINGS[$item]);
        }
        return ['min' => min($res), 'max' => max($res)];
    }

    /**
     * @param $array
     * @return array
     */
    protected function getWind($array)
    {
        $res = [];
        $wind = array_column($array, 'wind10m');

        foreach ($wind as $item) {
            $speedIndex = intval($item['speed']);
            $res[] = [
                'speed' => self::WIND_MEANINGS[$speedIndex],
                'dir' => $item['direction']
            ];
        }

        return $res;
    }

    /**
     * @param $nextDayData
     * @param $offset
     * @return array
     */
    protected function getData($nextDayData, $offset)
    {
        $data = array_slice($nextDayData, $offset, 2);
        return [
            'temp' => $this->getAverage( array_column($data, 'temp2m') ),
            'prec' => $this->getPrecipitation($data),
            'cloud' => $this->getCloudiness($data),
            'wind' => $this->getWind($data)
        ];
    }
}
