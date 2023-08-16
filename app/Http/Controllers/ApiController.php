<?php

namespace App\Http\Controllers;
use App\Models\Average;
use App\Models\Response;
use App\Models\City;
use App\Models\ResponseInterface;
use App\Models\Source;
use App\Services\ServiceFactory;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class ApiController
 * @package App\Http\Controllers
 */
class ApiController extends Controller
{
    /**
     * Get forecast for selected sources
     * @param Request $request
     * @return array
     * @throws \Throwable
     */
    public function show(Request $request)
    {
        $sources = $request->input('sources');
        $cityCode = $request->input('cityCode');

        $response = new Response();

        $this->processingServices($sources, $cityCode, $response);

        return $response->get();
    }

    /**
     * Get forecast for all sources
     * @param $cityCode
     * @return array
     * @throws \Throwable
     */
    public function average($cityCode)
    {
        $sources = array_column(Source::getList(), 'id');

        $average = new Average();

        $this->processingServices($sources, $cityCode, $average);

        return $average->get();
    }

    /**
     * @param $sources
     * @param $cityCode
     * @param ResponseInterface $response
     * @throws \Throwable
     */
    protected function processingServices($sources, $cityCode, ResponseInterface $response)
    {
        if (empty($cityCode)) {
            throw new BadRequestHttpException('City id is empty');
        }

        if (empty($sources)) {
            throw new BadRequestHttpException('Sources is empty');
        }

        $city = City::findByCode($cityCode);
        foreach ($sources as $sourceId) {
            $service = ServiceFactory::createService($sourceId, $city);
            $response->add($service->get());
        }
    }
}
