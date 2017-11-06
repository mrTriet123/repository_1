import { Component } from '@angular/core';
import { PostService } from '../../services/post/post.service';

@Component({
  moduleId: module.id,
  selector: 'user',
  templateUrl: '../tpl/user.component.html',
  providers: [PostService]
})
export class UserComponent  { 
  name: string;
  email: string;
  address: Address;
  showHobbies: boolean;
  hobbies: string[];
  posts: Post[];


  constructor(private postsService:PostService){
    this.name = 'Hein'; 
    this.email = 'thawheinthit@gmail.com';
    this.address = {
    block: '319',
    street: 'Clementi Ave 4',
    country: 'Singapore'
    };
    this.hobbies = ['Music','Movie','Swimming'];
    this.showHobbies = false;

    this.postsService.orders(1, '').subscribe(posts => {
        this.posts = posts;
    });
  }

  toggleHobbies(){
      if(this.showHobbies == false){
        this.showHobbies = true;    
      }else {
        this.showHobbies = false;
      }
      
  }

//   addHobby(hobby){
//       this.hobbies.push(hobby);
//   }

//   removeHobby(index){
//       this.hobbies.splice(index,1)
//   }
  
}

interface Address{
    block: string;
    street: string;
    country: string;
}

interface Post{
    id: number;
    title: string;
    body: string;
}
