<?php

namespace App\Services;

/**
 * Class AccuWeather
 * @package App\Services
 */
class AccuWeather extends SourceService
{
    /** @var string  */
    const ENV_BASE_URL_KEY = 'ACCUWEATHER_BASE_URL';
    /** @var array  */
    const DEFAULT_QUERY_PARAMS = [
        'metric' => 'true'
    ];

    protected function prepareQueryParams()
    {
        $this->queryParams = [
            'apikey' => env('ACCUWEATHER_APIKEY')
        ];
    }

    /**
     *
     */
    protected function prepareApiFullUrl()
    {
        $baseUrl = $this->getBaseUrl() . '' . $this->city->getLocationKey();
        $this->fullApiUrl = $baseUrl . '?' . http_build_query($this->queryParams);
    }

    /**
     * @return string|void
     */
    protected function parseServiceResponse()
    {
        $serviceResponse = $this->rawServiceResponse->json();
        $nextDay = $serviceResponse['DailyForecasts'][1];

        $this->result = [
            'morning' => [],
            'day' => $this->getData($nextDay, 'Day'),
            'evening' => [],
            'night' => $this->getData($nextDay, 'Night')
        ];
    }

    /**
     * @param $array
     * @return array
     */
    protected function getDayTemp($array)
    {
        $temp = $array['Temperature'];
        return [
            'min' => $temp['Minimum']['Value'],
            'max' => $temp['Maximum']['Value']
        ];
    }

    /**
     * @param $array
     * @return string
     */
    protected function getPrecipitation($array)
    {
        if ($array['HasPrecipitation']) {
            return $array['PrecipitationIntensity'] . ' ' . $array['PrecipitationType'];
        }

        return 'none';
    }

    /**
     * @param $nextDayData
     * @param $key
     * @return array
     */
    protected function getData($nextDayData, $key)
    {
        $data = $nextDayData[$key];
        return [
            'temp' => $this->getDayTemp($nextDayData),
            'prec' => $this->getPrecipitation($data),
            'cloud' => $data['IconPhrase'],
        ];
    }
}
