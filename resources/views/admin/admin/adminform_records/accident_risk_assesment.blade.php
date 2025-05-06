@extends('admin.dashboard.layouts.app')

@section('content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

	<!--Begin::Dashboard 1-->


	<!--Begin::Section-->
	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<h2>Accident Risk Assessments</h2>
		</div>
	</div>
	<section id="procedure_section">

		<div class="row">
			<div class="col-lg-12">
				<h5>Scope:</h5>
				<p>This procedure details possible scenarios of potential accidents and compares this with risk and consequence of such an accident occurring. It will also provide details as to what measures have been taken to reduce the risk of such accidents occurring.</p>
                    
                    <div class="procedure_div">
                    	<div class="requirments_table_div">
                    		<h4>Total Accident Risk Assessments Listed</h4>
                    		<div class="kt-portlet__body table-responsive">
								<!--begin: Datatable -->
								<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_agent">
									<thead>
										<tr>
											<th>Scenario</th>
											<th>Detail View</th>
											<th>Action</th>
										</tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($riskassesment as $data)
                                        <tr>
                                            <td> {{$data->activityscenario}}</td>
                                            <td> <button
                                                 onclick="getDetails({{json_encode($data)}})" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" value=""><i class="fa fa-eye"></i>
                                            </button></td>
                                            <td> <button onclick="deleteModal({{json_encode($data)}})" class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Edit" value=""><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">											<rect x="0" y="0" width="24" height="24"></rect>											<path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#5d78ff" fill-rule="nonzero"></path>											<path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#5d78ff" opacity="0.3"></path>										</g>									</svg>
                                        </button></td>


                                        </tr>

                                        @endforeach
                                    </tbody>
								</table>
								<!--end: Datatable -->
                    		</div>
						</div>
					</div>


	</section>

	<!--End::Section-->
</div>
<div class="modal fade" id="deleteRequirment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Deleting Accident Risk</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<p>Are you sure?Do you really want to delete this?.</p>
			</div>
			<div class="modal-footer">
            <form action="{{route('deleteRiskadmin')}}" method="POST">
				@csrf
				<input type="hidden" name="id" value="" id="idform">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
				<button type="submit" class="btn btn-danger">Yes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="editInfo" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Accident Risk Assessments</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
                <form >

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Scenario - Describe the activity:</label><br>
                                <input type="text" class="form-control" placeholder="Enter Activity" name="activityscenario">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Risk likelihood of scenario occuring - Enter a number between 1-6 (6 being most likely):</label><br>
                                <input type="number" class="form-control" placeholder="Enter likelihood" name="risklikehood">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Risk severity - Enter a number between 1-6 (6 being most severe):</label>
                                <input type="number" class="form-control" name="riskseverity" placeholder="Enter severity Level:">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>If an environmental accident, what gets out and how much:</label>
                                <input type="text" class="form-control" name="envaccident" placeholder="Enter Potential Outcome:">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>If an environmental accident, where does it end up?</label>
                                <input type="text" class="form-control" placeholder="Enter Location" name="envaccidental">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>What are the consequences?:</label>
                                <input type="text" class="form-control" placeholder="Enter Potential Consequences" name="consequences">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>What can prevent or reduce the risk?:</label>
                                <input type="text" class="form-control" c name="reducerisk">
                        </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Revised Risk likelihood of scenario occuring following prevention step - A number between 1-6 (6 being most likely):</label>
                                <input type="number" class="form-control" placeholder="Enter new reduced risk level" name="revisedrisk">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Revised Risk severity following prevention step - A number between 1-6 (6 being most severe):</label>
                                <input type="number" class="form-control" placeholder="Enter new reduced risk severity level" name="reviseRiskSever">
                            </div>
                        </div>
                    </div>
                </form>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="editmodalData" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Accident Risk Assessments</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
            </div>
            <form  action="{{route('accidentedit')}}" method="POST">
                @csrf
			<div class="modal-body">
                <input type="hidden" id="editrisk" name="id" value="">

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Scenario - Describe the activity:</label><br>
                                <input type="text" class="form-control" placeholder="Enter Activity" name="activityscenario">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Risk likelihood of scenario occuring - Enter a number between 1-6 (6 being most likely):</label><br>
                                <input type="number" class="form-control"  placeholder="Enter likelihood" name="risklikehood">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Risk severity - Enter a number between 1-6 (6 being most severe):</label>
                                <input type="number" class="form-control" name="riskseverity"  placeholder="Enter severity Level:">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>If an environmental accident, what gets out and how much:</label>
                                <input type="text" class="form-control" name="envaccident" placeholder="Enter Potential Outcome:">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>If an environmental accident, where does it end up?</label>
                                <input type="text" class="form-control" placeholder="Enter Location" name="envaccidental">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>What are the consequences?:</label>
                                <input type="text" class="form-control" placeholder="Enter Potential Consequences" name="consequences">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>What can prevent or reduce the risk?:</label>
                                <input type="text" class="form-control" placeholder="Enter Preventive Solutions" name="reducerisk">
                        </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Revised Risk likelihood of scenario occuring following prevention step - A number between 1-6 (6 being most likely):</label>
                                <input type="number" class="form-control" placeholder="Enter new reduced risk level" name="revisedrisk">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Revised Risk severity following prevention step - A number between 1-6 (6 being most severe):</label>
                                <input type="number" class="form-control" placeholder="Enter new reduced severity level" name="reviseRiskSever">
                            </div>
                        </div>
                    </div>

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancle</button>
            <button type="submit" class="btn btn-danger">Update</button>
        </div>
    </form>
	</div>
</div>
</div>
{{-- editmodalData --}}
@endsection

<script>
    function getDetails(data){
        console.log(data);
         $("#id_feild").val(data.id);
         $("input[name='riskseverity']").val(data.riskseverity);
         $("input[name='risklikehood']").val(data.risklikehood);
         $("input[name='revisedrisk']").val(data.revisedrisk);
         $("input[name='reviseRiskSever']").val(data.reviseRiskSever);
         $("input[name='reducerisk']").val(data.reducerisk);
         $("input[name='envaccidental']").val(data.envaccidental);
         $("input[name='envaccident']").val(data.envaccident);
         $("input[name='consequences']").val(data.consequences);
         $("input[name='activityscenario']").val(data.activityscenario);
         $("#editInfo").modal('show');

     }
     function Editinfo(data){
        $("#editrisk").val(data.id);
         $("input[name='riskseverity']").val(data.riskseverity);
         $("input[name='risklikehood']").val(data.risklikehood);
         $("input[name='revisedrisk']").val(data.revisedrisk);
         $("input[name='reviseRiskSever']").val(data.reviseRiskSever);
         $("input[name='reducerisk']").val(data.reducerisk);
         $("input[name='envaccidental']").val(data.envaccidental);
         $("input[name='envaccident']").val(data.envaccident);
         $("input[name='consequences']").val(data.consequences);
         $("input[name='activityscenario']").val(data.activityscenario);
         $("#editmodalData").modal('show');
     }
     function deleteModal(data){
         console.log(data);
         $("#idform").val(data.id);
         $("#deleteRequirment").modal('show');

     }


 </script>
