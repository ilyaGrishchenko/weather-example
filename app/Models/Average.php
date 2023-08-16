<?php

namespace App\Models;

/**
 * Class Average
 * @package App\Helpers
 *
 * @property array $temp
 * @property array $cloud
 * @property array $prec
 * @property array $windSpeed
 * @property array $windDir
 */
class Average implements ResponseInterface
{
    /** @var array  */
    protected $temp = [];
    /** @var array  */
    protected $cloud = [];
    /** @var array  */
    protected $prec = [];
    /** @var array  */
    protected $windSpeed = [];
    /** @var array  */
    protected $windDir = [];
    /** @var string  */
    const CLOUDY_STRING = 'Cloudy';
    /** @var string  */
    const CLEAR_STRING = 'Clear';

    /**
     * @param $sourceRes
     */
    public function add($sourceRes)
    {
        foreach ($sourceRes as $item) {
            if (is_string($item)) {
                continue;
            }

            $this->prepareTemp($item);
            $this->prepareCloud($item);
            $this->preparePrec($item);
            $this->prepareWind($item);
        }
    }

    /**
     * @return array
     */
    public function get()
    {
        $temp = array_unique($this->temp);
        $temp = round(array_sum($temp) / count($temp), 1, PHP_ROUND_HALF_DOWN);

        $precArr = [];
        if (in_array('none', $this->prec)) {
            $precArr[] = 'Probably';
        }
        $precArr = array_merge(
            $precArr,
            array_diff($this->prec, ['none'])
        );
        $prec = implode( ' ', $precArr);

        $windSpeed = implode(', ', array_unique($this->windSpeed));
        $windDir = implode(', ', array_unique($this->windDir));

        return [
            'title' => 'Average',
            'temp' => $temp,
            'cloud' => $this->cloud,
            'prec' => $prec,
            'wind' => [
                'speed' => $windSpeed,
                'dir' => $windDir
            ]
        ];
    }

    /**
     * @param $item
     */
    protected function prepareTemp($item)
    {
        if (key_exists('temp', $item)) {
            $temp = $item['temp'];
            if (is_array($temp)) {
                $res = [$temp['min'], $temp['max']];
            } else {
                $res = [$temp];
            }

            $this->temp = array_merge($this->temp, $res);
        }
    }

    /**
     * @param $item
     */
    protected function prepareCloud($item)
    {
        // If we've found clouds in any of previous iterations, skip this one, priority to cloudy weather
        if ($this->cloud == self::CLOUDY_STRING) {
            return;
        }

        // Priority to cloudy weather
        if (key_exists('cloud', $item)) {
            $cloud = $item['cloud'];
            if (is_array($cloud)) {
                if ($cloud['min'] > 30) {
                    $this->cloud = self::CLOUDY_STRING;
                } else {
                    $this->cloud = self::CLEAR_STRING;
                }
            } else {
                $this->cloud = $cloud != self::CLEAR_STRING ? self::CLOUDY_STRING : self::CLEAR_STRING;
            }
        }
    }

    /**
     * @param $item
     */
    protected function preparePrec($item)
    {
        // Store all values in one heap, parse them before returning
        if (key_exists('prec', $item)) {
            $this->prec[] = $item['prec'];
        }
    }

    protected function prepareWind($item)
    {
        if (key_exists('wind', $item)) {
            $speedRes = [];
            $dirRes = [];
            foreach ($item['wind'] as $windItem) {
                $speedRes[] = $windItem['speed'];
                $dirRes[] = $windItem['dir'];
            }

            $this->windSpeed = array_merge($this->windSpeed, array_unique($speedRes));
            $this->windDir = array_merge($this->windDir, array_unique($dirRes));
        }
    }
}
