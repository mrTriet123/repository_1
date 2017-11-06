import { Component, ViewChild, ElementRef, trigger, transition, style, animate } from '@angular/core';
import { PostService } from '../../services/post/post.service';
import { ModalDirective } from 'ng2-bootstrap';
import { AlertModule } from 'ng2-bootstrap';
import { Router, ActivatedRoute } from '@angular/router';
import { Globals } from '../../services/globals/globals.service';

@Component({
    moduleId: module.id,
    selector: 'merchant-add-items-to-menu',
    templateUrl: 'add-items.component.html',
    styleUrls: ['add-items.component.css'],
    providers: [PostService],
})
export class AddItemsToMenuComponent  { 
    @ViewChild('auModal') public auModal:ModalDirective;
    model: any = {};
    addon_type: any = {
        free_ones : 'Free Ones',
        pay_ones : 'Pay Ones'
    };
    alerts: any = [];
    defaultImage = 'https://s3-ap-southeast-1.amazonaws.com/hillman-dev/item_images/14877438910.png';
    listSearch: any = [];
    listCategorySelect: any = [];
    copyAnotherDishModel: number;
    showAddOnsFree: boolean = false;
    add_new_add_on: any = {
        price: 0,
    };
    list_add_ons: any = [];
    type_add_ons: string;
    add_new_add_on_list: any = [];
    add_new_add_check: any = {};
    add_ons_free: any = [];
    add_ons_pay: any = [];
    search_dish: string = '';
    dish_sizes: any = [
        {'size': 'SMALL'},
        {'size': 'MEDIUM'},
        {'size': 'LARGE'}
    ];
    default_category_select: any = [];

    constructor(private _postService: PostService, private route: ActivatedRoute,
        private router: Router, private element: ElementRef, public global: Globals) {
        let action = localStorage.getItem('action');
        if (action == 'Save'){
            this.router.navigate(['/settings']);
        }
    }

    ngOnInit() {
        this.model = {
            spicy_levels: '2',
            online_order_inventory: 0,
            sizes: []
        }
        this.fListCategory();
    }

    removeItemAddOns(data: any, index: number){
        data.splice(index, 1);
    }
    showAddOns(model: string, show: boolean){
        this.showAddOnsFree = show;
        this.type_add_ons = model;
        if(show){
            this.listAddOns(model);
        }
    }

    addNewAddOns(add_new_add_on: any, form: any){
        if(add_new_add_on.name){
            add_new_add_on.type = this.type_add_ons;
            this._postService.createAddOns(localStorage.getItem('tokenMerchant'),add_new_add_on)
            .subscribe(
                data => {
                    if (data.result == 1){
                        let new_data = {
                            id: data.id,
                            name: add_new_add_on.name,
                            type: add_new_add_on.type,
                            price: add_new_add_on.price,
                        };
                        this.list_add_ons.unshift(new_data);
                        form.reset();
                    }
                },
                error => {

                });
        }
    }

    listAddOns(model: any){
        this.add_new_add_check = {};
        this._postService.listAddOns(localStorage.getItem('tokenMerchant'), model)
        .subscribe(
            data => {
                if (data.result == 1){
                    this.list_add_ons = data.data;
                }
            },
            error => {

            });
    }


    fListCategory(){
        this._postService.getListCategoryDrinkCategory(localStorage.getItem('tokenMerchant'))
        .subscribe(
            data => {
                if (data.result == 1){
                    this.listCategorySelect = data.data.map((value: any) => {return {id: value.id, text: value.name}});
                }
            },
            error => {

            });
    }

    refreshDishValue(value:any):void {
        let listDish: any = [];
        listDish = value.map((v: any) => v.id);
        this.model.category_id = listDish.sort(((a: any, b: any) => {return a-b})).toString();
    }

    acceptAddOns(){
        let add_ons_arr = this.type_add_ons === this.addon_type.free_ones ? 'add_ons_free' : 'add_ons_pay';

        this.add_new_add_on_list.forEach((x:any) => {
            let _arr1 = this[add_ons_arr].filter((value: any) => value.id === x.id);
            if(_arr1.length === 0){
                this[add_ons_arr].push(x);
            }
        });
        this.add_new_add_on_list = [];
        this.showAddOns('', false);
    }
    checkHasAddOns(item: any){
        let add_ons_arr = this.type_add_ons === this.addon_type.free_ones ? 'add_ons_free' : 'add_ons_pay';
        let check_has = this[add_ons_arr].filter((value: any) => value.id === item.id);
        if(check_has.length > 0){
            return true;
        }
        return false;
    }

    addItemToAddOnsArray(check: any, item: any){
        if(check){
            let arr = this.add_new_add_on_list.filter((value: any) => value.id === item.id);
            if(arr.length === 0){
                this.add_new_add_on_list.push(item);
            }
        }else{
            this.add_new_add_on_list.forEach((x:any, i: any) => {
                if(x.id === item.id){
                    this.add_new_add_on_list.splice(i, 1);
                }
            });
        }
    }

