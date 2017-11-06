import { Component } from '@angular/core';
import { PostService } from '../../../services/post/post.service';
import { Router } from '@angular/router';
import { Globals } from '../../../services/globals/globals.service'; // For file globals

@Component({
  moduleId: module.id,
  selector: 'SetupStep5',
  templateUrl: 'setup-step5.component.html',
  styleUrls: ['setup-step5.component.css'],
  providers: [PostService],
})
export class SetupStep5Component  { 
  model: any = [];
  emails: any = [];
  loading : boolean = false;
  image : File;

  constructor(private _postService: PostService, 
    private globals : Globals, 
    private router: Router){
  }

  submit(){
    this.loading = true;
    if (localStorage.getItem('setup_name')){
      this.model.name = localStorage.getItem('setup_name');
    }
    if (localStorage.getItem('setup_code')){
      this.model.token = localStorage.getItem('setup_code');
    }
    if (localStorage.getItem('setup_describe')){
      this.model.describe = localStorage.getItem('setup_describe');
    }
    if (localStorage.getItem('setup_emails')){
      this.emails = JSON.parse(localStorage.getItem('setup_emails'));
    }
    this.model.emails = '[{"email":"'+this.emails[0]+'"},{"email":"'+this.emails[1]+'"},{"email":"'+this.emails[2]+'"}]';
    // if (localStorage.getItem('setup_images')){
    //   this.model.image = localStorage.getItem('setup_images');
    // }
    // console.log(this.globals.getFile());
    this.model.image = this.globals.getFile();
    // console.log(this.model);

    this._postService.saveAccountSetup(this.model, localStorage.getItem('tokenMerchant'))
    .subscribe(data => {
      this.loading = false;
      if (data.result == 1){
        localStorage.removeItem("isSetup");
        localStorage.removeItem("setup_code");
        localStorage.removeItem("setup_describe");
        localStorage.removeItem("setup_emails");
        localStorage.removeItem("setup_name");
        localStorage.removeItem("setup_images");
        localStorage.setItem("action", 'Save');
        this.router.navigate(['settings']);
      } else {
        console.log("Error From Server");
      }
    });
  }
}