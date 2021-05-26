<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SportsController;
use App\Http\Controllers\JobTypeController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\ChampionshipController;
use App\Http\Controllers\BeltExamController;

use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;



use App\Http\Controllers\SupplierController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\CafeteriaController;
use App\Http\Controllers\UnionsController;
use App\Http\Controllers\PurchaseController;

use App\Models\UserRole;
use App\Models\Modules;
use App\Models\Notifications;
use App\Models\MembersRenewHistory;
use App\Models\Members;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/save-history', function() {
  $get_mem = Members::get();

  foreach ($get_mem as $key) 
  {
                  MembersRenewHistory::insert(array(
                              "member_id"         => $key['id'],
                              "total_amount"        => $key['total_amount'],
                              "discount"    => $key['discount'],
                              "grand_total"   => $key['grand_total'],
                              "paid_amount"   => $key['paid_amount'],
                              "remaining_amount"  => $key['remaining_amount'],
                              "payment_date"  => $key['payment_date'],
                              "created_at"=> $key['created_at'],
                              "updated_at"=> $key['updated_at'],
                  ));
  }
});




Route::get('/clear-cache', function() {
   \Artisan::call('cache:clear');
   \Artisan::call('config:cache');
   \Artisan::call('config:clear');
   \Artisan::call('view:clear');
   \Artisan::call('route:clear');
});



Route::get('/lang/{locale}',[AdminController::class,'ChangeLanguage'])->name('lang');


Route::get('/MB-Script',function(){
		
	UserRole::where('user_type',0)->delete();

	$modules = Modules::get();
	foreach ($modules as $key) 
	{	

		UserRole::insert(array(
				'user_type'	=> 0,
				'module_id'		=> $key['id'],
		));
	}
});



Route::get('/', function () {
    return view('login');
})->name('index')->middleware('CheckLogin');

Route::post('/login',[AdminController::class,'Login'])->name('login');


Route::get('/logout',[AdminController::class,'Logout'])->name('logout');

Route::get('/forgot-password/{email}',[AdminController::class,'ForgotPassword'])->name('forgot-password');
Route::get('/reset-password/{token}',[AdminController::class,'ResetPassword'])->name('reset-password');

Route::post('/change-password',[AdminController::class,'ChangePassword'])->name('change-password');


