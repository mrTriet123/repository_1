import { Component, ViewChild, OnInit } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { Globals } from '../../services/globals/globals.service'; // For file globals
import {ViewEncapsulation} from '@angular/core';
import { PostService } from '../../services/post/post.service';

@Component({
  moduleId: module.id,
  selector: 'resetpassword',
  templateUrl: 'resetpassword.component.html',
  styleUrls: [
      '../../../assets/global/vendor/login/app_merchant.css',
      'resetpassword.component.css',
  ],
  providers: [PostService]
  // encapsulation: ViewEncapsulation.None,
})
export class ResetPasswordComponent implements OnInit { 
    model: any = {};
    loading = false;
    returnUrl: string;
    message: string;
    
    constructor(
      private route: ActivatedRoute,
      private router: Router,
      private _postService: PostService,
      private globals : Globals
    ) { }

    ngOnInit() {
        // get return url from route parameters or default to '/'
        this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/home';
    }

    login() {
      // Check rePassword
      if(this.model.newpassword == this.model.renewpassword) {
        if(this.model.newpassword.length >= 8){ // Check length
          let token = this.route.snapshot.queryParams['token'];
          // this.message = "Demo OK ! Your token is : " + this.route.snapshot.queryParams['token'];
          this.loading = true;
          // DEMO ONLY
          // this.loading = false;
          // localStorage.setItem('isSetup', 'true');
          // this.router.navigate(['/step1']);
          // END DEMO
          this._postService.changePassword(this.model, token).subscribe(res => {
                this.loading = false;
                if (res.result == 1){
                  localStorage.setItem('isSetup', 'true');
                  this.router.navigate(['/step1']);
                } else {
                  this.message = res.messages;
                }
            },
            error => {
              this.loading = false;
              var json = JSON.parse(error._body);
              this.message = json.error.message;
              if (json.error.validateError){
                this.message += " " + json.error.validateError.password_new[0]
              }
            });
        } else {
          this.message = "Password must be more than eight characters !";
        }
      } else {
        this.message = "Not match! Please type again your password!";
      }
    }

}