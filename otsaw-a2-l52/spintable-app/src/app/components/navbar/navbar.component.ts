import { Component } from '@angular/core';

@Component({
  moduleId: module.id,
  selector: 'navBar',
  templateUrl: 'navbar.component.html',
  
})
export class NavBarComponent  {
  restaurant: string = '';
  isLogin(){
    if (localStorage.getItem('tokenMerchant')) {
        let cookie_obj = JSON.parse(localStorage.getItem('GLOBAL_USER'));
        this.restaurant = cookie_obj.restaurant ? cookie_obj.restaurant.name : '';
        // logged in so return true
        return true;
    } else {
        return false;
    }
  }
}

