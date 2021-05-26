@extends('layouts.app')
@section('content')


<div class="content-wrapper">

<section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{__('web.User Roles')}}</h1>
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



            <div class="card p-4">

                <div class="form-group row">
                  <div class="col-lg-3">
                   <label>{{__('web.Select')}}</label>
                      <select class="form-control" id="user_type_list" onchange="GetUserRoles(this.value)">
                          @foreach($user_type as $key)
                          <option <?php if ($type == $key['id']): ?>
                            selected
                          <?php endif ?> value="{{$key['id']}}">{{$key['name']}}</option>
                          @endforeach
                      </select>
                  </div>
                </div>
                      



       <form name="userRolesForm" id="userRolesForm" method="post" action="{{route('user.save-roles')}}">
                                    @csrf

      <div class="user-data">
        

                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <!-- <td width="2%">
                                                        <label class="au-checkbox">
                                                            <input type="checkbox">
                                                            <span class="au-checkmark"></span>
                                                        </label>
                                                    </td> -->
                                                    <th width="35%">{{__('web.Modules')}}</th>
                                                    <th width="35%">{{__('web.Sub-Modules')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody style="font-size: 14px;" id="user_roleTBody">
                                              <input type="hidden" name="user_type_id" id="user_type_id" value="{{$type}}">
                                            @for($i = 0; $i < count($all_modules); $i++)
                                                <tr id="user_role{{$all_modules[$i]['id']}}">
                                                    <td><li><input onclick='ToggleAllSubModule("{{$all_modules[$i]['id']}}")' id="main_module-cb{{$all_modules[$i]['id']}}"  type="checkbox" name="main_module-cb[]" value="{{$all_modules[$i]['id']}}" <?php if (in_array($all_modules[$i]['id'], $user_role)): ?>
                                                        checked=""
                                                        <?php else: ?>
                                                    <?php endif ?>>&nbsp;&nbsp;&nbsp;{{__("web.".$all_modules[$i]['name'])}}</li></td>
                                                    <td> @for($j = 0 ; $j < count($all_sub_modules[$i]); $j++)  
                                                           <li><input onclick='ToggleMainModule("{{$all_sub_modules[$i][$j]['id']}}","{{$all_modules[$i]['id']}}")' value="{{$all_sub_modules[$i][$j]['id']}}" class="sub_module-cb{{$all_modules[$i]['id']}}" id="sub_module-cb{{$all_sub_modules[$i][$j]['id']}}" type="checkbox" name="main_module-cb[]" <?php if (in_array($all_sub_modules[$i][$j]['id'], $user_role)): ?>
                                                        checked=""
                                                    <?php endif ?>>&nbsp;&nbsp;&nbsp;{{__("web.".$all_sub_modules[$i][$j]['name'])}}</li>
                                                       @endfor</td>
                                                </tr>
                                            @endfor
                                            </tbody>
                                        </table>

        </div>
                                        <br>
                                        <button class="btn btn-primary" style="float: right; width: 20%;" type="submit">{{__('web.Save')}}</button>
                                        <br>
                                        <br>

        </form>







            </div>
        </div>
    </section>
  	

</div>

<script type="text/javascript">
     function ToggleAllSubModule(id)
   {    
        check = document.getElementById("main_module-cb"+id);

   

    if (check.checked == true) 
    {
        $('.sub_module-cb'+id).each(function() {
                this.checked = true; 
            }); 
    }
    else
    {
        $('.sub_module-cb'+id).each(function() {
                this.checked = false; 
            });   
    }

   }

   function ToggleMainModule(sub_id,main_id)
   {    

        check = document.getElementById("sub_module-cb"+sub_id);

        
        if (check.checked == true) 
        {
            document.getElementById("main_module-cb"+main_id).checked = true;   
        }
        
   }


   function GetUserRoles(type)
   {
                location.href = "{{ config('app.url')}}user/get-user-roles/"+type;

   }

</script>

@endsection
