import { Component, ViewChild, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '../../services/authentication/authentication.service';
import { Globals } from '../../services/globals/globals.service'; // For file globals
import {ViewEncapsulation} from '@angular/core';

@Component({
  moduleId: module.id,
  selector: 'login',
  templateUrl: 'login.component.html',
  styleUrls: [
      '../../../assets/global/vendor/login/app.css',
      'login.component.css',
  ],
  // encapsulation: ViewEncapsulation.None,
})
export class LoginComponent implements OnInit { 
    model: any = {};
    loading = false;
    returnUrl: string;
    message: string;
    
    constructor(
      private route: ActivatedRoute,
      private router: Router,
      private authenticationService: AuthenticationService,
      private globals : Globals
    ) { }

    ngOnInit() {
        // reset login status
        let token = localStorage.getItem('token');
        if (token != null){
            this.authenticationService.logout().subscribe(
                data => {
                    // this.router.navigate([this.returnUrl]);
                },
                error => {
                    // this.alertService.error(error);
                });
        } else {
            this.authenticationService.logout();
        }

        // get return url from route parameters or default to '/'
        this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/admin';
    }

    login() {
        this.loading = true;
        this.authenticationService.login(this.model.email, this.model.password)
            .subscribe(
                data => {
                    this.router.navigate([this.returnUrl]);
                },
                error => {
                    // this.alertService.error(error);
                    this.loading = false;
                    this.message = "These credentials do not match our records.";
                });
    }

}