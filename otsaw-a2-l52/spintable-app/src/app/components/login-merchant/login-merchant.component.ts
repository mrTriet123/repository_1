import { Component, ViewChild, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { AuthenticationService } from '../../services/authentication/authentication.service';
import { PostService } from '../../services/post/post.service';
import { Globals } from '../../services/globals/globals.service'; // For file globals
import {ViewEncapsulation} from '@angular/core';

@Component({
  moduleId: module.id,
  selector: 'login-merchant',
  templateUrl: 'login-merchant.component.html',
  styleUrls: [
      '../../../assets/global/vendor/login/app_merchant.css',
      'login-merchant.component.css',
  ],
  providers: [PostService],
  // encapsulation: ViewEncapsulation.None,
})
export class LoginMerchantComponent implements OnInit { 
    model: any = {};
    loading = false;
    returnUrl: string;
    message: string;
    
    constructor(
      private route: ActivatedRoute,
      private router: Router,
      private authenticationService: AuthenticationService,
      private globals : Globals,
      private _postService: PostService
    ) { }

    ngOnInit() {
        // reset login status
        let token = localStorage.getItem('tokenMerchant');
        if (token != null){
            this.authenticationService.logoutMerchant().subscribe(
                data => {
                    // this.router.navigate([this.returnUrl]);
                },
                error => {
                    // this.alertService.error(error);
                });
        } else {
            this.authenticationService.logoutMerchant();
        }

        // get return url from route parameters or default to '/'
        this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/home';
    }

    login() {
        this.loading = true;
        this.authenticationService.loginMerchant(this.model.email, this.model.password)
            .subscribe(data => {
                  if (data.result != 0){
                    this.loading = false;
                    let user = data.data;
                    if (user && user.token) {
                      localStorage.setItem('tokenMerchant', JSON.stringify(user.token).replace(/"/g,''));
                      localStorage.setItem('GLOBAL_USER', JSON.stringify(user));
                      // localStorage.setItem('name', (JSON.stringify(user.firstname) + " " + JSON.stringify(user.lastname)).replace(/"/g,''));
                      // localStorage.setItem('isSetup', 'true');
                    }
                    
                    this.loading = false;

                    switch (data.result) {
                      case 1:
                        // go to set new password page
                        localStorage.removeItem('isSetup');
                        this.router.navigate(['/reset-password'], { queryParams: { token: user.token } });
                        break;
                      case 2:
                        // go to setup page
                        localStorage.setItem('isSetup', 'true');
                        this.router.navigate(['/step1']);
                        break;
                      case 3:
                        // go to order setting page
                        localStorage.removeItem('isSetup');
                        this.router.navigate(['/settings']);
                        // localStorage.setItem('action', 'Save');
                        break;
                      case 4:
                        // go to home page
                        localStorage.removeItem('isSetup');
                        this.router.navigate(['/home']);
                        break;
                      default:
                        this.message = "Error! Please report to admin. Thank you!";
                    }
                  } else {
                    this.loading = false;
                    this.message = "These credentials do not match our records.";
                  }
                },
                error => {
                    // this.alertService.error(error);
                    this.loading = false;
                    this.message = "These credentials do not match our records.";
                });
    }

}