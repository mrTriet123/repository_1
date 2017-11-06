import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router'

// Reset Password
import { ResetPasswordComponent }  from './components/resetpassword/resetpassword.component';

//Admin
// import {AppComponent} from './app.component';
import {LoginComponent} from './components/login/login.component';
import {MerchantComponent} from './components/merchant/merchant.component';
import {AdminComponent} from './components/admin/admin.component';
import { AuthGuard } from './components/_guards/index';

// Public
import {LoginMerchantComponent}  from './components/login-merchant/login-merchant.component';
import {RegisterMerchantComponent}  from './components/register-merchant/register-merchant.component';
import {HomeComponent} from './components/home/home.component';
import {CreateCategory}  from './components/create-category/create-category.component';
import {EditCategory}  from './components/edit-category/edit-category.component';
import {HistoryComponent} from './components/history/history.component';
import {ReportComponent} from './components/report/report.component';
import {MenuComponent} from './components/menu/menu.component';
import {DishComponent} from './components/dish/dish.component';
import {SettingComponent} from './components/setting/setting.component';
import {SetupComponent} from './components/pages/setup/setup.component';
import {SetupStep2Component} from './components/pages/setup-step2/setup-step2.component';
import {SetupStep3Component} from './components/pages/setup-step3/setup-step3.component';
import {SetupStep4Component} from './components/pages/setup-step4/setup-step4.component';
import {SetupStep5Component} from './components/pages/setup-step5/setup-step5.component';
import { AddItemsToMenuComponent }  from './components/add-items-to-menu/add-items.component';

// Layouts
import { PublicLayoutComponent } from './components/layouts/public-layout.component';
import { LoginLayoutComponent } from './components/layouts/login-layout.component';
import { AdminLayoutComponent } from './components/layouts/admin-layout.component';

export const appRoutes: Routes = [
    
    // Public
    {
        path: '',
        redirectTo: 'home',
        pathMatch: 'full',
    },
    {
        path: '',
        component: PublicLayoutComponent,
        data: {
          title: 'Home'
        },
        children: [
          {
            path: 'home',
            component: HomeComponent
          },
          {
            path: 'register',
            component: RegisterMerchantComponent
          },
        ]
    },
    // Public After Login
    {
        path: '',
        component: PublicLayoutComponent,
        canActivate : [AuthGuard],
        data: {
          title: 'Home'
        },
        children: [
          // {
          //   path: 'home',
          //   component: HomeComponent
          // },
          {
            path:'history',
            component: HistoryComponent
          },
          {
              path:'report',
              component: ReportComponent
          },
          {
              path:'menu',
              component: MenuComponent
          },
          {
              path:'add-items-to-menu',
              component: AddItemsToMenuComponent
          },
          {
              path:'dish',
              component: DishComponent
          },
          {
              path:'step1',
              component: SetupComponent
          },
          {
              path:'step2',
              component: SetupStep2Component
          },
          {
              path:'step3',
              component: SetupStep3Component
          },
          {
              path:'step4',
              component: SetupStep4Component
          },
          // {
          //     path:'step5',
          //     component: SetupStep5Component
          // },
          {
              path:'settings',
              component: SettingComponent
          },
          {
              path:'create-category',
              component: CreateCategory
          },
          {
              path:'edit-category',
              component: EditCategory
          },
        ]
    },
    // STEP 5 FULL PAGE
    {
        path:'step5',
        component: SetupStep5Component
    },
    // Admin
    {
      path: 'admin',
      component: AdminLayoutComponent,
      canActivate : [AuthGuard],
      data: {
        title: 'Admin'
      },
      children: [
        {
          path: '',
          component: AdminComponent,
        },
        {
          path: 'merchant',
          component: MerchantComponent,
        },
      ]
    },
    // Login Admin
    {
      path: 'admin/login',
      component: LoginComponent,
      data: {
        title: 'Admin'
      },
    },
    // Login Merchant
    {
      path: 'login',
      component: LoginMerchantComponent,
      data: {
        title: 'Admin'
      },
    },
    // Reset Password
    {
      path: 'reset-password',
      component: ResetPasswordComponent,
      data: {
        title: 'Admin'
      },
    }
];

@NgModule({
  imports: [ RouterModule.forRoot(appRoutes) ],
  exports: [ RouterModule ]
})
export class AppRoutingModule {}
