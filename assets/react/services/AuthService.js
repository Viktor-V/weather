import api from '../http';

export default class AuthService {
  static async login(username, password) {
    return api.post('/login', {username, password});
  }
}
