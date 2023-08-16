<?php

namespace App\Models;

/**
 * Class Response
 * @package App\Helpers
 *
 * @property array $response
 */
class Response implements ResponseInterface
{
    /** @var array  */
    protected $response = [];

    /**
     * @param array $sourceRes
     */
    public function add($sourceRes)
    {
        $res = [];
        foreach ($sourceRes as $key => $item) {
            $res[$key] = $this->prepareItem($item);
        }

        $this->response[] = $res;
    }


    /**
     * @return array
     */
    public function get()
    {
        return $this->response;
    }

    /**
     * @param $item
     * @return mixed
     */
    public function prepareItem($item)
    {
        if (is_string($item)) {
            return $item;
        }

        $res = [];

        if (key_exists('temp', $item)) {
            $temp = $item['temp'];
            if (is_array($temp)) {
                $res['temp'] = $temp['min'] . '°C - ' . $temp['max'] . '°C';
            } else {
                $res['temp'] = $temp . '°C';
            }
        }

        if (key_exists('prec', $item)) {
            $res['prec'] = ucfirst($item['prec']);
        }

        if (key_exists('cloud', $item)) {
            $cloud = $item['cloud'];
            if (is_array($cloud)) {
                $res['cloud'] = $cloud['min'] . '% - ' . $cloud['max'] . '%';
            } else {
                $res['cloud'] = $cloud;
            }
        }

        if (key_exists('wind', $item)) {
            $speedRes = [];
            $dirRes = [];
            foreach ($item['wind'] as $windItem) {
                $speedRes[] = $windItem['speed'];
                $dirRes[] = $windItem['dir'];
            }

            $res['wind'] = [
                'speed' => implode(', ', array_unique($speedRes)),
                'dir' => implode(', ', array_unique($dirRes))
            ];
        }

        return $res;
    }
}
