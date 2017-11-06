import { Component, ViewChild } from '@angular/core';
import { Globals } from '../../services/globals/globals.service';
import { PostService } from '../../services/post/post.service';
import { SocketService } from '../../services/socket/socket.service';
import { FxModalComponent } from '../home/modal.component';
import { ParentNotification } from '../../interfaces/ParentNotification';
import { Notification } from '../../interfaces/Notification';
import { Paginator } from '../../interfaces/Paginator';
import { Location } from '@angular/common';


@Component({
  moduleId: module.id,
  selector: 'header',
  templateUrl: 'header.component.html',
  providers: [SocketService, PostService, FxModalComponent],
  styleUrls: ['header.component.css']
})
export class HeaderComponent  { 
  merchant_id: number;
  notif_list: Notification[];
  paginator: Paginator;
  nameAccount: string;
  currentUser : any;
  @ViewChild('modal') modal: FxModalComponent;

  constructor(private location: Location, private _socketService: SocketService, private globals: Globals, private _postService: PostService){
    this.merchant_id = 1;
    this.getNotifications();   
    if(localStorage.getItem('GLOBAL_USER')){
      this.currentUser = JSON.parse(localStorage.getItem('GLOBAL_USER'));
      this.nameAccount = this.currentUser.firstname + " " + this.currentUser.lastname;
    }
  }

  ngOnInit() {
    this._socketService.notificationChannel().subscribe(message => {
        let result: ParentNotification = message;
        this.notif_list = result.data;
        this.paginator = result.paginator;
    });
  }

  isLogin(){
    if (localStorage.getItem('tokenMerchant')) {
        // logged in so return true
        return true;
    } else {
        return false;
    }
  }

  public isUrl(url : string) {  
    let route = this.location.path();
    // console.log("isAdminPage :" + (route.indexOf("/admin") > -1)); // is ADMIN
    return (route.indexOf(url) > -1);
  }

  getNotifications() {
    this._postService.recentNotifications(this.merchant_id).subscribe(res => {
        this.notif_list = res.data;
        this.paginator = res.paginator;
    });
  }

  getOrderInformation(reservable_id:number, notification_id: number){
    this._postService.orderDetails(this.merchant_id, reservable_id).subscribe(res => {
        this.modal.showModal(res.data);
    });
    this._postService.markAsRead(this.merchant_id, notification_id).subscribe(res => {   
    });
  }
}