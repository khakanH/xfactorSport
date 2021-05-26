<div id="LoadingModal" class="modal" data-backdrop="false" data-keyboard="true" style="height: 1%; background: rgba(0,0,0,0.6);">
          <center><img style="width: 200%;" src="<?php echo config('app.img_url') ?>loading_bar.gif" width="100%" height="20"></center>
</div>

<div class="" id="toast" style="visibility: hidden; position: fixed; bottom: 5px; left: 30px; z-index: 999999999; font-size: 15px;">
                                        <p id="toastMsg" style="float: left;"></p> 
                                            <button type="button" class="close" onclick="hideToast('toast')" aria-label="Close" style="float: right;"> &nbsp;&nbsp;&nbsp;<span aria-hidden="true">×</span> </button>
                                        </div>

<!-- MyModal -->
<!-- ------------------------------------------------ -->

<div id="MyModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="MyModalDialog">
        <div class="modal-content" id="MyModalContent">
           
                  <div class="modal-header">
                      <h4 class="modal-title" id="MyModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="MyModalData">

                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="MyModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{__('web.Close')}}</button>
                  </div>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ------------------------------------------------------------------ -->
<!-- -------------------------------------------------------------------- -->
<!-- ---------------------------------------------------------------------- -->



<!-- Employee Detail Modal -->
<!-- ------------------------------------------------ -->
<div id="EmployeeDetailModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="EmployeeDetailModalDialog">
        <div class="modal-content" id="EmployeeDetailModalContent">
           
                  <div class="modal-header">
                      <h4 class="modal-title" id="EmployeeDetailModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="EmployeeDetailModalData">


                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="EmployeeDetailModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{__('web.Close')}}</button>
                  </div>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ------------------------------------------------------------------ -->
<!-- -------------------------------------------------------------------- -->
<!-- ---------------------------------------------------------------------- -->





<!-- EmployeeSalary -->
<!-- ------------------------------------------------ -->

<div id="EmployeeSalaryModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="EmployeeSalaryModalDialog">
        <div class="modal-content" id="EmployeeSalaryModalContent">
            
            <form role="form"  method="POST" id="salary_from">
              @csrf
                  <div class="modal-header">
                      <h4 class="modal-title" id="EmployeeSalaryModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="EmployeeSalaryModalData">

                        <input type="hidden" name="salary_id" id="salary_id">
                        <input type="hidden" name="employee_id" id="employee_id">

                        <div class="form-group row">
                          <div class="col-md-6">
                            <label for="employee_name">{{__('web.Name')}}</label>
                            <input type="text" class="form-control" id="employee_name" name="employee_name" readonly="">
                          </div>
                          <div class="col-md-6">
                            <label for="employee_salary">{{__('web.Salary')}}</label>
                            <input type="number" class="form-control" id="employee_salary" name="employee_salary" readonly="">
                          </div>
                        </div>

                        <div class="form-group row">
                          <div class="col-md-6">
                            <label for="deduction">{{__('web.Deduction')}}</label>
                            <input type="number" class="form-control" id="deduction" name="deduction" onkeyup="CalculateTotalSalary()">
                          </div>
                          <div class="col-md-6">
                            <label for="honorarium">{{__('web.Honorarium')}}</label>
                            <input type="number" class="form-control" id="honorarium" name="honorarium" onkeyup="CalculateTotalSalary()">
                          </div>
                        </div>

                        <div class="form-group row">
                           <div class="col-md-6">
                            <label for="deduction_reason">{{__('web.Deduction Reason')}}</label>
                            <textarea class="form-control" rows="3" id="deduction_reason" name="deduction_reason"></textarea>
                          </div>
                          <div class="col-md-6">
                            <label for="honorarium_reason">{{__('web.Honorarium Reason')}}</label>
                            <textarea class="form-control" rows="3" id="honorarium_reason" name="honorarium_reason"></textarea>
                          </div>
                        </div>

                         <div class="form-group row">
                          <div class="col-md-6">
                            <label for="salary_date">{{__('web.Salary Date')}}</label>
                            <input type="month" class="form-control" id="salary_date" name="salary_date" required="">
                          </div>
                          <div class="col-md-6">
                            <label for="total_salary">{{__('web.Total Salary')}}</label>
                            <input type="number" class="form-control" id="total_salary" name="total_salary" readonly="">
                          </div>
                        </div>
                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="EmployeeSalaryModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{__('web.Close')}}</button>
                      <button type="submit" class="btn btn-primary waves-effect">{{__('web.Save')}}</button>
                  </div>


            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ------------------------------------------------------------------ -->
<!-- -------------------------------------------------------------------- -->
<!-- ---------------------------------------------------------------------- -->





