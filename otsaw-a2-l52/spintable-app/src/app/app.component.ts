import { Component, ViewContainerRef, OnInit } from '@angular/core';
import { Location } from '@angular/common';
// import { Overlay } from 'angular2-modal';
// import { Modal } from 'angular2-modal/plugins/bootstrap';

@Component({
  moduleId: module.id,
  selector: 'spintable-app',
  templateUrl: './app.component.html', 
})
export class AppComponent implements OnInit {


  public isAdminUrl() {  
    let route = this.location.path();
    // console.log("isAdminPage :" + (route.indexOf("/admin") > -1)); // is ADMIN
    return (route.indexOf("/admin") > -1);
  }
  
  ngOnInit() {
  }

  constructor(private location: Location) {
  }

}
