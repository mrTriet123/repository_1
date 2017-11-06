import { Component, ViewChild } from '@angular/core';
import { PostService } from '../../services/post/post.service';
import { Merchant } from '../../interfaces/Merchant';
import { ModalDirective } from 'ng2-bootstrap';
import { AlertModule } from 'ng2-bootstrap';
import { TooltipModule } from 'ng2-bootstrap';
import { PaginationModule } from 'ng2-bootstrap';

@Component({
  moduleId: module.id,
  selector: 'merchant',
  templateUrl: 'merchant.component.html',
  providers: [PostService]
})
export class MerchantComponent  { 
    @ViewChild('lgModal') public lgModal:ModalDirective;
    @ViewChild('auModal') public auModal:ModalDirective;
    model: any = {};
    token : string;
    nameAction : string;
    msg : string;
    merchants : Merchant[];
    backupIndex : number;
    merchant : Merchant;
    alerts: any = [];
    // Paging
    // public maxSize:number = 5;
    // public bigTotalItems:number = 100;
    // public bigCurrentPage:number = 1;
    // public numPages:number = 5;
   
    constructor(private _postService: PostService) {
        this.getAllMerchant();
    }

    // Paging
    // public pageChanged(event:any):void {
    //   console.log('Page changed to: ' + event.page);
    //   console.log('Number items per page: ' + event.itemsPerPage);
    // }

    // Merchant
  
    getAllMerchant(){
        this.token = localStorage.getItem('token').replace(/"/g,'');
        this._postService.listAllMerchant(this.token).subscribe(res => {
            if (res.result == 1){
              this.merchants = res.data;
              // console.log(this.merchants);
              // console.log(this.merchants.length);
              // this.bigTotalItems = this.merchants.length;
            } else {
              console.log(res.message);
            }
        });
    }

    showMerchant(i : number){
      this.nameAction = "Infomation";
      // this.merchant = this.merchants[i];
      // let obj = this.merchants[i];
      this.model = this.merchants[i];
      this.lgModal.show();
    }

    showAddMerchant(){
      this.nameAction = "Add";
      this.model = new Merchant();
      this.auModal.show();
    }

    showEditMerchant(i : number){
      this.nameAction = "Edit";
      // this.model = this.merchants[i]; // Copy
      this.model = Object.assign({}, this.merchants[i]); // Deep copy
      this.backupIndex = i;
      this.auModal.show();
    }

    showDelMerchant(i : number){
      this.nameAction = "Delete";
      this.model = this.merchants[i];
      this.auModal.show();
    }

    addMerchant(){
      let obj = this.model;
      // this.merchants.push(obj);
      this._postService.addMerchant(this.token, obj).subscribe(res => {
          if (res.result > 0){
            this.showAlert("Add Merchant sucsessful!", 'success');
            this.merchants.push(obj);
            obj.id = res.messages.user_id;
            this.auModal.hide();
            // this.merchants.push(this.model);
          } else {
            this.showAlert(res.messages, 'danger');
          }
      });
      // this.auModal.hide();
    }

    editMerchant(){
      let obj = this.model;
      this._postService.editMerchant(this.token, obj).subscribe(res => {
          if (res.result == 1){
            this.showAlert("Update Merchant sucsessful!", 'success');
            this.merchants[this.backupIndex] = obj;
            this.auModal.hide();
          } else {
            this.showAlert(res.messages, 'danger');
          }
      });
    }

    deleteMerchant(){
      let obj = this.model;
      this._postService.deleteMerchant(this.token, obj).subscribe(res => {
          if (res.result > 0){
            this.showAlert(res.messages, 'success');
          } else {
            this.showAlert(res.messages, 'danger');
          }
      });
      // Remove item object
      this.merchants = this.merchants.filter(item => item !== this.model);
      this.auModal.hide();
    }

    generatedPassword(){
      let obj = this.model;
      this._postService.generatedPassword(this.token, obj).subscribe(res => {
          if (res.result > 0){
            this.showAlert("You successfully generated password. The new password is: "+res.messages.password, 'success');
            obj.password = res.messages.password;
          } else {
            this.showAlert(res.messages, 'danger');
          }
      });
      this.lgModal.hide();
    }

    cancelRequest(){
      console.log("No Edit");
    }

    // ALERT
    showAlert(msg : string, type: string): void {
      this.alerts.push({
        type: type,
        msg: msg,
        timeout: 3000
      });
    }

}
