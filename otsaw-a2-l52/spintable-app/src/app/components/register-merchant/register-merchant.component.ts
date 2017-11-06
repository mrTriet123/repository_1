import { Component, ViewChild } from '@angular/core';
import { Subject } from 'rxjs/Subject';
import { PostService } from '../../services/post/post.service';
import { Router, ActivatedRoute } from '@angular/router';
import { RestaurantType } from '../../interfaces/RestaurantType';

@Component({
  moduleId: module.id,
  selector: 'register-merchant',
  templateUrl: 'register-merchant.component.html',
  styleUrls: ['register-merchant.component.css'],
  providers: [PostService]
})
export class RegisterMerchantComponent { 
  model: any = {};
  isThank : boolean = false;
  loading = false;
  returnUrl: string;
  message: string;
  restaurantTypes: RestaurantType[];

  constructor(private _postService: PostService, private router: Router, private route: ActivatedRoute,) {
  }

  ngOnInit() {
    this.returnUrl = this.route.snapshot.queryParams['returnUrl'] || '/register';
    this._postService.getRestaurantType()
        .subscribe(
            data => {
                if (data.result == 1){
                  this.restaurantTypes = data.data;
                  this.model.restauranttype = 1;
                } else {
                  this.message = "Error! Please report to admin. Thank you!";
                }
            },
            error => {
              this.loading = false;
              this.message = "Error! Please report to admin. Thank you!";
            });
  }

  register(){
    this.loading = true;
    // console.log(this.model.restauranttype);
    this._postService.registerMerchant(this.model)
        .subscribe(
            data => {
                if (data.result == 1){
                  this.loading = false;
                  this.isThank = true;
                  this.router.navigate([this.returnUrl]);
                } else {
                  this.loading = false;
                  this.message = data.messages;
                }
            },
            error => {
              this.loading = false;
              this.message = "Error! Please report to admin. Thank you!";
            });
  }

}