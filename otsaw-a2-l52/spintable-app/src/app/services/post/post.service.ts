import { Injectable } from '@angular/core';
import { Http, URLSearchParams } from '@angular/http';
import 'rxjs/add/operator/map';

@Injectable()
export class PostService{
	url: String;
    constructor(private http: Http){
        //this.url = 'http://spintable.dev/api/v1/'; // backend url
        this.url = 'http://localhost:8000/api/v1/'; // backend url
    }

    orders(merchantId: number, date: string){
        return this.http.get(this.url + 'merchants/'+merchantId+'/orders?date='+date)
            .map(res => res.json());
    }

    bookings(merchantId: number, date: string){
        return this.http.get(this.url + 'merchants/'+merchantId+'/bookings?date='+date)
            .map(res => res.json());
    }

    walkins(merchantId: number, date: string){
        return this.http.get(this.url + 'merchants/'+merchantId+'/walkins?date='+date)
            .map(res => res.json());
    }

    orderDetails(merchantId:number, reservableId: number){
    	return this.http.get(this.url + 'merchants/'+merchantId+'/reservables/'+reservableId)
    		.map(res => res.json());
    }

    recentNotifications(merchantId: number){
    	return this.http.get(this.url + 'merchants/'+merchantId+'/recent_notifications').map(res => res.json());
    }

    markAsRead(merchantId: number, notificationId: number){
        return this.http.get(this.url + 'merchants/'+merchantId+'/notifications/'+notificationId+'/mark_as_read')
            .map(res => res.json());
    }

    history(merchantId: number, type: string, date: string){
        return this.http.get(this.url + 'merchants/'+merchantId+'/history?type=' +type+'&date='+date)
            .map(res => res.json());
    }

    // Admin Merchant
    listAllMerchant(token: string){
        return this.http.get(this.url + 'admin-side/list-merchant?token='+token)
            .map(res => res.json());
    }

    addMerchant(token: string, obj : any){
        let data = new URLSearchParams();
        data.append('firstname', obj.firstname);
        data.append('lastname', obj.lastname);
        data.append('email', obj.email);
        data.append('password', obj.password);

        return this.http.post(this.url + 'admin-side/create-merchant?token='+token, data)
            .map(res => res.json());
    }

    editMerchant(token: string, obj : any){
        let data = new URLSearchParams();
        data.append('firstname', obj.firstname);
        data.append('lastname', obj.lastname);
        data.append('email', obj.email);
        // data.append('password', obj.password);

        return this.http.post(this.url + 'admin-side/update-merchant/'+obj.id+'?token='+token, data)
            .map(res => res.json());
    }

    deleteMerchant(token: string, obj : any){
        return this.http.get(this.url + 'admin-side/delete-merchant/'+obj.id+'?token='+token)
            .map(res => res.json());
    }

    generatedPassword(token: string, obj : any){
        let data = new URLSearchParams();
        data.append('email', obj.email);
        return this.http.post(this.url + 'admin-side/generated-password-merchant?token='+token, data)
            .map(res => res.json());
    }

    // Public Merchant
    registerMerchant(obj : any){
        let data = new URLSearchParams();
        data.append('firstname', obj.firstname);
        data.append('lastname', obj.lastname);
        data.append('email', obj.email);
        data.append('restaurant_name', obj.restaurantname);
        data.append('restaurant_type', obj.restauranttype);
        data.append('tel_no', obj.contactnumber);
        data.append('location', obj.location);

        return this.http.post(this.url + 'mersignup', data)
            .map(res => res.json());
    }

    checkSetup(token: string){
        return this.http.get(this.url + 'setup-page/check?token='+token)
            .map(res => res.json());
    }

    // Restaurant
    restaurantInfo(id: number){
        return this.http.get(this.url + 'restaurants/'+ id)
            .map(res => res.json());
    }

    getRestaurantType(){
        return this.http.get(this.url + 'restaurants/get-type')
            .map(res => res.json());
    }

    changePassword(obj : any, token : string){
        let data = new URLSearchParams();
        data.append('password_old', obj.oldpassword);
        data.append('password_new', obj.newpassword);

        return this.http.post(this.url + 'users/merchant-change-password?token=' + token, data)
            .map(res => res.json());
    }


    // SETUP ACCOUNT
    saveAccountSetup(obj : any, token : string){
        let data = new FormData();
        data.append('account_name', obj.name);
        data.append('stripe_token', obj.token);
        data.append('describe_restaurant', obj.describe);
        data.append('employees', obj.emails);
        // data.append('image', obj.image);
        // data.append('employees', JSON.stringify(obj.emails));
        data.append('image', obj.image);

        return this.http.post(this.url + 'setup-page?token=' + token, data)
            .map(res => res.json());
    }

    getDataSetting(token : string){
        return this.http.get(this.url + 'settings/order/get-data?token=' + token)
            .map(res => res.json());
    }

    saveDataSetting(obj : any, token : string){
        let data = new URLSearchParams();
        data.append('start_time', obj.start_time);
        data.append('end_time', obj.end_time);
        data.append('merchant_table', obj.merchant_table);
        data.append('hour_slot_per_table', obj.hour_slot_per_table);
        data.append('repeat', obj.repeat);
        data.append('offer_name', obj.offer_name);
        data.append('from', obj.from);
        data.append('to', obj.to);
        data.append('discount_type', obj.discount_type);
        data.append('total_discount', obj.total_discount);
        data.append('dish_id', obj.dish_id);

        return this.http.post(this.url + 'settings/order?token=' + token, data)
            .map(res => res.json());
    }

