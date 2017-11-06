import { Component, OnInit } from '@angular/core';
import {ViewEncapsulation} from '@angular/core';
import { Location } from '@angular/common';

@Component({
  moduleId: module.id,
  selector: 'app-dashboard',
  templateUrl: './public-layout.component.html',
  // styleUrls: [
  //     '../../../assets/custom/custom.css',
  //     '../../../assets/custom/theme.css',
  //     '../../../assets/custom/layout.css',
  //     '../../../assets/global/css/plugins.min.css',
  //     '../../../assets/global/css/components.min.css',
  // ],
  // encapsulation: ViewEncapsulation.None,
})
export class PublicLayoutComponent implements OnInit {
  isSetup : boolean = true;
  ngOnInit(): void {}
  constructor(private location: Location) {
    if (localStorage.getItem('isSetup')) {
      this.isSetup = true;
      // localStorage.removeItem('isSetup'); // Will remove after submit all
    } else {
      this.isSetup = false;
    }
  }

  public isUrl(url : string) {  
    let route = this.location.path();
    // console.log("isAdminPage :" + (route.indexOf("/admin") > -1)); // is ADMIN
    return (route.indexOf(url) > -1);
  }
}
