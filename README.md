## Weather Application

Запуск приложения php artisan serve

Приложение доступно по ссылке http://127.0.0.1:8000/

АПИ для получения данных о погоде:

## Данные о погоде в выбранном гороже
### Запрос
GET http://127.0.0.1:8000/api/weather/{code}

code - код города

```
[
    'spb', // Санкт-Петербург
    'msk' // Москва
]
```

### Ответ
```
[
  {
    "title": "7Timer!",
    "morning": {
      "temp": "16°C",
      "prec": "None",
      "cloud": "0% - 6%",
      "wind": {
        "speed": "Light",
        "dir": "N"
      }
    },
    "day": {
      "temp": "18.5°C",
      "prec": "None",
      "cloud": "0% - 6%",
      "wind": {
        "speed": "Light",
        "dir": "N, NE"
      }
    },
    "evening": {
      "temp": "27°C",
      "prec": "None",
      "cloud": "0% - 6%",
      "wind": {
        "speed": "Light",
        "dir": "NE, N"
      }
    },
    "night": {
      "temp": "23.5°C",
      "prec": "None",
      "cloud": "0% - 6%",
      "wind": {
        "speed": "Light",
        "dir": "NE"
      }
    }
  },
  {
    "title": "AccuWeather",
    "morning": [],
    "day": {
      "temp": "15.8°C - 28.3°C",
      "prec": "None",
      "cloud": "Mostly sunny"
    },
    "evening": [],
    "night": {
      "temp": "15.8°C - 28.3°C",
      "prec": "None",
      "cloud": "Intermittent clouds"
    }
  }
]

```

## Усредненные данные по всем источникам
### Запрос
GET http://127.0.0.1:8000/api/weather/{code}/average

code - код города

```
[
    'spb', // Санкт-Петербург
    'msk' // Москва
]
```

### Ответ
```
{
  "title": "Average",
  "temp": 21.5,
  "cloud": "Cloudy",
  "prec": "No",
  "wind": {
    "speed": "Light",
    "dir": "N, NE"
  }
}
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