    editDataSetting(obj : any, token : string){
        let data = new URLSearchParams();
        data.append('start_time', obj.start_time);
        data.append('end_time', obj.end_time);
        data.append('merchant_table', obj.merchant_table);
        data.append('hour_slot_per_table', obj.hour_slot_per_table);
        data.append('repeat', obj.repeat);
        data.append('offer_name', obj.offer_name);
        data.append('from', obj.from);
        data.append('to', obj.to);
        data.append('discount_type', obj.discount_type);
        data.append('total_discount', obj.total_discount);
        data.append('dish_id', obj.dish_id);

        return this.http.post(this.url + 'settings/order/edit?token=' + token, data)
            .map(res => res.json());
    }


    listAddOns(token : string, type: string = ''){
        type = type ? '&type=' + type : '';
        return this.http.get(this.url + 'add-ons/list?token='+ token + type)
            .map(res => res.json());
    }

    createAddOns(token : string, obj: any){
        let data = new URLSearchParams();
        data.append('name', obj.name);
        data.append('type', obj.type);
        data.append('price', obj.price);

        return this.http.post(this.url + 'add-ons/create?token=' + token, data)
            .map(res => res.json());
    }

    getListDish(token : string){
        return this.http.get(this.url + 'category/list-category-dishes?token=' + token)
            .map(res => res.json());
    }


    getDetailDish(token: string, id: any){
        return this.http.get(this.url + 'dish/detail?token='+ token +'&dish_id=' + id)
                .map(res => res.json());
    }

    getAllListCategory(token : string){
        return this.http.get(this.url + 'category/list-all?token=' + token)
            .map(res => res.json());
    }

    getListCategoryDrinkCategory(token : string, drink_category: string = ''){
        drink_category = drink_category ? '&drink_category=' + drink_category : '';
        return this.http.get(this.url + 'category/list?token=' + token + drink_category)
            .map(res => res.json());
    }

    createDish(obj : any, token : string){
        let data = new FormData();
        data.append('name', obj.name);
        data.append('description', obj.description);
        data.append('spicy_level', obj.spicy_levels);
        data.append('online_order_inventory', obj.online_order_inventory);
        data.append('category_id', obj.category_id);
        data.append('addons', obj.addons);
        data.append('image', obj.image);
        data.append('sizes', obj.sizes);

        return this.http.post(this.url + 'dish/create?token=' + token, data)
            .map(res => res.json());
    }

    createAddon(obj : any, token : string){
        let data = new URLSearchParams();
        data.append('name', obj.name);
        data.append('type', obj.type);
        data.append('price', obj.price);

        return this.http.post(this.url + 'add-ons/create?token=' + token, data)
            .map(res => res.json());
    }

    createCategory(obj : any, token : string){
        let data = new FormData();
        data.append('drink_category', obj.drink_category);
        data.append('name', obj.name);
        data.append('description', obj.description);
        data.append('image', obj.image);

        return this.http.post(this.url + 'category/create?token=' + token, data)
            .map(res => res.json());
    }

    deleteCategory(id : string, token : string){
        let data = new URLSearchParams();
        data.append('id', id);

        return this.http.post(this.url + 'category/delete?token=' + token, data)
            .map(res => res.json());
    }
    
    deleteAddon(id : string, token : string){
        let data = new URLSearchParams();
        data.append('id', id);

        return this.http.post(this.url + 'add-ons/delete?token=' + token, data)
            .map(res => res.json());
    }

    getListCategoryByType(drink_category : number, token : string){
        return this.http.get(this.url + 'category/list?token=' + token + '&drink_category=' + drink_category)
            .map(res => res.json());
    }

    getAllListAddon(token : string){
        return this.http.get(this.url + 'add-ons/list?token=' + token)
            .map(res => res.json());
    }

    // EDIT category
    editAddon(token : string, obj: any){
        let data = new URLSearchParams();
        data.append('id', obj.id);
        data.append('name', obj.name);
        data.append('type', obj.type);
        data.append('price', obj.price);

        return this.http.post(this.url + 'add-ons/edit?token=' + token, data)
            .map(res => res.json());
    }

    editCategory(obj : any, token : string){
        let data = new FormData();
        data.append('id', obj.id);
        data.append('drink_category', obj.drink_category);
        data.append('name', obj.name);
        data.append('description', obj.description);
        data.append('image', obj.image);

        return this.http.post(this.url + 'category/edit?token=' + token, data)
            .map(res => res.json());
    }

    getListItemDish(token : string, category_id : number, page : number, item_per_page : number){
        return this.http.get(this.url + 'category/list-items?token=' + token + '&category_id=' + category_id
            + '&page=' + page + '&item_per_page=' + item_per_page)
            .map(res => res.json());
    }

    editDish(token : string, obj: any){
        let data = new FormData();
        data.append('id', obj.id);
        // dishes table 
        data.append('name', obj.name);
        data.append('description', obj.description);
        data.append('spicy_level', obj.spicy_level);
        data.append('online_order_inventory', obj.online_order_inventory);
        // dish_categories table
        data.append('category_id', obj.category_id);
        // dish_addons table
        data.append('addons', obj.addons);
        // dish_images table
        data.append('image', obj.image);
        // dish_sizes table
        data.append('sizes', obj.sizes);
        data.append('image', obj.image);

        return this.http.post(this.url + 'dish/edit?token=' + token, data)
            .map(res => res.json());
    }

    deleteDish(id : string, token : string){
        let data = new URLSearchParams();
        data.append('id', id);

        return this.http.post(this.url + 'dish/delete?token=' + token, data)
            .map(res => res.json());
    }

    searchDish(token : string, page: any, item_per_page: any, keyword: string){
        keyword = keyword ? '&keyword=' + keyword : '';
        return this.http.get(this.url + 'dish/search?token=' + token + '&page=' + page + '&item_per_page=' + item_per_page + '&keyword=' + keyword)
            .map(res => res.json());
    }
}
