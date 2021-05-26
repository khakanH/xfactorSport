@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.View Classes')}}</h1>
          </div>
        </div>
      </div>
</section>

  	<section class="content">
    	<div class="container-fluid">
          
              @if(session('success'))
                <div class="alert alert-success alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{session('success')}}
                </div>
                {{ session()->forget('success') }}
              @elseif(session('failed'))
                <div class="alert alert-danger alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  {{session('failed')}}
                </div>
                {{ session()->forget('failed') }}
              @endif

              @if ($errors->any())
              <div class="callout callout-danger">
                  <h5>Form Submission Errors</h5>
                  @foreach ($errors->all() as $error)
                   <li class="text-danger">{{$error}}</li>
                  @endforeach
              </div>
              @endif


              <form method="post" name="classesForm" id="classesForm">
              <div class="row">
              @csrf
              <div class="col-md-2"></div>
                <div class="col-md-3">
                  <label for="sport">{{__('web.Sports')}}</label>
                  <select class="form-control" name="sport" id="sport" required="" onchange="GetCoachList(this.value)">
                    <option value="">{{__('web.Select')}}</option>
                    @foreach($sport as $key)
                      <option value="{{$key['id']}}">{{$key['name']}}</option>
                    @endforeach
                  </select>
                </div>

                <div class="col-md-3">
                  <label for="coach">{{__('web.Coach')}}</label>
                  <select class="form-control" name="coach" required="" id="coach">
                    <option value="">{{__('web.Select')}}</option>
                  </select>
                </div>

                <div class="col-md-2">
                  <label for="">&nbsp;</label><br>
                  <button type="submit" class="btn btn-success">{{__('web.Filter')}}</button>
                </div>
              
              <div class="col-md-2"></div>

              </div>
              </form>
              <br>



            <div class="card" id="class-result">

              <div class="card-body p-4 table-responsive">
                  <table class="table table-striped CommonDataTables">
                  <thead>
                    <tr>
                      <th width="5%">#</th>
                      <th width="10%"><?php echo __('web.Sports') ?></th>
                      <th width="10%"><?php echo __('web.Coach') ?></th>
                      <th width="10%"><?php echo __('web.Duration') ?></th>
                      <th width="10%"><?php echo __('web.Time Table') ?></th>
                      <th style="text-align: center;" width="20%"><?php echo __('web.Action') ?></th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php $count = 1; foreach($classes as $key): ?>
                    <tr>
                      <td><?php echo $count ?></td>
                      <td><?php echo $key->sport_name->name ?></td>
                      <td><?php echo $key->coach_name->name ?></td>
                      <td><?php echo $key['duration'] ?></td>
                      <td><a href="javascript:void(0)" onclick='GetClassesTimeTable("<?php echo $key['id'] ?>")'><?php echo __('web.View')?></a></td>
                      <td style="text-align: center;"><a title="<?php echo __('web.Edit') ?>" data-toggle="tooltip" href="<?php echo route('classes.edit-classes',[$key['id']]) ?>"><i class="fa fa-edit text-primary"></i></a>&nbsp;&nbsp;&nbsp;&nbsp;<a title="<?php echo __('web.Delete') ?>" data-toggle="tooltip" onclick="return confirm('<?php echo __("web.Are you sure?") ?>')" href="<?php echo route('classes.delete-classes',[$key['id']]) ?>"><i class="fa fa-trash text-danger"></i></a></td>
                    </tr>
                  <?php $count++; endforeach; ?>
                  </tbody>
                  </table>
                </div>

            </div>




            




        </div>
    </section>
  	

</div>

<script type="text/javascript">


function GetCoachList(sport_id)
{
  if (!sport_id) 
  {
    $('#coach').html('<option value="">{{__("web.Select")}}</option');
    return false;
  }

   $.ajax({
            type: "GET",
            cache: false,
            async: false,
            url: "{{ config('app.url')}}classes/names-coach/"+sport_id,
            beforeSend: function(){
                           
                        },
            success: function(data) {
                            $('#coach').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });
}

function GetClassesTimeTable(class_id)
{ 

    $('#ClassesTimeTableModal').modal('show');
    $('#ClassesTimeTableModalLabel').html('{{__("web.Time Table")}}');



   
   $.ajax({
            type: "GET",
            cache: false,
            async: false,
            url: "{{ config('app.url')}}classes/view-time-table/"+class_id,
            beforeSend: function(){
                            $('#ClassesTimeTableModalData').html('<center><i class="fas fa-2x fa-sync-alt fa-spin"></i></center>');
                        },
            success: function(data) {
                            $('#ClassesTimeTableModalData').html(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Exception:' + errorThrown);
            }
        });

    document.getElementById('ClassesTimeTableModal').style.backgroundColor="rgba(0,0,0,0.8)";
    document.getElementById('ClassesTimeTableModalDialog').style.paddingTop="0px";

}




    $(function() {
      $("form[name='classesForm']").validate({
        submitHandler: function(form) {
          let myForm = document.getElementById('classesForm');
          let formData = new FormData(myForm);
          $.ajax({
            type: "POST",
            url: "{{ config('app.url')}}classes/get-classes",
            data: formData,
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function(){
                                $('#class-result').html('<br><center><i class="fas fa-2x fa-sync-alt fa-spin"></i></center><br>');
            },
            success: function(data) {
                                $('#class-result').html(data);
            }
          });

        }
      });
    });

</script>

@endsection
