import { Component, Input, ViewChild, Injectable } from '@angular/core';
import 'rxjs/add/operator/map';

@Component({
    moduleId: module.id,
    selector: 'fx-modal',
    providers: [],
    templateUrl: 'modal.component.html',  
    styles: []  
})

@Injectable()
export class FxModalComponent {
    @ViewChild('staticModal') modal: any;
    order_details:any = [];
    dishes_per_page:number = 4;

    constructor() { }

    showModal(order: any){
        this.order_details = order;
        this.modal.show();
    }
    
    range(value : number){

        value = Math.ceil(value / this.dishes_per_page);
        var a:number[] = [];

        for(let i = 0; i < value; ++i) { 
            a.push(i+1) 
        }
        return a; 
    }

    chunk(dishes : any[], x: number){

        var result:any[] = [];

        for(var i =0; i < dishes.length; i+= this.dishes_per_page){
            var smallarray = dishes.slice(i, i+this.dishes_per_page);
            result.push(smallarray);
        }

        if (result) {
            return result[x];
        }

        return result;
    }

}

