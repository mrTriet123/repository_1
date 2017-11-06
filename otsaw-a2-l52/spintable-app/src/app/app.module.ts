import { NgModule }      from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { FormsModule } from '@angular/forms';
import { HttpModule } from '@angular/http';
import { AlertModule } from 'ng2-bootstrap';
import { TooltipModule } from 'ng2-bootstrap';
import { PaginationModule } from 'ng2-bootstrap';
import { SelectModule } from 'ng2-select';
// import { PopoverModule } from 'ng2-bootstrap/popover';
import { PopoverModule } from 'ng2-bootstrap';
import { ButtonsModule } from 'ng2-bootstrap';
// import { BootstrapModalModule } from 'angular2-modal/plugins/bootstrap';

import { AppComponent }  from './app.component';
// Reset Password
import { ResetPasswordComponent }  from './components/resetpassword/resetpassword.component';
// Admin
import { LoginComponent }  from './components/login/login.component';
import { AdminComponent }  from './components/admin/admin.component';
import { NavBarAdminComponent } from './components/navbar-admin/navbar-admin.component';
import { AuthGuard } from './components/_guards/index';
import { HeaderAdminComponent } from './components/header-admin/header-admin.component';
import { MerchantComponent } from './components/merchant/merchant.component';
// Public
import { LoginMerchantComponent }  from './components/login-merchant/login-merchant.component';
import { RegisterMerchantComponent }  from './components/register-merchant/register-merchant.component';
import { HomeComponent }  from './components/home/home.component';
import { CreateCategory }  from './components/create-category/create-category.component';
import { EditCategory }  from './components/edit-category/edit-category.component';
import { HistoryComponent }  from './components/history/history.component';
import { SettingComponent }  from './components/setting/setting.component';
import { ReportComponent }  from './components/report/report.component';
import { MenuComponent }  from './components/menu/menu.component';
import { DishComponent }  from './components/dish/dish.component';
import { NavBarComponent } from './components/navbar/navbar.component';
import { SetupNavBarComponent } from './components/setup-navbar/setup-navbar.component';
import { SetupHeaderComponent } from './components/setup-header/setup-header.component';
import { SetupComponent } from './components/pages/setup/setup.component';
import { SetupStep2Component } from './components/pages/setup-step2/setup-step2.component';
import { SetupStep3Component } from './components/pages/setup-step3/setup-step3.component';
import { SetupStep4Component } from './components/pages/setup-step4/setup-step4.component';
import { SetupStep5Component } from './components/pages/setup-step5/setup-step5.component';
import { HeaderComponent } from './components/header/header.component';
import { FooterComponent } from './components/footer/footer.component';
import { FxDatepickerComponent }     from './components/library/datepicker.component';
import { FxModalComponent }     from './components/home/modal.component';
import { ClickOutsideModule}   from './components/library/ng2-click-outside.module';
import { AddItemsToMenuComponent }  from './components/add-items-to-menu/add-items.component';
// import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect';
// import { MultiselectDropdownModule } from 'angular-2-dropdown-multiselect/src/multiselect-dropdown';


import { Globals } from './services/globals/globals.service';
import { AuthenticationService } from './services/authentication/authentication.service';
// import { ModalModule } from 'angular2-modal';
// import { BootstrapModalModule } from 'angular2-modal/plugins/bootstrap';
import { ModalModule, CarouselModule, DatepickerModule, TimepickerModule} from 'ng2-bootstrap';
// Layouts
import { PublicLayoutComponent } from './components/layouts/public-layout.component';
import { AdminLayoutComponent } from './components/layouts/admin-layout.component';
import { LoginLayoutComponent } from './components/layouts/login-layout.component';



// import { routing } from './app.routing';
// Routing Module
import { AppRoutingModule } from './app.routing';

@NgModule({
  imports:      [ 
    BrowserModule,
    FormsModule,
    HttpModule,
    AlertModule.forRoot(),
    TooltipModule.forRoot(),
    PopoverModule.forRoot(),
    PaginationModule.forRoot(),
    SelectModule,
    // MultiselectDropdownModule,
    ModalModule.forRoot(),
    CarouselModule.forRoot(),
    DatepickerModule.forRoot(),
    // DropdownModule.forRoot(),
    TimepickerModule.forRoot(),
    ButtonsModule.forRoot(),
    ClickOutsideModule,
    AppRoutingModule
  ],
  declarations: [ 
    // Layout
    PublicLayoutComponent,
    LoginLayoutComponent,
    AdminLayoutComponent,
    AppComponent,
    // Reset Password
    ResetPasswordComponent,
    // Admin
    NavBarAdminComponent,
    HeaderAdminComponent,
    LoginComponent,
    AdminComponent,
    MerchantComponent,
    // Public
    LoginMerchantComponent,
    RegisterMerchantComponent,
    HomeComponent,
    CreateCategory,
    EditCategory,
    HeaderComponent,
    NavBarComponent,
    SettingComponent,
    SetupComponent,
    ReportComponent,
    MenuComponent,
    AddItemsToMenuComponent,
    DishComponent,
    HistoryComponent,
    SetupStep2Component,
    SetupStep3Component,
    SetupStep4Component,
    SetupStep5Component,
    SetupNavBarComponent, 
    SetupHeaderComponent,
    FooterComponent,
    FxDatepickerComponent,
    FxModalComponent,
  ],
  bootstrap:    [ AppComponent ],
  providers: [ Globals, AuthGuard, AuthenticationService ]
})
export class AppModule { 
  public dt: Date = new Date();
}