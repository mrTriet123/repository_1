import { Injectable } from '@angular/core';
import { Http, Headers, Response, URLSearchParams, RequestOptions } from '@angular/http';
import { Observable } from 'rxjs/Observable';
import 'rxjs/add/operator/map'

@Injectable()
export class AuthenticationService {
  url = 'http://localhost:8000/api/v1/';
  constructor(private http: Http) { }

  login(email: string, password: string) {
      let data = new URLSearchParams();
      data.append('email', email);
      data.append('password', password);

      return this.http.post(this.url + 'admin-side/login', data)
        .map((response: Response) => {
          // login successful if there's a jwt token in the response
          let data = response.json();
          if (data.result == 0){
            // console.log(data.messages);
            throw "Error";
          } else {
            let user = data.data;
            if (user && user.token) {
              // store user details and jwt token in local storage to keep user logged in between page refreshes
              localStorage.setItem('token', JSON.stringify(user.token));
              // console.log(user.token);
            }
          }       
        });
  }

  logout() {
    // remove user from local storage to log user out
    let token = localStorage.getItem('token');
    localStorage.removeItem('token');
    localStorage.removeItem('GLOBAL_USER');
    if (token != null){
      token = token.replace(/"/g,'');
      return this.http.get(this.url + 'admin-side/logout?token='+token)
        .map((response: Response) => {
          // login successful if there's a jwt token in the response
          let data = response.json();
          if (data.result == 0){
            // console.log("Logout Error");
            // throw "Error"; 
          } else {
            // console.log("Logout Success");
          }       
        });
    }
  }

  private jwt() { 
    let headers = new Headers({ 'Authorization': 'Bearer ' + "123" });
    headers.append('Content-Type', 'application/x-www-form-urlencoded');
    headers.append('token', 'abc');      
    return new RequestOptions({ headers: headers });   
  }  


  loginMerchant(email: string, password: string) {
      let data = new URLSearchParams();
      data.append('email', email);
      data.append('password', password);

      // return this.http.post(this.url + 'admin-side/login', data)
      return this.http.post(this.url + 'merchant-login', data)
        .map(res => res.json());
  }

  logoutMerchant() {
    // remove user from local storage to log user out
    let token = localStorage.getItem('tokenMerchant');
    localStorage.removeItem('tokenMerchant');
    localStorage.removeItem('GLOBAL_USER');
    if (token != null){
      token = token.replace(/"/g,'');
      // let headers = new Headers();
      // headers.append("Access-Control-Allow-Origin", "*");
      // headers.append('Content-Type', 'application/x-www-form-urlencoded');
      // headers.append('token', 'abc');
      // headers.append("Access-Control-Allow-Credentials", "true"); 
      // headers.append('Access-Control-Allow-Headers', 'true');
      // headers.append('token', token);
      // let options = new RequestOptions({ headers: headers, method: 'GET' });
      // let options = new RequestOptions({ headers: headers });
      // let options = { headers };
      return this.http.get(this.url + 'admin-side/logout?token='+token)
        .map((response: Response) => {
          // login successful if there's a jwt token in the response
          let data = response.json();
          if (data.result == 0){
            // console.log("Logout Error");
            // throw "Error"; 
          } else {
            // console.log("Logout Success");
          }       
        });
    }
  }
}