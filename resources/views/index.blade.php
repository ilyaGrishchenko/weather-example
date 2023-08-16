<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Weather-app</title>

    <link rel="stylesheet" href="{{ asset("css/lib/bootstrap-5.3.1/bootstrap.min.css") }}">
    <link rel="stylesheet" href="css/app.css">

    <script src="{{ asset("js/lib/jquery-3.7.0/jquery-3.7.0.min.js") }}"></script>
    <script src="{{ asset("js/app.js") }}"></script>

</head>
<body>

<div class="container">

    <div class="row">
        <form class="js-content">
            <div class="offset-2 col-8 mb-3">
                <label for="city-name" class="h5 d-block">City name</label>
                <select id="city-name" class="form-select js-city-code" name="cityCode">
                    @foreach($cities as $city)
                        <option value="{{ $city['code'] }}">{{ $city['title'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="offset-2 col-8 mb-3">
                <label for="source-name" class="mb-2 h5">Sources</label>
                <div class="mb-2">
                    <input id="average" class="form-check-input js-average" type="checkbox">
                    <label class="form-check-label" for="average">Average</label>
                </div>
                <div class="js-sources d-flex">
                @foreach ($sources as $source)
                    <div class="col-3 form-check d-inline-flex">
                        <input id="check_{{ $source['id'] }}"
                               type="checkbox"
                               class="form-check-input"
                               name="sources[]"
                               value="{{ $source['id'] }}"
                        >
                        <label class="form-check-label" for="check_{{ $source['id'] }}">{{ $source['title'] }}</label>
                    </div>
                @endforeach
                </div>
            </div>

            <div class="offset-4 col-4 mb-3">
                <button class="js-send btn btn-success col-12">
                    <span class="js-spinner spinner-border-sm" aria-hidden="true"></span>
                    Send request
                </button>
            </div>
        </form>
    </div>

    <div class="js-result"> </div>
</div>

<div class="visually-hidden js-source-forecast">
    <div class="row">
        <div class="col-12 h3 js-title"></div>
    </div>
    <div class="row js-average-tpl">
        <div class="col-3 js-morning"></div>
        <div class="col-3 js-day"></div>
        <div class="col-3 js-evening"></div>
        <div class="col-3 js-night"></div>
    </div>
    <hr>
</div>

<div class="js-daypart-forecast visually-hidden mt-2">
    <div class="js-part-title"><strong></strong></div>
    <div class="js-temp">Temperature: <span class="js-value"></span></div>
    <div class="js-cloud">Cloudiness: <span class="js-value"></span></div>
    <div class="js-prec">Precipitation: <span class="js-value"></span></div>
    <div class="js-wind">
        Wind:
        <div class="">Speed: <span class="js-speed"></span></div>
        <div class="">Direction: <span class="js-dir"></span></div>
    </div>
</div>

<div class="js-error visually-hidden mt-2">
    <div class="col-12 h3 js-title"></div>
    <div class="js-error-text text-danger"></div>
</div>

</body>
</html>
