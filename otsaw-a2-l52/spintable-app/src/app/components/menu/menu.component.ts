import { Component, ViewChild } from '@angular/core';
import { PostService } from '../../services/post/post.service';
import { ModalDirective } from 'ng2-bootstrap';
import { AlertModule } from 'ng2-bootstrap';
import { Router, ActivatedRoute } from '@angular/router';
import { Globals } from '../../services/globals/globals.service';

// byPassSecurity
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  moduleId: module.id,
  selector: 'merchant-menu',
  templateUrl: 'menu.component.html',
  providers: [PostService],
  styleUrls: [
      'menu.component.css',
  ],
})
export class MenuComponent  { 
  @ViewChild('auModal') public auModal:ModalDirective;
  model: any = {};
  token : string;
  nameAction : string;
  // Category
  nameCategory : string;
  idCategory : number;
  msg : string;
  // merchants : Merchant[];
  backupIndex : number;
  // merchant : Merchant;
  alerts: any = [];
  // dishes
  dishes : any[] = [];
  // drinks
  drinks : any[] = [];
  // addons
  addons : any[] = [];
  // item
  item : any = {};

  constructor(private _postService: PostService, private route: ActivatedRoute,
      private router: Router, public sanitizer: DomSanitizer, public global: Globals,) {
    
  }

  ngOnInit() {
    let action = localStorage.getItem('action');
    if (action == 'Save'){
        this.router.navigate(['/settings']);
    } else {
    }

    // Get all list category
    this._postService.getAllListCategory(localStorage.getItem('tokenMerchant'))
      .subscribe(
          data => {
            if (data.result==0){

            } else {
              this.dishes = data.data.data_dish;
              this.drinks = data.data.data_drink;
              this.addons = data.data.$addon;
            }
          },
          error => {

          }
      );

  }
  
  showDelItem(item : any){
    this.item = item;
    // console.log(item);
    this.nameAction = "Delete";
    this.nameCategory = item.name;
    this.idCategory = item.id;
    this.auModal.show();
  }

  addCategoryDishes(){
    this.router.navigate(['/create-category'], { queryParams: { type: "Dishes" } });
  }

  addCategoryDrinks(){
    this.router.navigate(['/create-category'], { queryParams: { type: "Drinks" } });
  }

  addCategoryAddon(){
    this.router.navigate(['/create-category'], { queryParams: { type: "Addon" } });
  }

  deleteCategory(){
    if (this.item.type){
      this._postService.deleteAddon(this.idCategory.toString(), localStorage.getItem('tokenMerchant'))
      .subscribe(
          data => {
            // Remove item object
            this.addons = this.addons.filter(item => item !== this.item);
          },
          error => {
            
          }
      );
    } else {
      this._postService.deleteCategory(this.idCategory.toString(), localStorage.getItem('tokenMerchant'))
      .subscribe(
          data => {
            // Remove item object
            this.dishes = this.dishes.filter(item => item !== this.item);
            this.drinks = this.drinks.filter(item => item !== this.item);
          },
          error => {
            
          }
      );
    }
    this.auModal.hide();
  }
}