Route::middleware(['AdminLogin','CheckRole'])->group(function () 
{
	Route::get('/dashboard',[AdminController::class,'Dashboard'])->name('dashboard');
	Route::post('/dashboard-filter',[AdminController::class,'DashboardFilter'])->name('dashboard-filter');



	//Sports Routes
	Route::get('/sports/add-sport',[SportsController::class,'AddSport'])->name('sports.add-sport');
	Route::post('/sports/save-sport',[SportsController::class,'SaveSport'])->name('sports.save-sport');
	Route::get('/sports/edit-sport/{id}',[SportsController::class,'EditSport'])->name('sports.edit-sport');
	Route::post('/sports/update-sport',[SportsController::class,'UpdateSport'])->name('sports.update-sport');
	Route::get('/sports/view-sport',[SportsController::class,'ViewSport'])->name('sports.view-sport');
	Route::get('/sports/delete-sport/{id}',[SportsController::class,'DeleteSport'])->name('sports.delete-sport');
	

	//Job Type Routes
	Route::get('/job_type/add-job',[JobTypeController::class,'AddJob'])->name('job_type.add-job');
	Route::post('/job_type/save-job',[JobTypeController::class,'SaveJob'])->name('job_type.save-job');
	Route::get('/job_type/edit-job/{id}',[JobTypeController::class,'EditJob'])->name('job_type.edit-job');
	Route::post('/job_type/update-job',[JobTypeController::class,'UpdateJob'])->name('job_type.update-job');
	Route::get('/job_type/view-job',[JobTypeController::class,'ViewJob'])->name('job_type.view-job');
	Route::get('/job_type/delete-job/{id}',[JobTypeController::class,'DeleteJob'])->name('job_type.delete-job');




	//Employee Routes
	Route::get('/employee/add-employee',[EmployeeController::class,'AddEmployee'])->name('employee.add-employee');
	Route::post('/employee/save-employee',[EmployeeController::class,'SaveEmployee'])->name('employee.save-employee');
	Route::get('/employee/edit-employee/{id}',[EmployeeController::class,'EditEmployee'])->name('employee.edit-employee');
	Route::post('/employee/update-employee',[EmployeeController::class,'UpdateEmployee'])->name('employee.update-employee');
	Route::post('/employee/delete-files',[EmployeeController::class,'DeleteEmployeeFiles'])->name('employee.delete-files');
	Route::get('/employee/view-employee',[EmployeeController::class,'ViewEmployee'])->name('employee.view-employee');
	Route::get('/employee/delete-employee/{id}',[EmployeeController::class,'DeleteEmployee'])->name('employee.delete-employee');
	Route::get('/employee/details-employee/{id}',[EmployeeController::class,'DetailsEmployee'])->name('employee.details-employee');
	Route::post('/employee/save-salary',[EmployeeController::class,'SaveEmployeeSalary'])->name('employee.save-salary');
	Route::post('/employee/update-salary',[EmployeeController::class,'UpdateEmployeeSalary'])->name('employee.update-salary');
	Route::get('/employee/view-salary',[EmployeeController::class,'ViewEmployeeSalary'])->name('employee.view-salary');
	Route::get('/employee/details-salary/{id}',[EmployeeController::class,'DetailsEmployeeSalary'])->name('employee.details-salary');
	Route::get('/employee/delete-salary/{id}',[EmployeeController::class,'DeleteEmployeeSalary'])->name('employee.delete-salary');
	Route::post('/employee/filter-salary',[EmployeeController::class,'FilterEmployeeSalary'])->name('employee.filter-salary');

	Route::post('/employee/filter-employee',[EmployeeController::class,'FilterEmployee'])->name('employee.filter-employee');
	Route::get('/employee/pending-salary',[EmployeeController::class,'PendingSalaryEmployee'])->name('employee.pending-salary');
	Route::get('/employee/id-expiry',[EmployeeController::class,'IdExpiryEmployee'])->name('employee.id-expiry');
	Route::post('/employee/filter-expiry',[EmployeeController::class,'FilterEmployeeExpiry'])->name('employee.filter-expiry');
	Route::post('/employee/filter-pendings',[EmployeeController::class,'FilterEmployeePendings'])->name('employee.filter-pendings');



	
	//For Coaches
	Route::get('/coaches/view-coaches',[EmployeeController::class,'ViewCoaches'])->name('coaches.view-coaches');
	Route::get('/coaches/view-coaches-time-table/{id}',[EmployeeController::class,'ViewCoachesTimeTable'])->name('coaches.view-coaches-time-table');




	


	//Leave Routes
	Route::get('/leave/view-leave',[LeaveController::class,'ViewLeave'])->name('leave.view-leave');
	Route::get('/leave/submit-leave',[LeaveController::class,'SubmitLeave'])->name('leave.submit-leave');
	Route::post('/leave/save-leave',[LeaveController::class,'SaveLeave'])->name('leave.save-leave');
	Route::get('/leave/names-employee/{name}',[LeaveController::class,'EmployeeNamesList'])->name('leave.names-employee');
	Route::get('/leave/edit-leave/{id}',[LeaveController::class,'EditLeave'])->name('leave.edit-leave');
	Route::post('/leave/update-leave',[LeaveController::class,'UpdateLeave'])->name('leave.update-leave');

	Route::get('/leave/delete-leave/{id}',[LeaveController::class,'DeleteLeave'])->name('leave.delete-leave');
	Route::get('/leave/details-leave/{id}',[LeaveController::class,'DetailsLeave'])->name('leave.details-leave');

	Route::post('/leave/filter-leave',[LeaveController::class,'FilterLeave'])->name('leave.filter-leave');

	// Route::get('/leave/remaining-employee/{id}',[LeaveController::class,'RemainingEmployees'])->name('leave.remaining-employee');




	//Classes Routes
	Route::get('/classes/add-classes',[ClassesController::class,'AddClasses'])->name('classes.add-classes');
	Route::post('/classes/save-classes',[ClassesController::class,'SaveClasses'])->name('classes.save-classes');
	Route::get('/classes/edit-classes/{id}',[ClassesController::class,'EditClasses'])->name('classes.edit-classes');
	Route::post('/classes/update-classes',[ClassesController::class,'UpdateClasses'])->name('classes.update-classes');
	Route::get('/classes/view-classes',[ClassesController::class,'ViewClasses'])->name('classes.view-classes');
	Route::post('/classes/get-classes',[ClassesController::class,'GetClasses'])->name('classes.get-classes');
	Route::get('/classes/view-time-table/{class_id}',[ClassesController::class,'ViewTimeTable'])->name('classes.view-time-table');
	Route::get('/classes/delete-time-table/{id}',[ClassesController::class,'DeleteTimeTable'])->name('classes.delete-time-table');
	Route::get('/classes/delete-classes/{id}',[ClassesController::class,'DeleteClasses'])->name('classes.delete-classes');
	Route::get('/classes/names-coach/{sport_id}',[ClassesController::class,'CoachNamesList'])->name('classes.names-coach');










		//Members Routes
	Route::get('/register/add-register',[MemberController::class,'AddMember'])->name('register.add-register');
	Route::post('/register/save-register',[MemberController::class,'SaveMember'])->name('register.save-register');
	Route::get('/register/edit-register/{id}',[MemberController::class,'EditMember'])->name('register.edit-register');
	Route::get('/register/renew-page',[MemberController::class,'RenewPage'])->name('register.renew-page');
	Route::get('/register/renew-register-form/{id}',[MemberController::class,'RenewMemberForm'])->name('register.renew-register-form');
	Route::post('/register/update-register',[MemberController::class,'UpdateMember'])->name('register.update-register');
	Route::post('/register/renew-register',[MemberController::class,'RenewMember'])->name('register.renew-register');
	
	Route::post('/register/delete-files',[MemberController::class,'DeleteMemberFiles'])->name('register.delete-files');

	Route::get('/register/view-register',[MemberController::class,'ViewMember'])->name('register.view-register');



	Route::get('/register/expired-members',[MemberController::class,'ExpiredMembers'])->name('register.expired-members');
	Route::post('/register/filter-expired',[MemberController::class,'FilterExpiredMember'])->name('register.filter-expired');

	Route::get('/register/pending-payments',[MemberController::class,'PendingPayments'])->name('register.pending-payments');
	Route::post('/register/filter-pendings',[MemberController::class,'FilterPendingPayments'])->name('register.filter-pendings');





	Route::get('/register/delete-register/{id}',[MemberController::class,'DeleteMember'])->name('register.delete-register');
	Route::get('/register/get-classes-duration-list/{sport_id}/{coach_id}',[MemberController::class,'ClassesDurationList'])->name('register.get-classes-duration-list');
	Route::get('/register/get-classes-days-list/{classes_id}',[MemberController::class,'ClassesDaysList'])->name('register.get-classes-days-list');
	Route::post('/register/get-classes-time-list',[MemberController::class,'ClassesTimeList'])->name('register.get-classes-time-list');
	Route::post('/register/get-classes-fee',[MemberController::class,'ClassesFee'])->name('register.get-classes-fee');
	Route::get('/register/details-member/{id}',[MemberController::class,'DetailsMember'])->name('register.details-member');
	Route::post('/register/filter-register',[MemberController::class,'FilterMember'])->name('register.filter-register');
	Route::get('/register/print-register/{id}',[MemberController::class,'PrintInvoice'])->name('register.print-register');
	Route::get('/register/print-member-details/{id}',[MemberController::class,'PrintMemberDetails'])->name('register.print-member-details');


	Route::get('/register/add-championship',[ChampionshipController::class,'AddChampionship'])->name('register.add-championship');
	Route::post('/register/save-championship',[ChampionshipController::class,'SaveChampionship'])->name('register.save-championship');
	Route::get('/register/edit-championship/{id}',[ChampionshipController::class,'EditChampionship'])->name('register.edit-championship');
	Route::post('/register/update-championship',[ChampionshipController::class,'UpdateChampionship'])->name('register.update-championship');
	Route::get('/register/view-championship',[ChampionshipController::class,'ViewChampionship'])->name('register.view-championship');
	Route::post('/register/filter-championship',[ChampionshipController::class,'FilterChampionship'])->name('register.filter-championship');
	Route::get('/register/delete-championship/{id}',[ChampionshipController::class,'DeleteChampionship'])->name('register.delete-championship');

	Route::get('/register/names-member/{name}',[ChampionshipController::class,'MemberNamesList'])->name('register.names-member');
	Route::get('/register/details-championship/{id}',[ChampionshipController::class,'DetailsChampionship'])->name('register.details-championship');



	Route::get('/register/add-belt-exam',[BeltExamController::class,'AddBeltExam'])->name('register.add-belt-exam');
	Route::post('/register/save-belt-exam',[BeltExamController::class,'SaveBeltExam'])->name('register.save-belt-exam');
	Route::get('/register/edit-belt-exam/{id}',[BeltExamController::class,'EditBeltExam'])->name('register.edit-belt-exam');
	Route::post('/register/update-belt-exam',[BeltExamController::class,'UpdateBeltExam'])->name('register.update-belt-exam');
	Route::get('/register/view-belt-exam',[BeltExamController::class,'ViewBeltExam'])->name('register.view-belt-exam');
	Route::post('/register/filter-belt-exam',[BeltExamController::class,'FilterBeltExam'])->name('register.filter-belt-exam');

	Route::get('/register/delete-belt-exam/{id}',[BeltExamController::class,'DeleteBeltExam'])->name('register.delete-belt-exam');
	Route::get('/register/details-belt-exam/{id}',[BeltExamController::class,'DetailsBeltExam'])->name('register.details-belt-exam');















	Route::get('/reports/profit-loss',[ReportController::class,'ProfitLossReport'])->name('reports.profit-loss');
	Route::post('/reports/filter-profit-loss',[ReportController::class,'FilterProfitLossReport'])->name('reports.filter-profit-loss');
	
	Route::get('/reports/members-registration',[ReportController::class,'MembersRegistrationReport'])->name('reports.members-registration');
	Route::post('/reports/filter-members-registration',[ReportController::class,'FilterMembersRegistrationReport'])->name('reports.filter-members-registration');
	
	Route::get('/reports/union-registration',[ReportController::class,'UnionRegistrationReport'])->name('reports.union-registration');
	Route::post('/reports/filter-union-registration',[ReportController::class,'FilterUnionRegistrationReport'])->name('reports.filter-union-registration');
	
	Route::get('/reports/championship-registration',[ReportController::class,'ChampionshipRegistrationReport'])->name('reports.championship-registration');
	Route::post('/reports/filter-championship-registration',[ReportController::class,'FilterChampionshipRegistrationReport'])->name('reports.filter-championship-registration');
	Route::post('/reports/filter-sales',[ReportController::class,'FilterSalesReport'])->name('reports.filter-sales');

















	// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
	Route::get('/expenses/add-expenses',[ExpenseController::class,'AddExpense'])->name('expenses.add-expenses');
	Route::post('/expenses/save-expenses', [ExpenseController::class,'SaveExpense'])->name('expenses.save-expenses');

	Route::get('/expenses/edit-expenses/{id}', [ExpenseController::class,'EditExpense'])->name('expenses.edit-expenses');
	Route::post('/expenses/update-expenses', [ExpenseController::class,'UpdateExpense'])->name('expenses.update-expenses');
	Route::get('/expenses/details-expenses/{id}', [ExpenseController::class,'DetailsExpense']);

	Route::get('/expenses/get-beneficiary-names/{id}/{val}', [ExpenseController::class,'GetBeneficiaryNames']);
	Route::get('/expenses/delete-expenses/{id}',[ExpenseController::class,'DeleteExpense'])->name('expenses.delete-expenses');


	Route::get('/expenses/view-expenses',[ExpenseController::class,'ViewExpense'])->name('expenses.view-expenses');
	
	Route::post('/expenses/filter-expenses', [ExpenseController::class,'FilterExpense'])->name('expenses.filter-expenses');
	Route::post('/expenses/delete-files',[ExpenseController::class,'DeleteExpensesFiles'])->name('expenses.delete-files');




	Route::get('/mark_notification_read/{id}',function($id){
			
			Notifications::where('id',$id)->update(array('is_new'=>0));
			return true;	
		});
	

	Route::get('/expenses/expense-setting',[ExpenseController::class,'ExpenseSetting'])->name('expenses.expense-setting');

	Route::post('/expenses/save-beneficiary', [ExpenseController::class,'SaveBeneficiary'])->name('expenses.save-beneficiary');
	Route::get('/expenses/delete-beneficiary/{id}',[ExpenseController::class,'DeleteBeneficiary'])->name('expenses.delete-beneficiary');
	Route::post('/expenses/update-expense-type', [ExpenseController::class,'UpdateExpenseType'])->name('expenses.update-expense-type');

	Route::get('/expenses/delete-expense-type/{id}',[ExpenseController::class,'DeleteExpenseType'])->name('expenses.delete-expense-type');

	// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
	// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$
	// $$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$








	Route::get('/account/user-account',[SettingController::class,'UserAccount'])->name('account.user-account');
	Route::post('/account/save-account-setting',[SettingController::class,'SaveAccountSetting'])->name('account.save-account-setting');
	Route::post('/account/change-account-password',[SettingController::class,'ChangeAccountPassword'])->name('account.change-account-password');

	Route::get('/setting/general-setting',[SettingController::class,'GeneralSetting'])->name('setting.general-setting');
	Route::post('/setting/save-general-setting',[SettingController::class,'SaveGeneralSetting'])->name('setting.save-general-setting');







	//Supplier Routes
	//Form Loading Route
	Route::get('/supplier/add-supplier',[SupplierController::class,'AddSupplier'] )->name('supplier.add-supplier');
	Route::get('/supplier/supplier-list',[SupplierController::class,'SupplierList'])->name('supplier.supplier-list');
	Route::get('/supplier/edit-supplier/{id}',[SupplierController::class, 'EditSupplier'])->name('supplier.edit-supplier');
	Route::get('/supplier/supplier-detail/{id}', [SupplierController::class, 'SupplierDetail'])->name('supplier.supplier-details');
	//Data Submit Routes
	Route::get('/supplier/delete-supplier/{id}',[SupplierController::class, 'DeleteSupplier'])->name('supplier.delete-supplier');
	Route::post('/supplier/update-supplier',[SupplierController::class, 'UpdateSupplier'])->name('supplier.update-supplier');
	Route::post('/supplier/create-supplier',[SupplierController::class, 'CreateSupplier'])->name('supplier.create-supplier');
	Route::post('supplier/search-supplier', [SupplierController::class, 'SearchSupplier'])->name('supplier.search-supplier');
	//Purchasing Routes
	//Form loading Routes
	Route::get('/purchase/add-items', [PurchaseController::class, 'AddItems'])->name('purchase.add-items');
	Route::get('/purchase/view-purchase-items', [PurchaseController::class, 'ViewPurchaseItems'])->name('purchase.view-purchase-items');
	Route::post('/purchase/search-purchasing-items', [PurchaseController::class, 'SearchPurchaseItems'])->name('purchase.search-purchasing-item');
	Route::get('/purchase/add-stock', [PurchaseController::class, 'AddStock'])->name('purchase.add-new-stock');
	Route::post('/purchase/save-stock', [PurchaseController::class, 'SaveStock'])->name('purchase.save-stock');
	//Data Submit Routes
	Route::post('/purchase/save-items', [PurchaseController::class, 'SaveItems'])->name('purchase.save-items');
	//Store Routes
	//Form Loading Route
	Route::get('/store/add-items',[StoreController::class,'AddItems'] )->name('store.add-items');
	Route::get('/store/view-items',[StoreController::class,'ViewItems'] )->name('store.view-items');
	Route::get('/store/edit-items/{id}',[StoreController::class, 'EditItems'])->name('store.edit-items');
	Route::get('/store/sales-add',[StoreController::class, 'SalesAdd'])->name('store.sales-add');
	Route::get('/store/sales-view',[StoreController::class, 'SalesView'])->name('store.sales-view');
	Route::get('/store/sales-edit/{id}',[StoreController::class, 'SalesEdit'])->name('store.sales-edit');
	Route::get('/store/sales-delete/{id}', [StoreController::class, 'DeleteSales'])->name('store.sales-delete');
	Route::get('/store/items-name/{name}', [StoreController::class, 'ItemsName'])->name('store.items-name');
	Route::post('/store/search-all-sales', [StoreController::class, 'SearchAllSales'])->name('store.search-all-sales');
	Route::post('/store/search-all-purchasing-item', [StoreController::class, 'SearchAllPurchasingItem'])->name('store.view-all-purchasing-item');
	Route::get('/store/view-stock', [StoreController::class, 'StockViewItems'])->name('store.view-all-stock');
	//Data Submit Routes
	Route::post('/store/create-items',[StoreController::class, 'CreateItems'])->name('store.create-items');
	Route::post('/store/update-items',[StoreController::class, 'UpdateItems'])->name('store.update-items');
	Route::get('/store/delete-items/{id}',[StoreController::class, 'DeleteItems'])->name('store.delete-items');
	Route::get('/store/delete-item-image/{id}',[StoreController::class, 'DeleteItemsImage'])->name('store.delete-item-image');
	Route::post('/store/sales-save',[StoreController::class, 'SalesSave'])->name('store.sales-save');
	Route::post('/store/sales-update',[StoreController::class, 'SalesUpdate'])->name('store.sales-update');
	//AJax Call
	Route::post('/store/sales-ajax',[StoreController::class, 'SalesAjax'])->name('store.sales-ajax');



		//Cafeteria Routes
	Route::get('/cafeteria/add-cafeteria',[CafeteriaController::class,'AddCafeteria'] )->name('cafeteria.add-cafeteria');
	Route::post('/cafeteria/save-user',[CafeteriaController::class,'SaveUser'] )->name('cafeteria.save-user');
	Route::get('/cafeteria/delete-user/{id}',[CafeteriaController::class,'DeleteUser'] )->name('cafeteria.delete-user');
	Route::get('/cafeteria/all-purchasing-data',[CafeteriaController::class,'PurchasingData'] )->name('cafeteria.purchasing-data');
	Route::get('/cafeteria/edit-items/{id}', [CafeteriaController::class, 'EditItems'])->name('cafeteria.edit-items');
	Route::get('/cafeteria/sales-view', [CafeteriaController::class, 'SalesView'])->name('cafeteria.sales-view');
	Route::get('/cafeteria/delete-sales/{id}', [CafeteriaController::class, 'DeleteSales'])->name('cafeteria.sales-delete');
	Route::post('/cafeteria/serach-data', [CafeteriaController::class, 'SearchCafeteria'] )->name('cafeteria.search-cafeteria');
	Route::post('/cafeteria/serach-stock-data', [CafeteriaController::class, 'SearchStockCafeteria'] )->name('cafeteria.search-stock-cafeteria');
	ROute::post('/cafeteria/search-purchasing-item', [CafeteriaController::class, 'SearchPurchaseItem'])->name('cafeteria.search-purchasing-item');
	Route::get('/cafeteria/sales-edit/{id}', [CafeteriaController::class, 'SalesEdit'])->name('cafeteria.sales-edit');
	Route::post('/cafeteria/sales-update', [CafeteriaController::class, 'SalesUpdate'])->name('cafeteria.sales-update');
	Route::get('/cafeteria/members-name/{name}', [CafeteriaController::class, 'MembersName'])->name('cafeteria.members-name');
	Route::get('cafeteria/stock-view', [CafeteriaController::class, 'AllStock'])->name('cafeteria.all-stock-view');
	//Forms Data Routes
	Route::post('/cafeteria/save-cafeteria', [CafeteriaController::class, 'SaveCafeteria'])->name('cafeteria.save-cafeteria');
	Route::post('/cafeteria/update-items', [CafeteriaController::class, 'UpdateItems'])->name('cafeteria.update-items');




	//Unions Routes...
	//Unions Form Routes
	Route::get('/unions/add-unions',[UnionsController::class,'AddUnions'] )->name('unions.add-unions');
	Route::get('/unions/delete-union/{id}',[UnionsController::class,'DeleteUnions'] )->name('unions.delete-union');
	Route::get('/unions/edit-union/{id}',[UnionsController::class,'EditUnions'] )->name('unions.edit-union');
	Route::post('/unions/update-unions', [UnionsController::class, 'UpdateUnions'])->name('unions.update-unions');

	Route::get('/unions/view-unions', [UnionsController::class, 'ViewUnions'])->name('unions.view-unions');
	//Unions Forms Data Routes
	Route::post('/unions/save-unions', [UnionsController::class, 'SaveUnions'])->name('unions.save-unions');
	Route::get('/unions/receive-payment', [UnionsController::class, 'ReceivePayment'])->name('unions.receive-payment');
	Route::get('/unions/view-payment', [UnionsController::class, 'ViewAllPayment'])->name('unions.view-payment');
	Route::post('/unions/filter-payment', [UnionsController::class, 'FilterAllPayment'])->name('unions.filter-payment');

	Route::post('/unions/save-receive-payment', [UnionsController::class, 'SaveReceivePayment'])->name('unions.save-receive-payment');

	Route::get('/unions/edit-receive-payment/{id}', [UnionsController::class, 'EditReceivePayment'])->name('unions.edit-receive-payment');
	Route::get('/unions/delete-receive-payment/{id}', [UnionsController::class, 'DeleteReceivePayment'])->name('unions.delete-receive-payment');

	Route::post('/unions/update-receive-payment', [UnionsController::class, 'UpdateReceivePayment'])->name('unions.update-receive-payment');

















































	//Only Access By SuperAdmin
	Route::middleware(['OnlySuperAdmin'])->group(function () 
	{
		//User Routes
		Route::get('/user/add-type',[UserController::class,'AddUserType'])->name('user.add-type');
		Route::post('/user/save-type',[UserController::class,'SaveUserType'])->name('user.save-type');
		Route::get('/user/edit-type/{id}',[UserController::class,'EditUserType'])->name('user.edit-type');
		Route::post('/user/update-type',[UserController::class,'UpdateUserType'])->name('user.update-type');
		Route::get('/user/delete-type/{id}',[UserController::class,'DeleteUserType'])->name('user.delete-type');
		Route::get('/user/type-list',[UserController::class,'UserTypeList'] )->name('user.type-list');
		
		Route::get('/user/add-user',[UserController::class,'AddUser'] )->name('user.add-user');
		Route::post('/user/save-user',[UserController::class,'SaveUser'] )->name('user.save-user');
		Route::get('/user/delete-user/{id}',[UserController::class,'DeleteUser'] )->name('user.delete-user');
		Route::get('/user/user-list',[UserController::class,'UserList'] )->name('user.user-list');

		Route::get('/user/user-roles',[UserController::class,'UserRoles'] )->name('user.user-roles');
		Route::post('/user/save-roles',[UserController::class,'SaveUserRoles'])->name('user.save-roles');
		Route::get('/user/get-user-roles/{type}',[UserController::class,'GetUserRoles'] )->name('user.get-user-roles');
	});


		

});