import { Component } from '@angular/core';
import { Router, ActivatedRoute } from '@angular/router';
import { DatePipe } from '@angular/common';

@Component({
  moduleId: module.id,
  selector: 'report',
  templateUrl: 'report.component.html',
})
export class ReportComponent  { 
  monthToday: Date = new Date();
  monthValue: string;
  show : any[] = [];

  constructor(
    private route: ActivatedRoute,
    private router: Router) {
      this.monthValue = new DatePipe('pt-PT').transform(this.monthToday, 'yyyy-MM-dd');
    }

  ngOnInit() {
    let action = localStorage.getItem('action');
    if (action == 'Save'){
        this.router.navigate(['/settings']);
    } else {
    }
  }

  updateMonth(){
    this.monthValue = new DatePipe('pt-PT').transform(this.monthToday, 'yyyy-MM-dd');
    console.log(this.monthValue + " - " + this.monthToday);
  }
  
}
