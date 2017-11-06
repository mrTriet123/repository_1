import { OnInit, Component, ChangeDetectorRef, EventEmitter } from '@angular/core';
import { PostService } from '../../services/post/post.service';
import { Router, ActivatedRoute } from '@angular/router';

@Component({
    moduleId: module.id,
    selector: 'create-category',
    templateUrl: 'create-category.component.html',
    styleUrls: ['create-category.component.css'],
    providers: [PostService],
})
export class CreateCategory { 
  type : string = '';
  typeList: any = ['Free Ones', 'Pay Ones'];
  isPayOne : boolean = false;
  alerts: any = [];
  model : any = {};
  //
  addon : any = {};
  dish : any = {};
  drink : any = {};

  // For preview and upload :3
  public file_srcs: string[] = [];
  public debug_size_before: string[] = [];
  public debug_size_after: string[] = [];

  // Upload file
  public files : File;

  constructor(
      private _postService: PostService,
      private route: ActivatedRoute,
      private router: Router,
      private changeDetectorRef: ChangeDetectorRef
      ) {
      this.type = this.route.snapshot.queryParams['type'];
      if ((this.type != "Addon") && (this.type != "Dishes") && (this.type != "Drinks")){
        this.router.navigate(['/menu']);
      }
      this.model.type = this.typeList[0];
      this.model.price = 0;
      this.model.url = "../assets/custom/img/menu/breakfast.png";
  }

  saveData(){
    if (this.type == "Addon"){
      this.addon.name = this.model.name;
      this.addon.type = this.model.type;
      this.addon.price = this.model.price;
      // console.log(this.addon);
      this._postService.createAddon(this.addon ,localStorage.getItem('tokenMerchant'))
      .subscribe(
          data => {
              if (data.result == 1){
               this.showAlert("Create addon success!", 'success');
              } else {
                this.showAlert("Create addon unsuccess!", 'danger');
              }
          },
          error => {
            this.showAlert("Please report to admin!", 'danger');
          }
      );
    }
    if (this.type == "Dishes"){
      this.dish.drink_category = 0;
      this.dish.name = this.model.name;
      this.dish.description = this.model.description;
      this.dish.image = this.model.image;
      // console.log(this.dish);
      this._postService.createCategory(this.dish ,localStorage.getItem('tokenMerchant'))
      .subscribe(
          data => {
              if (data.result == 1){
               this.showAlert("Create dish success!", 'success');
              } else {
                this.showAlert("Create dish unsuccess!", 'danger');
              }
          },
          error => {
            this.showAlert("Please report to admin!", 'danger');
          }
      );
    }
    if (this.type == "Drinks"){
      this.drink.drink_category = 1;
      this.drink.name = this.model.name;
      this.drink.description = this.model.description;
      this.drink.image = this.model.image;
      // console.log(this.drink);
      this._postService.createCategory(this.drink ,localStorage.getItem('tokenMerchant'))
      .subscribe(
          data => {
              if (data.result == 1){
               this.showAlert("Create drink success!", 'success');
              } else {
                this.showAlert("Create drink unsuccess!", 'danger');
              }
          },
          error => {
            this.showAlert("Please report to admin!", 'danger');
          }
      );
    }
    
  }

  isAddon(){
    if (this.type == "Addon"){
        return true;
    }
    return false;
  }

  backToMenu(){
    this.router.navigate(['/menu']);
  }
 
  public refreshValue(value:any):void {
    this.model.type = value.text;
    if (value.text == "Pay Ones"){
      this.isPayOne = true;
    } else {
      this.isPayOne = false;
      this.model.price = 0;
    }
  }

  // ALERT
  showAlert(msg : string, type: string): void {
    this.alerts.push({
      type: type,
      msg: msg,
      timeout: 5000
    });
  }


  // For preview and upload :3
  fileChange(input : any){
    this.readFiles(input.files);
  }
  readFile(file : any, reader : any, callback : any){
    reader.onload = () => {
      callback(reader.result);
      this.model.url = reader.result;
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
    
        // Send this img to the resize function (and wait for callback) (Can delete)
        this.resize(img, 250, 250, (resized_jpeg : any, before : any, after : any)=>{
          // For debugging (size in bytes before and after)
          this.debug_size_before.push(before);
          this.debug_size_after.push(after);
    
          // Add the resized jpeg img source to a list for preview
          // This is also the file you want to upload. (either as a
          // base64 string or img.src = resized_jpeg if you prefer a file). 
          this.file_srcs.push(resized_jpeg);
    
          // Read the next file;
          this.readFiles(files, index+1);
        });
      });
    }else{
      // When all files are done This forces a change detection
      this.changeDetectorRef.detectChanges();
    }
  }

  resize(img : any, MAX_WIDTH:number, MAX_HEIGHT:number, callback : any){
    // This will wait until the img is loaded before calling this function
    return img.onload = () => {

      // Get the images current width and height
      var width = img.width;
      var height = img.height;

      // Set the WxH to fit the Max values (but maintain proportions)
      if (width > height) {
          if (width > MAX_WIDTH) {
              height *= MAX_WIDTH / width;
              width = MAX_WIDTH;
          }
      } else {
          if (height > MAX_HEIGHT) {
              width *= MAX_HEIGHT / height;
              height = MAX_HEIGHT;
          }
      }

      // create a canvas object
      var canvas = document.createElement("canvas");

      // Set the canvas to the new calculated dimensions
      canvas.width = width;
      canvas.height = height;
      var ctx = canvas.getContext("2d");  

      ctx.drawImage(img, 0, 0,  width, height); 

      // Get this encoded as a jpeg
      // IMPORTANT: 'jpeg' NOT 'jpg'
      var dataUrl = canvas.toDataURL('image/jpeg');

      // callback with the results
      callback(dataUrl, img.src.length, dataUrl.length);
    };
  }

  // Upload file
  onChangeFile(event : any) {
    this.files = event.srcElement.files[0];
    this.model.image = this.files;
    // console.log(this.files);
    // this.globals.setFile(this.files);
  }
}
