import { Component } from '@angular/core';
import { Router } from '@angular/router';

@Component({
  moduleId: module.id,
  selector: 'setup',
  templateUrl: 'setup.component.html',
  // inputs:[`pageData`],
  styleUrls: ['setup.component.css']

})
export class SetupComponent  { 
  title:String;
  description:String;
  public pageData:Number;

  name : string;
  currentUser : any;

  constructor(private router: Router){
    this.currentUser = JSON.parse(localStorage.getItem('GLOBAL_USER'));
    this.name = localStorage.getItem('setup_name');
    if (this.name == null){
      this.name = this.currentUser.firstname + " " + this.currentUser.lastname;
      localStorage.setItem('setup_name', this.name);
    }
  }

  saveName(){
    localStorage.setItem('setup_name', this.name);
    this.router.navigate(['/step2']);
  }

}