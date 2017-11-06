import { Injectable } from '@angular/core';
import { Router, CanActivate, ActivatedRouteSnapshot, RouterStateSnapshot } from '@angular/router';
import { Location } from '@angular/common';

@Injectable()
export class AuthGuard implements CanActivate {

    constructor(private router: Router, private location: Location) { }

    canActivate(route: ActivatedRouteSnapshot, state: RouterStateSnapshot) {
        if (this.isAdminUrl()){
            if (localStorage.getItem('token')) {
                // logged in so return true
                return true;
            }

            // not logged in so redirect to login page with the return url
            this.router.navigate(['admin/login'], { queryParams: { returnUrl: state.url }});
            return false;
        } else {
            if (localStorage.getItem('tokenMerchant')) {
                // logged in so return true
                return true;
            }

            // not logged in so redirect to login page with the return url
            this.router.navigate(['/login'], { queryParams: { returnUrl: state.url }});
            return false;
        }
    }

    public isAdminUrl() {  
        let route = this.location.path();
        return (route.indexOf("/admin") > -1);
    }
}