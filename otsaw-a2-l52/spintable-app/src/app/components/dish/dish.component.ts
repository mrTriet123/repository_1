import { Component, OnInit, ViewChild, ChangeDetectorRef} from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { PostService } from '../../services/post/post.service';
import { ModalDirective, AlertModule } from 'ng2-bootstrap';
import { Globals } from '../../services/globals/globals.service';

// byPassSecurity
import { DomSanitizer } from '@angular/platform-browser';

@Component({
  moduleId: module.id,
  selector: 'dish',
  templateUrl: 'dishlist.component.html',
  providers: [PostService],
  styleUrls: [
      'dish.component.css',
  ],
})
export class DishComponent{ 
  @ViewChild('auModal') public auModal:ModalDirective;
  isEdit:boolean = false;
  drink_category : number;
  typeCategory : string;
  nameDish : string;
  category_id : number;
  categories : any[] = [];
  model : any = {};
  alerts: any = [];

  // pagination
  pagination : any = {};
  isEnablePagination : boolean = true;

  // dishes
  dishes : any[] = [];
  dish : any = {};
  item : any = {};

  // category
  listCategorySelect: any = [];
  listCategory: any = [];
  selectedDishValues: any = [];

  // addon
  listAddonSelect: any = [];
  listAddon: any = [];
  selectedAddonValues: any = [];

  // size
  dish_sizes: any = [
      {'size': 'SMALL'},
      {'size': 'MEDIUM'},
      {'size': 'LARGE'}
  ];

  // for delete
  nameAction : string;
  idDish : string;

  // Upload file
  public files : File;

  // For paginator
  public maxSize:number = 5;
  public bigTotalItems:number;
  public bigCurrentPage:number = 1;
  public numPages:number = 0;

  public pageChanged(event:any):void {
    // console.log('Page changed to: ' + event.page);
    // console.log('Number items per page: ' + event.itemsPerPage);
    this.pagination.page = event.page;
    this.getListItemDishCustom();

  }

  // ALERT
  showAlert(msg : string, type: string): void {
    this.alerts.push({
      type: type,
      msg: msg,
      timeout: 5000
    });
  }

  deleteAlerts(ev : any){
    this.alerts.pop();
  }

  constructor(
    private _postService: PostService,
    private route: ActivatedRoute,
    private router: Router, 
    public sanitizer: DomSanitizer,
    public global: Globals,
    private changeDetectorRef: ChangeDetectorRef){

    this.drink_category = this.route.snapshot.queryParams['drink_category'];
    this.category_id = this.route.snapshot.queryParams['category_id'];
    this.model.url = '../assets/custom/img/menu/breakfast.png';
    this.dish.spicy_levels = '1';

    if (this.drink_category == 0){
      this.typeCategory = "DISHES";
    } else {
      this.typeCategory = "DRINKS";
    }

    this.getDataFirstTime();

  }

  ngOnInit() {

  }

  getDataFirstTime(){
    this._postService.getListCategoryByType(this.drink_category ,localStorage.getItem('tokenMerchant'))
      .subscribe(
          data => {
              if (data.result == 1){
                // console.log(data);
                this.categories = data.data;

                // DEMO ONLY
                this.pagination.page = 1;
                this.pagination.item_per_page = 12;

                this.getListItemDishCustom();

              } else {
              }
          },
          error => {
          }
      );
  }

  // Button Edit Click :v
  editDish(dish:any){
    if(this.isEdit == false) {
      this.isEdit = true;
      // Get detail dish
      this._postService.getDetailDish(localStorage.getItem('tokenMerchant'), dish.id)
      .subscribe(
        data => {
          if (data.result == 1){
            this.dish = data.data;
            this.nameDish = this.dish.name;
            this.model.categories = data.data.categories;
            let cat_id : any = [];
            for (let cat of this.model.categories) {
              cat_id.push(cat.id);
            }
            this.model.category_id = cat_id;

            let addon_id : any = [];
            for (let addon of data.data.add_ons) {
              addon_id.push(addon.id);
            }
            this.model.addons = addon_id;
            this.model.add_ons = data.data.add_ons; // For get

            this.dish.spicy_levels = data.data.spicy_level_id;
            
            // Get list category
            this._postService.getListCategoryDrinkCategory(localStorage.getItem('tokenMerchant'))
            .subscribe(
                data => {
                    if (data.result == 1){
                      this.listCategorySelect = data.data.map((value: any) => {return value.name});
                      this.listCategory = data.data;

                      // categories_id
                      let selectedDishValues: any = [];
                      for (let cate of this.model.categories) {
                        selectedDishValues.push(cate.name);
                      }
                      this.selectedDishValues = selectedDishValues;
                    }
                },
                error => {

                });

            // Select addon
            this._postService.getAllListAddon(localStorage.getItem('tokenMerchant'))
              .subscribe(
              data => {
                  if (data.result == 1){
                      this.listAddonSelect = data.data.map((value: any) => {return value.name});
                      this.listAddon = data.data;
                      // addon_id
                      let selectedAddonValues: any = [];
                        for (let addon of this.model.add_ons) {
                            selectedAddonValues.push(addon.name);
                        }
                      this.selectedAddonValues = selectedAddonValues;
                      // console.log(this.selectedAddonValues);
                  }
              },
              error => {

              });

            // sizes
            this.dish_sizes[0].price = data.data.sizes[0] ? data.data.sizes[0].price : '';
            this.dish_sizes[1].price = data.data.sizes[1] ? data.data.sizes[1].price : '';
            this.dish_sizes[2].price = data.data.sizes[2] ? data.data.sizes[2].price : '';


            // online_order_inventory
            this.dish.online_order_inventory = data.data.online_order_inventory;
        } else {

        }
      },
      error => {
      });
    }else{
      this.isEdit = false;
    }
    return this.isEdit;
  }

