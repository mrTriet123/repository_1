import { OnInit, Component, ChangeDetectorRef, EventEmitter } from '@angular/core';
import { Router, ActivatedRoute, Params } from '@angular/router';
import { PostService } from '../../../services/post/post.service';
import { Globals } from '../../../services/globals/globals.service'; // For file globals

@Component({
  moduleId: module.id,
  selector: 'SetupStep3',
  templateUrl: 'setup-step3.component.html',
  styleUrls: ['setup-step3.component.css'],
  providers: [PostService],
  
})
export class SetupStep3Component implements OnInit { 

  currentUser: any;
  restaurant: any
  model: any = {};

  // For preview and upload :3
  public file_srcs: string[] = [];
  public debug_size_before: string[] = [];
  public debug_size_after: string[] = [];

  // Upload file
  public files : File;

  constructor(private route: ActivatedRoute,
              private router: Router, 
              private globals : Globals,
              private _postService: PostService,
              private changeDetectorRef: ChangeDetectorRef){
    this.currentUser = JSON.parse(localStorage.getItem('GLOBAL_USER'));
    this.getRestaurant(this.currentUser.restaurant.id);
  }

  ngOnInit()
  {
    let setup_code = this.route.snapshot.queryParams['code'];
    if (setup_code){
      localStorage.setItem('setup_code', setup_code.replace(/"/g,''));
    }
    if (localStorage.getItem('setup_describe')){
      this.model.describe = localStorage.getItem('setup_describe');
    }
  }

  getRestaurant(id: number){
    this._postService.restaurantInfo(id)
    .subscribe(data => {
      this.restaurant =  data.data;
      // Check if changed images
      if (localStorage.getItem('setup_images')){
        this.restaurant.images[0].url = localStorage.getItem('setup_images');
      }
    });
  }

  saveInfo(){
    let describe = this.model.describe;
    if (describe){
      localStorage.setItem('setup_describe', describe);
      this.router.navigate(['/step4']);
    }
  }

  // For preview and upload :3
  fileChange(input : any){
    this.readFiles(input.files);
  }
  readFile(file : any, reader : any, callback : any){
    reader.onload = () => {
      callback(reader.result);
      this.restaurant.images[0].url = reader.result;
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
    // console.log(this.files);
    this.globals.setFile(this.files);
  }

}