    // search dish
    copyAnotherDish(key: any){
        this.searchResult(key);
        this.search_dish = key;
    }

    openCopyAnotherDishModal(modal: any){
        modal.show();
        let key = this.search_dish ? this.search_dish : '';
        this.searchResult(key);
    }

    copyAnotherDishAccept(id: number, modal: any){
        this._postService.getDetailDish(localStorage.getItem('tokenMerchant'), id)
        .subscribe(
            data => {
                if (data.result == 1){
                    modal.hide();
                    // model
                    this.model = data.data;
                    // spice level
                    this.model.spicy_levels = data.data.spicy_level_id.toString();
                    // sizes
                    this.dish_sizes[0].price = data.data.sizes[0] ? parseInt(data.data.sizes[0].price) : '';
                    this.dish_sizes[1].price = data.data.sizes[1] ? parseInt(data.data.sizes[1].price) : '';
                    this.dish_sizes[2].price = data.data.sizes[2] ? parseInt(data.data.sizes[2].price) : '';
                    // add ons
                    if(data.data.add_ons.length > 0){
                        this.add_ons_free = data.data.add_ons.filter((value: any) => value.type === this.addon_type.free_ones);
                        this.add_ons_pay = data.data.add_ons.filter((value: any) => value.type === this.addon_type.pay_ones);
                    }
                    // category
                    this.default_category_select = data.data.categories.map((v: any)=> {return {id: v.id, text: v.name} });
                    let listDish: any = [];
                    listDish = data.data.categories.map((v: any) => v.id);
                    this.model.category_id = listDish.sort(((a: any, b: any) => {return a-b})).toString();
                }
            },
            error => {

            });
    }

    searchResult(key: string = ''){
        let page = 1;
        let item_per_page = 12;
        this._postService.searchDish(localStorage.getItem('tokenMerchant'), page, item_per_page, key)
        .subscribe(
            data => {
                if (data.result == 1){
                    this.listSearch = data.data.data;
                }
            },
            error => {

            });
    }

    onChangeFile(event : any) {
        this.model.image = event.srcElement.files[0];
        var reader = new FileReader();
        var image = this.element.nativeElement.querySelector('.image_preview');
        reader.onload = function(e: any) {
            var src = e.target.result;
            image.src = src;
        };

        reader.readAsDataURL(event.target.files[0]);
    }

    saveAndAddAnotherOne(form: any){
        // handlingModel
        this.handlingModel();
        // create dish
        this._postService.createDish(this.model, localStorage.getItem('tokenMerchant'))
        .subscribe(
            data => {
                if (data.result == 1){
                    form.reset();
                    this.add_ons_free = [];
                    this.add_ons_pay = [];
                    this.list_add_ons = [];
                    this.model = {
                        spicy_levels: '2',
                        online_order_inventory: 0,
                        sizes: [],
                        category_id: []
                    };
                    this.default_category_select = [];
                }else{
                    this.showAlert(data.messages, 'danger');
                }
            },
            error => {
                this.showAlert("Error!", 'danger');
            });
    }

    saveAndFinish(form: any){
        // handlingModel
        this.handlingModel();
        // create dish
        this._postService.createDish(this.model, localStorage.getItem('tokenMerchant'))
        .subscribe(
            data => {
                if (data.result == 1){
                    form.reset();
                    this.add_ons_free = [];
                    this.add_ons_pay = [];
                    this.list_add_ons = [];
                    this.model = {
                        spicy_levels: '2',
                        online_order_inventory: 0,
                        sizes: [],
                        category_id: []
                    };
                    this.default_category_select = [];
                    this.router.navigate(['/home']);
                }else{
                    this.showAlert(data.messages, 'danger');
                }
            },
            error => {
                this.showAlert("Error!", 'danger');
            });
    }


    handlingModel(){
        // add ons
        let addons_arr = this.add_ons_free.concat(this.add_ons_pay);
        addons_arr = addons_arr.map((value: any) => {return value.id});
        this.model.addons = addons_arr.sort(((a: any, b: any) => {return a-b})).toString();

        // sizes
        let sizes: any = [];
        this.dish_sizes.forEach((x:any) => {
            if(x.price){
                sizes.push(x);
            }
        });
        sizes = {"records": sizes}
        this.model.sizes = JSON.stringify(sizes);
    }

      // ALERT
     showAlert(msg : string, type: string): void {
        this.alerts.push({
          type: type,
          msg: msg,
          timeout: 10000
        });
    }

    replaceImageUrl(url: string){
        let image = url.replace('./uploads/dishs/', this.global.host + '/uploads/dishs/')
        return image;
    }
    
}
