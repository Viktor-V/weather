import api from '../http';

export default class WeatherService {
  static fetchWeather(city) {
    return api.get('/weather/current', { params: { city: city } });
  }

  static fetchAverageWeatherForLastSevenDays(city) {
    return api.get('/weather/last-seven-days', { params: { city: city } });
  }
}
