import { Component, ViewChild, OnInit } from '@angular/core';
import { PostService } from '../../../services/post/post.service';
import { Router } from '@angular/router';

@Component({
	moduleId: module.id,
	selector: 'SetupStep4',
	templateUrl: 'setup-step4.component.html',
	styleUrls: ['setup-step4.component.css'],
	providers: [PostService],
})
export class SetupStep4Component implements OnInit{ 
	title:String;
	description:String;
	model: any = [];

	constructor(private router: Router){
		this.title = "First: Set your accounts";
		this.description = "You have two kind of accounts: administrator and employee. You will be able to manage all aspects of the account";
		if (localStorage.getItem('setup_emails')){
			this.model = JSON.parse(localStorage.getItem('setup_emails'));
		}
	}
	ngOnInit() {

	}
	step4_submit(){
		localStorage.setItem('setup_emails', JSON.stringify(this.model));
		this.router.navigate(['/step5']);
	}
}