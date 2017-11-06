import { Component, OnInit } from '@angular/core';
import {ViewEncapsulation} from '@angular/core';

@Component({
  moduleId: module.id,
  selector: 'app-dashboard',
  templateUrl: 'admin-layout.component.html',
  styleUrls: [
  //     '../../../assets/global/vendor/metisMenu/metisMenu.min.css',
      '../../../assets/global/dist/css/sb-admin-2.css',
  //     '../../../assets/global/vendor/morrisjs/morris.css',
  ],
  encapsulation: ViewEncapsulation.None,
})
export class AdminLayoutComponent implements OnInit {

  constructor() { }

  ngOnInit(): void { }
}
