import { Component, OnInit, Input } from '@angular/core';
import { PostService } from '../../services/post/post.service';
import { SocketService } from '../../services/socket/socket.service';

@Component({
  moduleId: module.id,
  selector: 'admin',
  templateUrl: 'admin.component.html',
  providers: [SocketService, PostService]
})
export class AdminComponent implements OnInit { 
  isLogin : boolean;

  public checkLogin(){
    this.isLogin = true;
    if (localStorage.getItem('token')) { // Have token
      // logged in so isLogin = false;
      this.isLogin = false;
    }
    console.log('Check Login');
  }

  constructor() {
    
  }

  ngOnInit() {

  }

}