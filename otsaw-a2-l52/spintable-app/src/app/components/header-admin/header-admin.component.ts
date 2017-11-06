import { Component, ViewChild, Input } from '@angular/core';
import { Globals } from '../../services/globals/globals.service'; // For file globals

@Component({
  moduleId: module.id,
  selector: 'header-admin',
  templateUrl: 'header-admin.component.html',
  
})
export class HeaderAdminComponent  { 

  constructor(private globals : Globals){
    
  }

  ngOnInit() {
        
  }

}