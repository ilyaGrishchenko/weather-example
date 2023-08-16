let apiUrl = 'http://127.0.0.1:8000/api/weather/';

$(document).ready(function() {
    $('.js-send').on('click', function (e) {
        e.preventDefault();
        clickHandler();
    });

    $('.js-average').on('change', function(e) {
        let sources = $('.js-sources').find('input');
        if ($(this).is(':checked')) {
            sources.attr('disabled', true);
        } else {
            sources.attr('disabled', false);
        }
    });
});

function clickHandler() {
    let cityCode = $('.js-city-code').val();
    addButtonSpinner();
    if ($('.js-average').is(':checked')) {
        averageForecast(cityCode);
    } else {
        forecastBySources(cityCode);
    }
}

function forecastBySources(cityCode) {
    clearResultPlacement();
    $.ajax({
        method: 'GET',
        url: apiUrl + cityCode + '?' + $('.js-content').serialize(),
        success: function (response) {
            showForecasts(response);
            removeButtonSpinner();
        },
        error: function (response) {
            showError(response);
            removeButtonSpinner();
        }
    });
}

function averageForecast(cityCode) {
    clearResultPlacement();
    $.ajax({
        method: 'GET',
        url: apiUrl + cityCode + '/average',
        success: function (response) {
            showAverage(response);
            removeButtonSpinner();
        },
        error: function (response) {
            showError(response);
            removeButtonSpinner();
        }
    });
}

function showError(response) {
    let resultPlacement = $('.js-result');
    resultPlacement.html(response.responseJSON.message);
    resultPlacement.addClass('text-danger');
}

function showAverage(response) {
    let resultPlacement = $('.js-result');
    let sourceItemTpl = $('.js-source-forecast').clone();
    let averageResPlacement = $('.js-average-tpl').clone();

    averageResPlacement.empty();
    averageResPlacement.append( fillForecast(response.title, response));
    averageResPlacement.find('strong').addClass('h3');

    sourceItemTpl.find('.js-part-title').html(response.title);
    resultPlacement.append(averageResPlacement);
}

function showForecasts(response)
{
    let resultPlacement = $('.js-result');
    let sourceForecastTpl = $('.js-source-forecast');

    for (let sourceItem of response) {
        let sourceItemTpl = '';

        if ('errors' in sourceItem) {
            sourceItemTpl = $('.js-error').clone();
            sourceItemTpl.find('.js-error-text').html(sourceItem.errors);
        } else {
            sourceItemTpl = sourceForecastTpl.clone();
            let morning = sourceItemTpl.find('.js-morning');
            let mdata = {};
            if ('morning' in sourceItem) {
                mdata = sourceItem.morning;
            }
            morning.append(fillForecast('Morning', mdata));

            let day = sourceItemTpl.find('.js-day');
            let ddata = {};
            if ('day' in sourceItem) {
                ddata = sourceItem.day;
            }
            day.append(fillForecast('Day', ddata));

            let evening = sourceItemTpl.find('.js-evening');
            let edata = {};
            if ('evening' in sourceItem) {
                edata = sourceItem.evening;
            }
            evening.append(fillForecast('Evening', edata));

            let night = sourceItemTpl.find('.js-night');
            let ndata = {};
            if ('night' in sourceItem) {
                ndata = sourceItem.night;
            }
            night.append(fillForecast('Night', ndata));
        }

        sourceItemTpl.find('.js-title').html(sourceItem.title);
        sourceItemTpl.removeClass('visually-hidden');
        sourceItemTpl.removeClass('js-source-forecast');
        resultPlacement.append(sourceItemTpl);
    }
}

function fillForecast(title, data) {
    let item = $('.js-daypart-forecast').clone();
    item.removeClass('js-daypart-forecast');

    item.find('.js-part-title strong').html(title);

    // return stub with title if data is empty
    if (!data) {
        return item;
    }

    let temp = item.find('.js-temp');
    if ('temp' in data) {
        temp.find('.js-value').html(data.temp);
    } else {
        temp.remove();
    }

    let cloud = item.find('.js-cloud');
    if ('cloud' in data) {
        cloud.find('.js-value').html(data.cloud);
    } else {
        cloud.remove();
    }

    let prec = item.find('.js-prec');
    if ('prec' in data) {
        prec.find('.js-value').html(data.prec);
    } else {
        prec.remove();
    }

    let wind = item.find('.js-wind');
    if ('wind' in data) {
        wind.find('.js-speed').html(data.wind.speed);
        wind.find('.js-dir').html(data.wind.dir);
    } else {
        wind.remove();
    }

    item.removeClass('visually-hidden');
    return item;
}

function addButtonSpinner(object) {
    $('.js-send').find('.js-spinner').addClass('spinner-border');
}

function removeButtonSpinner(object) {
    $('.js-send').find('.js-spinner').removeClass('spinner-border');
}

function clearResultPlacement() {
    let resultPlacement = $('.js-result');
    resultPlacement.empty();
    resultPlacement.removeClass('text-danger');
}
