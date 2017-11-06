import { Component, ViewChild } from '@angular/core';
import { Subject } from 'rxjs/Subject';
import { PostService } from '../../services/post/post.service';
import { SocketService } from '../../services/socket/socket.service';
import { FxDatepickerComponent } from '../library/datepicker.component';
import { FxModalComponent } from '../home/modal.component';
import { DatePipe } from '@angular/common';
import { Reservable } from '../../interfaces/Reservable';
import { OrderDetail } from '../../interfaces/OrderDetail';
import { Order } from '../../interfaces/Order';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  moduleId: module.id,
  selector: 'home',
  templateUrl: 'home.component.html',
  providers: [SocketService, PostService]
})
export class HomeComponent { 
    dateToday: Date = new Date();
    dateValue: string;
    less_than_hour_orders: Reservable[];
    more_than_hour_orders: Reservable[];
    less_than_hour_bookings: Reservable[];
    more_than_hour_bookings: Reservable[];
    walkins: Reservable[];
    merchant_id: number;
    @ViewChild('modal') modal: FxModalComponent;
    
    constructor(private _socketService: SocketService, private _postService: PostService,private route: ActivatedRoute,
      private router: Router) {
        this.dateValue = new DatePipe('pt-PT').transform(this.dateToday, 'yyyy-MM-dd');
        this.merchant_id = 1;
        this.getOrders();
        this.getBookings();
        this.getWalkins();

        let isSetup = localStorage.getItem('isSetup');
        if (isSetup != null){
            this.router.navigate(['/step1']);
        } else {

        }
    }

    ngOnInit() {
        let action = localStorage.getItem('action');
        if (action == 'Save'){
            this.router.navigate(['/settings']);
        } else {
            this._socketService.orderChannel().subscribe(message => {
            let result: Order = message;
            this.less_than_hour_orders = result.less_than_one_hour;
            this.more_than_hour_orders = result.more_than_one_hour;
            });

            this._socketService.bookingChannel().subscribe(message => {
                let result: Order = message;
                this.less_than_hour_bookings = result.less_than_one_hour;
                this.more_than_hour_bookings = result.more_than_one_hour;
            });

            this._socketService.walkinChannel().subscribe(message => {
                let result: Reservable[] = message;
                this.walkins = result;
            });
        }
    }

    isLogin(){
        if (localStorage.getItem('tokenMerchant')) {
            // logged in so return true
            return true;
        }
        return false;
    }

    changeValue(event:any){
        this.dateValue = event;
        this.getOrders();
        this.getBookings();
        this.getWalkins();
    }

    getOrders(){
        this._postService.orders(this.merchant_id, this.dateValue).subscribe(res => {
            this.less_than_hour_orders = res.data.less_than_one_hour;
            this.more_than_hour_orders = res.data.more_than_one_hour;
        });
    }

    getBookings(){
        this._postService.bookings(this.merchant_id, this.dateValue).subscribe(res => {
            this.less_than_hour_bookings = res.data.less_than_one_hour;
            this.more_than_hour_bookings = res.data.more_than_one_hour;
        });
    }

    getWalkins(){
        this._postService.walkins(this.merchant_id, this.dateValue).subscribe(res => {
            this.walkins = res.data;
        });
    }

    openOrderDetails(reservable_id: number){
        console.log(reservable_id);
        this._postService.orderDetails(this.merchant_id, reservable_id).subscribe(res => {
            this.modal.showModal(res.data);
        });
    }
}