<!-- Leave Detail Modal -->
<!-- ------------------------------------------------ -->
<div id="LeaveDetailModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="LeaveDetailModalDialog">
        <div class="modal-content" id="LeaveDetailModalContent">
           
                  <div class="modal-header">
                      <h4 class="modal-title" id="LeaveDetailModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="LeaveDetailModalData">


                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="LeaveDetailModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{__('web.Close')}}</button>
                  </div>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ------------------------------------------------------------------ -->
<!-- -------------------------------------------------------------------- -->
<!-- ---------------------------------------------------------------------- -->






<!-- ClassesTimeTable Modal -->
<!-- ------------------------------------------------ -->
<div id="ClassesTimeTableModal" class="modal fade show " tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="ClassesTimeTableModalDialog">
        <div class="modal-content" id="ClassesTimeTableModalContent">
           
                  <div class="modal-header">
                      <h4 class="modal-title" id="ClassesTimeTableModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="ClassesTimeTableModalData">


                                
                      </div>
              

                      </div>
                  <div class="modal-footer" id="ClassesTimeTableModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{__('web.Close')}}</button>
                  </div>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ------------------------------------------------------------------ -->
<!-- -------------------------------------------------------------------- -->
<!-- ---------------------------------------------------------------------- -->








<!-- Expense Type Modal -->
<!-- ------------------------------------------------ -->
<div id="ExpenseTypeModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog" id="ExpenseTypeModalDialog">
        <div class="modal-content" id="ExpenseTypeModalContent">
           
            <form method="post" action="{{route('expenses.update-expense-type')}}">
              @csrf
               <span class='arrow'>
              <label class='error'></label>
              </span>
                  <div class="modal-header">
                      <h4 class="modal-title" id="ExpenseTypeModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="ExpenseTypeModalData">

                        <input type="hidden" name="expense_type_id" id="expense_type_id">

                        <div class="form-group">
                        <label>{{__('web.Name')}}</label>
                        <input type="text" class="form-control" name="expense_type_name" id="expense_type_name" required="">
                        <br>
                         <label>{{__('web.Notification Period')}}</label>
                        <input required="" type="number" min="1" max="360" step="1" class="form-control" name="notification_period" id="notification_period">
                    </div>


                      </div>
                  </div>
                  <div class="modal-footer" id="ExpenseTypeModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{__('web.Close')}}</button>
                      <button type="submit" class="btn btn-success ">{{__('web.Save')}}</button>

                  </div>
            </form>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ------------------------------------------------------------------ -->
<!-- -------------------------------------------------------------------- -->
<!-- ---------------------------------------------------------------------- -->







<!-- Beneficiary Modal -->
<!-- ------------------------------------------------ -->
<div id="BeneficiaryModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="BeneficiaryModalLabel" aria-modal="true" aria-hidden="true">
    <div class="modal-dialog" id="BeneficiaryModalDialog">
        <div class="modal-content" id="popup_modal_content">

            <form method="post" action="{{route('expenses.save-beneficiary')}}">
                @csrf
            <div class="modal-header">
                <h4 class="modal-title" id="BeneficiaryModalLabel"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body" id="BeneficiaryModalData">
                    
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="expense_id" id="expense_id">

                    <div class="form-group">
                        <label>{{__('web.Name')}}</label>
                        <input required="" type="text" class="form-control" name="name" id="name">
                    </div>


            </div>
            <div class="modal-footer" id="BeneficairyModalFooter">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">{{__('web.Close')}}</button>
                 <button type="submit" class="btn btn-success ">{{__('web.Save')}}</button>
            </div>

            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<!-- ------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------ -->




<!-- ExpenseDetailModal Modal -->
<!-- ------------------------------------------------------------------ -->
<div id="ExpenseDetailModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-modal="true" aria-hidden="true" style="color: black;">
    <div class="modal-dialog modal-lg" id="ExpenseDetailModalDialog">
        <div class="modal-content" id="ExpenseDetailModalContent">
           
                  <div class="modal-header">
                      <h4 class="modal-title" id="ExpenseDetailModalLabel"></h4>
                      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                  </div>
                  <div class="modal-body">
                      <div class="" id="ExpenseDetailModalData">

                       


                      </div>
                  </div>
                  <div class="modal-footer" id="ExpenseDetailModalFooter">
                      <button type="button" class="btn btn-danger waves-effect" data-dismiss="modal">{{__('web.Close')}}</button>

                  </div>


        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- ------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------ -->
<!-- ------------------------------------------------------------------ -->








































































<script type="text/javascript">
   $(function () {
    $(document).on('click', '[data-toggle="lightbox"]', function(event) {
      event.preventDefault();
      $(this).ekkoLightbox({
        alwaysShowClose: true
      });
    });

    $('.filter-container').filterizr({gutterPixels: 3});
    $('.btn[data-filter]').on('click', function() {
      $('.btn[data-filter]').removeClass('active');
      $(this).addClass('active');
    });
  })
</script>
