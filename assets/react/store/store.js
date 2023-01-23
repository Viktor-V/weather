import {makeAutoObservable} from "mobx";
import AuthService from "../services/AuthService";
import axios from "axios";
import {API_URL} from "../http";
import WeatherService from "../services/WeatherService";

export default class Store {
  isAuth = !!localStorage.getItem('token');
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
      localStorage.setItem('refreshToken', response.data.refreshToken);
      this.setAuth(true);
    } catch (e) {
      console.log(e.response?.data?.message);
    }
  }

  async logout() {
    localStorage.removeItem('token');
    localStorage.removeItem('refreshToken');
    this.setAuth(false);
  }

  async checkAuth() {
    try {
      const response = await axios.post(`${API_URL}/login/refresh`, {
        refreshToken: localStorage.getItem('refreshToken')
      });

      localStorage.setItem('token', response.data.token);
      localStorage.setItem('refreshToken', response.data.refreshToken);
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

      if (response.data.weather !== null) {
        this.setWeather(response.data.weather);
        this.setMessage(null);
      } else {
        this.setWeather(null);
        this.setMessage('Not found!');
      }
    } catch (e) {
      console.log(e);
    }
  }
}
