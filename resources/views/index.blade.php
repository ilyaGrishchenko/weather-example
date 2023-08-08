<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Weather-app</title>

    <link rel="stylesheet" href="{{ asset("css/lib/bootstrap-5.3.1/bootstrap.min.css") }}">

    <script src="{{ asset("js/lib/jquery-3.7.0/jquery-3.7.0.min.js") }}"></script>

</head>
<body>

<div class="container">

    <div class="row">
        <div class="offset-2 col-8 mb-3">
            <label for="source-name" class="mb-2">Source name</label>
            <select id="source-name" class="js-source-name form-select">
                <option value="seven_timer">7Timer!</option>
                <option value="accu_weather">AccuWeather</option>
            </select>
        </div>

        <div class="offset-4 col-4 mb-3">
            <button class="js-send btn btn-success col-12">Send request</button>
        </div>
    </div>

</div>

</body>
</html>
