import {makeAutoObservable} from "mobx";
import AuthService from "../services/AuthService";
import axios from "axios";
import {API_URL} from "../http";
import WeatherService from "../services/WeatherService";

export default class Store {
  isAuth = false;
  weather = null;
  message = null;

  constructor() {
    makeAutoObservable(this);
  }

  setAuth(bool) {
    this.isAuth = bool;
  }

  setWeather(weather) {
    this.weather = weather;
  }

  setMessage(message) {
    this.message = message;
  }

  async login(username, password) {
    try {
      const response = await AuthService.login(username, password);

      localStorage.setItem('token', response.data.token);
      this.setAuth(true);
    } catch (e) {
      console.log(e.response?.data?.message);
    }
  }

  async logout() {
    localStorage.removeItem('token');
    this.setAuth(false);
  }

  async checkAuth() {
    try {
      const response = await axios.get(`${API_URL}/login/refresh`, {
        withCredentials: true
      });

      localStorage.setItem('token', response.data.token);
      this.setAuth(true);
    } catch (e) {
      console.log(e.response?.data?.message);
    }
  }

  async getWeather(city, average) {
    try {
      const response = average
        ? await WeatherService.fetchAverageWeatherForLastSevenDays(city)
        : await WeatherService.fetchWeather(city);

      this.setWeather(response.data.weather);
      this.setMessage(null);
    } catch (e) {
      if (e.response.status === 404) {
        this.setMessage(e.response.data.message);
      }

      console.log(e);
    }
  }
}
