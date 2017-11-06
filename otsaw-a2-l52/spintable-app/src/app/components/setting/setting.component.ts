import { Component } from '@angular/core';
import { Table } from '../../interfaces/Table';
import { SpecialTime } from '../../interfaces/SpecialTime';
import { MerchantSetting } from '../../interfaces/MerchantSetting';
import { PostService } from '../../services/post/post.service';
import {ViewEncapsulation} from '@angular/core';
import { SelectComponent } from 'ng2-select';
import { AlertModule } from 'ng2-bootstrap';

@Component({
  moduleId: module.id,
  selector: 'setting',
  templateUrl: 'setting.component.html',
  styleUrls: [
    'setting.component.css',
  ],
  providers: [PostService],
  encapsulation: ViewEncapsulation.None,
})
export class SettingComponent  { 
    public timeStart: Date;
    public timeStop: Date;    
    // public hourStart: Date = new Date();
    // public hourStop: Date = new Date();
    model : any = {};
    tables : any[] = [];
    show : any[] = [];
    table: Table;
    setting: any = {};
    specialTimes : SpecialTime[] = [];
    // Check first time setting
    action : string = 'Edit';
    // List Dishes
    dishes : any[];
    dish : any[];
    alerts: any = [];

    // Test Multi select
    // private filterSelection: string[];
    // public filterItems: string[] = ["alpha","beta","gamma"];
    // public setFilterSelection(value: any): void {
    //     this.filterSelection = value;
    // }

    // ng-select OK
    public items:Array<string> = ['1', '2', '3', '4','5','6','7','8','9','10'];

    public selected(value:any):void {
      // console.log('Selected value is: ', value);
    }
   
    public refreshValue(value:any):void {
      this.setting.servingtime = value.id;
      // console.log("Da luu" + value.id);
    }

    constructor(private _postService: PostService) {

      // Get list dishes
      // this._postService.getListDish(localStorage.getItem('tokenMerchant'))
      //   .subscribe(
      //       data => {
      //         console.log(data);
      //         if (data.result == 1){
      //           this.dishes = data.data;

      //         } else {
      //           console.log("Error");
      //         }
      //       },
      //       error => {

      //       }
      //   );
      this.setting.cbNotification = 1;

      this._postService.getDataSetting(localStorage.getItem('tokenMerchant'))
        .subscribe(
            data => {
                if (data.result == 1){
                  if ((data.data.data_order == null) 
                  && (data.data.special_offers == null) 
                  && (data.data.dishes == null) 
                  && (data.data.table == "[]")){ // No data
                    // Add default data time
                    this.timeStart = new Date();
                    this.timeStop = new Date();
                    // Add new 1 table
                    let obj = new Table();
                    this.tables.push(obj);      
                    // Add new 1 offer
                    let specialTime = new SpecialTime();
                    specialTime.hourStart = new Date();
                    specialTime.hourStop = new Date();
                    this.specialTimes.push(specialTime);
                    this.setting.offername = "";
                    this.setting.servingtime = 0;
                    // Check menu
                    localStorage.setItem('action', 'Save')
                    // Check action
                    this.action = 'Save';
                    } else { // Have data
                      let hour = data.data.data_order.start_time.split(":");
                      this.timeStart = new Date(0, 0, 0, hour[0], hour[1], 0, 0);
                      hour = data.data.data_order.end_time.split(":");
                      this.timeStop = new Date(0, 0, 0, hour[0], hour[1], 0, 0);
                      this.setting.servingtime = data.data.data_order.hour_slot_per_table; // :3
                      this.setting.offername = data.data.special_offers?data.data.special_offers.offer_name:'0';
                      this.setting.discount = data.data.special_offers?data.data.special_offers.total_discount:'0';
                      this.setting.cbRepeat = data.data.special_offers?data.data.special_offers.repeat:'0';

                      // Add one by one table
                      for (let table of JSON.parse(data.data.table)) {
                          // console.log(table);
                          let obj = new Table();
                          obj.table_no = table.table_no;
                          obj.capacity = table.capacity;
                          obj.is_reserved = table.is_reserved;
                          this.tables.push(obj);
                      }

                      // Add new 1 offer (???)
                      let specialTime = new SpecialTime();
                      specialTime.hourStart = new Date();
                      specialTime.hourStop = new Date();
                      this.specialTimes.push(specialTime);

                    }
                } else {

                }
            },
            error => {

            }
        );

    }

    addTable(){
      let obj = new Table();
      this.tables.push(obj);
    }

    removeTable(i : number){
      if (this.tables.length != 1){
        let obj = this.tables[i];
        this.tables = this.tables.filter(item => item !== obj);
      }
    }

    addDay(){
      let obj = new SpecialTime();
      this.specialTimes.push(obj);
    }

    removeDay(i : number){
      if (this.specialTimes.length != 1){
        let specialTime = this.specialTimes[i];
        this.specialTimes = this.specialTimes.filter(item => item !== specialTime);
      }
    }

    saveData(){ // Check localStorage remove localStorage(action)
      // console.log(this.tables);
      // console.log(this.setting);
      // console.log(this.specialTimes);
      // console.log("start_time: " + this.toStringTime(this.timeStart) );
      // console.log("end_time: " + this.toStringTime(this.timeStop) );

      // Collect Data
      this.model.start_time = this.toStringTime(this.timeStart);
      this.model.hour_slot_per_table = this.setting.servingtime;
      this.model.end_time = this.toStringTime(this.timeStop);

      let merchant_table: any = [];
      for (let table of this.tables) {
        if (table.is_reserved == null || table.is_reserved == false){
          table.is_reserved = 0;
        } else {
          table.is_reserved = 1;
        }
        merchant_table.push(table);
      }
      this.tables = merchant_table;
      this.model.merchant_table = '{"records" : ' + JSON.stringify(this.tables) + '}';
      this.model.repeat = this.setting.cbRepeat?"on":"off";
      this.model.offer_name = this.setting.offername?this.setting.offername:'0';
      this.model.from = this.toStringTime(this.specialTimes[0].hourStart);
      this.model.to = this.toStringTime(this.specialTimes[0].hourStop);
      this.model.discount_type = "percentage";
      this.model.total_discount = this.setting.discount?this.setting.discount:'0';
      this.model.dish_id = '{"records" : [{ "id": "4"},{ "id": "5"},{ "id": "6"}]}';

      if (this.action == "Save"){
        this._postService.saveDataSetting(this.model ,localStorage.getItem('tokenMerchant'))
        .subscribe(
            data => {
                if (data.result == 1){
                 this.showAlert("Save " + data.messages, 'success');
                 localStorage.removeItem('action');
                 localStorage.removeItem('setup_images');
                } else {
                  this.showAlert("Save " + data.messages, 'danger');
                }
            },
            error => {

            }
        );
      } else {
        this._postService.editDataSetting(this.model ,localStorage.getItem('tokenMerchant'))
        .subscribe(
            data => {
                if (data.result == 1){
                 this.showAlert("Edit " + data.messages, 'success');
                } else {
                  this.showAlert("Edit " + data.messages, 'danger');
                }
            },
            error => {

            }
        );
      }
      
    }

    toStringTime(date : Date){
      return date.getHours() + ":" + date.getMinutes();
    }

    // ALERT
    showAlert(msg : string, type: string): void {
      this.alerts.push({
        type: type,
        msg: msg,
        timeout: 5000
      });
    }

}
