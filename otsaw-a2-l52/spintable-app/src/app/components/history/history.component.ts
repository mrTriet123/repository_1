import { Component, ViewChild } from '@angular/core';
import { PostService } from '../../services/post/post.service';
import { FxDatepickerComponent } from '../library/datepicker.component';
import { FxModalComponent } from '../home/modal.component';
import { DatePipe } from '@angular/common';
import { Reservable } from '../../interfaces/Reservable';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
  moduleId: module.id,
  selector: 'history',
  templateUrl: 'history.component.html',
  providers: [PostService]
})
export class HistoryComponent  { 
    dateToday: Date = new Date();
    dateValue: string;
    merchant_id: number;
    transactions: Reservable[];
    bookings: Reservable[];
    @ViewChild('modal') modal: FxModalComponent;

    constructor(private _postService: PostService, private route: ActivatedRoute,
      private router: Router) {
        this.dateValue = new DatePipe('pt-PT').transform(this.dateToday, 'yyyy-MM-dd');
        this.merchant_id = 1;
        this.getPastTransactions();
        this.getPastBookings();
    }

    ngOnInit() {
        let action = localStorage.getItem('action');
        if (action == 'Save'){
            this.router.navigate(['/settings']);
        } else {
        }
    }
  
    getPastTransactions(){
        this._postService.history(this.merchant_id, 'transactions', this.dateValue).subscribe(res => {
            this.transactions = res.data;
        });
    }

    getPastBookings(){
        this._postService.history(this.merchant_id, 'bookings', this.dateValue).subscribe(res => {
            this.bookings = res.data;
        });
    }
    
    changeValue(event:any){
        this.dateValue = event;
        this.getPastTransactions();
        this.getPastBookings();
    }

    openOrderDetails(reservable_id: number){
        this._postService.orderDetails(this.merchant_id, reservable_id).subscribe(res => {
            this.modal.showModal(res.data);
        });
    }

}
