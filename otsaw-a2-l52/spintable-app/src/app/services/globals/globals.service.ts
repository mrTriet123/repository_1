import { Injectable } from '@angular/core';
import 'rxjs/add/operator/map';

@Injectable()
export class Globals{
    private file : File;
    imagePath: String;
    host : String;
    constructor(){
        this.imagePath = '../../assets/custom/img';
        this.host = 'http://localhost:8000';
        // console.log('SettingService Initialized...');
    }

    setFile(val : File) {
        this.file = val;
    }

    getFile() {
        return this.file;
    }

}