  getItemById(id : number){
    // console.log(id);
    this.bigCurrentPage = 1;
    this.router.navigate(['/dish'], { queryParams: { category_id: id, drink_category:  this.drink_category} });
    this.category_id = id;
    this.getListItemDishCustom();
  }

  getListItemDishCustom(){
    this._postService.getListItemDish(
      localStorage.getItem('tokenMerchant'),
      this.category_id,
      this.pagination.page,
      this.pagination.item_per_page)
      .subscribe(
          data => {
              if (data.result == 1){
                this.dishes = data.data.data; // @@!
                this.bigTotalItems = data.data.total;
                if (this.bigTotalItems <= 12){
                  this.isEnablePagination = false;
                } else {
                  this.isEnablePagination = true;
                }
                this.bigCurrentPage = data.data.current_page;
                // this.smallnumPages = data.data.per_page;
                this.dishes.forEach((item, index) => {
                    if (item.image != null){
                      if (item.image.path.indexOf("http") > -1){
                      } else {
                        this.dishes[index].image.path = this.global.host + this.dishes[index].image.path.replace('.','');
                      }
                    }
                });
              } else {
                
              }
          },
          error => {
          }
      );
  }

  submitEdit(){
    this.model.id = this.dish.id;
    this.model.name = this.dish.name;
    this.model.description = this.dish.description;
    this.model.spicy_level = this.dish.spicy_levels;
    this.model.online_order_inventory = this.dish.online_order_inventory;
    // sizes
    let sizes: any = [];
    this.dish_sizes.forEach((x:any) => {
        if(x.price){
          sizes.push(x);
        }
    });

    sizes = {"records": sizes}
    this.model.sizes = JSON.stringify(sizes);

    console.log(this.model);

    if (this.model.category_id == null || this.model.category_id == ""){
      this.showAlert("Please input all field!", 'danger');
    } else {
      this._postService.editDish(localStorage.getItem('tokenMerchant'), this.model)
      .subscribe(
      data => {
          if (data.result == 1){
            this.showAlert("Edit dish success!", 'success');
            // this.selectedDishValues = [];
            // this.selectedAddonValues = [];
          } else {
            this.showAlert("Edit dish unsuccess! " + data.messages, 'danger');
          }
      },
      error => {
        this.showAlert("Please report to admin!", 'danger');
      });
    }

  }

  refreshDishValue(value:any):void {
    let listDish: any = [];
    value.forEach((x:any) => {
        let _listDish = this.listCategory.filter((value: any) => value.name === x.id);
        listDish.push(_listDish[0].id);
    });
    // console.log(listDish.toString().replace('[',''));
    this.model.category_id = listDish.toString().replace('[','');
    // this.model.category_id = listDish.sort(((a: any, b: any) => {return a-b})).toString();
  }

  refreshAddonValue(value:any):void {
        let listAddon: any = [];
        value.forEach((x:any) => {
            let _listAddon = this.listAddon.filter((value: any) => value.name === x.id);
            listAddon.push(_listAddon[0].id);
        });
        // console.log(listAddon.toString().replace('[',''));
        this.model.addons = listAddon.toString().replace('[','');
        // this.model.category_id = listDish.sort(((a: any, b: any) => {return a-b})).toString();
    }

  // Delete dish
  showDelItem(item : any){
    this.item = item;
    this.nameAction = "Delete";
    this.nameDish = item.name;
    this.idDish = item.id;
    this.auModal.show();
  }

  deleteDish(){
    // console.log("Call API Delete Dish: " + this.item.name);
    this._postService.deleteDish(this.item.id, localStorage.getItem('tokenMerchant'))
      .subscribe(
      data => {
          if (data.result == 1){
            this.showAlert("Delete dish success!", 'success');
            this.dishes = this.dishes.filter(item => item !== this.item);
          } else {
            this.showAlert("Delete dish unsuccess! " + data.messages , 'danger');
          }
      },
      error => {
        this.showAlert("Please report to admin!", 'danger');
      });
    this.auModal.hide();
  }


  // UPLOAD PICTURE
    // For preview and upload :3
  fileChange(input : any){
    this.readFiles(input.files);
  }

  readFile(file : any, reader : any, callback : any){
    reader.onload = () => {
      callback(reader.result);
      this.model.url = reader.result;
      this.dish.image = reader.result;
      // console.log(reader.result);
      localStorage.setItem('setup_images', reader.result);
    }

    reader.readAsDataURL(file);
  }

  readFiles(files : any, index=0){
    // Create the file reader
    let reader = new FileReader();
    
    // If there is a file
    if(index in files){
      // Start reading this file
      this.readFile(files[index], reader, (result : any) =>{
        // Create an img element and add the image file data to it
        var img = document.createElement("img");
        img.src = result;
      });
    }else{
      // When all files are done This forces a change detection
      this.changeDetectorRef.detectChanges();
    }
  }

  // Upload file
  onChangeFile(event : any) {
    this.files = event.srcElement.files[0];
    this.model.image = this.files;
    // console.log(this.files);
    // this.globals.setFile(this.files);
  }

}
