import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';
import { Observable } from 'rxjs/Observable';
import { Observer } from 'rxjs/Observer';
import * as io from 'socket.io-client';
import { Order } from '../../interfaces/Order';
import { Reservable } from '../../interfaces/Reservable';
import { ParentNotification } from '../../interfaces/ParentNotification';

@Injectable()
export class SocketService{
    socket:any = null;

    constructor(){
        //this.socket = io('http://spintable.dev:3030'); // backend url
        this.socket = io('http://localhost:3030'); // backend url
    }

    orderChannel() {
        let observable = new Observable<Order>((observer: Observer<Order>) => {
            this.socket.on('order-list-channel:App\\Events\\UpdateOrderList', (data:any) => {
                observer.next(data.data);
            });
            return () => {
                this.socket.disconnect();
            };  
        })     
        return observable;
    }

    bookingChannel() {
        let observable = new Observable<Order>((observer: Observer<Order>) => {
            this.socket.on('booking-list-channel:App\\Events\\UpdateBookingList', (data:any) => {
                observer.next(data.data);
            });
            return () => {
                this.socket.disconnect();
            };  
        })     
        return observable;
    }

    walkinChannel() {
        let observable = new Observable<Reservable[]>((observer: Observer<Reservable[]>) => {
            this.socket.on('walkin-list-channel:App\\Events\\UpdateWalkinList', (data:any) => {
                observer.next(data.data);
            });
            return () => {
                this.socket.disconnect();
            };  
        })     
        return observable;
    }

    notificationChannel() {
        let observable = new Observable<ParentNotification>((observer: Observer<ParentNotification>) => {
            this.socket.on('notification-channel:App\\Events\\RefreshNotifications', (data:any) => {
                observer.next(data.data);
            });
            return () => {
                this.socket.disconnect();
            };  
        })     
        return observable;
    }
}
