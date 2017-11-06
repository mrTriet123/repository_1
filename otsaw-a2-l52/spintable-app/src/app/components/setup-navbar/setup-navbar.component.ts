import { Component } from '@angular/core';
import { Globals } from '../../services/globals/globals.service';
import { Location } from '@angular/common';

@Component({
  moduleId: module.id,
  selector: 'setUpNavBar',
  templateUrl: 'setup-navbar.component.html',
  styleUrls: [
      'setup-navbar.component.css',
  ],
})
export class SetupNavBarComponent  { 

  localG:Globals;
  // pageData:Number;
  constructor(private globals:Globals, private location: Location){
    this.localG= globals;
    // this.pageData = 1;
  }

  // changePage(pageID:Number){
  //   this.pageData = pageID;
  // }

  public isEnable(url : string) {  
    let route = this.location.path();
    return (route.indexOf(url) > -1);
  }
}

