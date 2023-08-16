<?php

namespace App\Services;

use App\Models\City;
use App\Models\Source;
use Illuminate\Support\Facades\Http;

/**
 * Class SourceService
 * @package App\Services
 *
 * @property City $city
 * @property array $queryParams
 * @property string $fullApiUrl
 * @property \Illuminate\Http\Client\Response $rawServiceResponse
 * @property array $result
 * @property Source $sourceModel
 * @property array $errors
 */
abstract class SourceService
{
    /** @var string  */
    const ENV_BASE_URL_KEY = 'BASE';
    /** @var array  */
    const DEFAULT_QUERY_PARAMS = [];
    /** @var Source|string  */
    protected $sourceModel = '';
    /** @var array  */
    protected $queryParams = [];
    /** @var string  */
    protected $fullApiUrl = '';
    /** @var \Illuminate\Http\Client\Response */
    protected $rawServiceResponse;
    /** @var array  */
    protected $result = [];
    /** @var array  */
    protected $errors = [];

    /**
     * SourceService constructor.
     * @param Source $source
     * @param City $city
     */
    public function __construct(Source $source, City $city)
    {
        $this->sourceModel = $source;
        $this->city = $city;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function get()
    {
        $this->prepareQueryParams();
        $this->applyDefaultParams();
        $this->prepareApiFullUrl();
        $this->sendRequest();
        $this->checkResponseErrors();

        if (!$this->errors) {
            $this->parseServiceResponse();
        }

        return $this->prepareResult();
    }

    /**
     * @return array
     */
    protected function prepareResult()
    {
        $res = [];

        $res['title'] = $this->sourceModel->getTitle();

        if ($this->errors) {
            $res['errors'] = implode('; ', $this->errors);
            return $res;
        }

        return array_merge(
            $res,
            $this->result
        );
    }

    /**
     *
     */
    protected function sendRequest()
    {
        try {
            $this->rawServiceResponse = Http::get($this->fullApiUrl);
        } catch (\Throwable $e) {
            $this->errors[] = $e->getMessage();
        }
    }

    /**
     *
     */
    protected function checkResponseErrors()
    {
        if (empty($this->rawServiceResponse)) {
            $this->errors[] = $this->sourceModel->getTitle() . ' response is empty';
            return;
        }

        if ($this->rawServiceResponse->failed()) {
            $this->errors[] = 'Request to ' . $this->sourceModel->getTitle() . ' failed';
            return;
        }
    }

    /**
     * @return void
     */
    protected function parseServiceResponse()
    {
        $this->result = $this->rawServiceResponse->json();
    }

    /**
     * @return mixed
     */
    protected function getBaseUrl()
    {
        return env(static::ENV_BASE_URL_KEY);
    }

    /**
     * @throws \Throwable
     */
    protected function prepareApiFullUrl()
    {
        throw new \Exception('Must be implemented in descendant class');
    }

    /**
     *
     */
    protected function prepareQueryParams()
    {
        $this->queryParams = self::DEFAULT_QUERY_PARAMS;
    }

    /**
     *
     */
    protected function applyDefaultParams()
    {
        $this->queryParams = array_merge(
            $this->queryParams,
            static::DEFAULT_QUERY_PARAMS
        );
    }
}
