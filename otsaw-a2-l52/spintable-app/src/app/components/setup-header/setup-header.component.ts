import { Component } from '@angular/core';

@Component({
  moduleId: module.id,
  selector: 'setUpHeader',
  templateUrl: 'setup-header.component.html',
  
})
export class SetupHeaderComponent  { 
    stepTitle:String;
    constructor(){
        this.stepTitle = "First: Set your account";
    }
}

