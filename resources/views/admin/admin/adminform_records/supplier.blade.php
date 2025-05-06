@extends('admin.dashboard.layouts.app')

@section('content')
<!-- begin:: Content -->
<div class="kt-content  kt-grid__item kt-grid__item--fluid" id="kt_content">

	<!--Begin::Dashboard 1-->


	<!--Begin::Section-->
	<div class="row">
		<div class="col-xl-12 col-lg-12">
			<h2>Suppliers</h2>
		</div>
	</div>
	<section id="procedure_section">

		<div class="row">
			<div class="col-lg-12">
					<p>To add a record, complete the form below and select the add button. To amend a record, fill in the form with the correct record number and include the new data and select update. You do need to complete the whole form.</p>
               
                    <div class="procedure_div">
                    	<div class="requirments_table_div">
                    		<h4>Total Customers Listed</h4>
                    		<div class="kt-portlet__body table-responsive">
								<!--begin: Datatable -->
								<table class="table table-striped- table-bordered table-hover table-checkable" id="kt_table_agent">
									<thead>
										<tr>
											<th class="w-100-px">Supplier ID</th>
											<th>Name</th>
											<th>Address</th>
											<th>City</th>
											<th>County</th>
											<th>Postcode</th>
											<th>Tel</th>
											<th>Email</th>
											<th>Contact</th>
											<th>Services</th>
											<th class="w-100-px">Action</th>
										</tr>
									</thead>
									<tbody>
                                        @foreach ($supplier as $data)


										<tr>
                                            <td>{{$data->id}}</td>
                                            <td>{{$data->suppliername}}</td>
											<td>{{$data->supplieraddress}}</td>
											<td>{{$data->suppliercity}}</td>
											<td>{{$data->suppliercountry}}</td>
											<td>{{$data->supplierzip}}</td>
											<td>{{$data->supplierphn}}</td>
											<td>{{$data->supplieremail}}</td>
                                            <td>{{$data->supplierContactNumber}}</td>
											<td>{{$data->supplierservc}}</td>
											<td>
											<button  class="btn btn-sm btn-clean btn-icon btn-icon-md" title="info" onclick="getEid({{$data}});"><i class="fa fa-eye"></i>
											</button>
											<button class="btn btn-sm btn-clean btn-icon btn-icon-md" title="Delete" onclick="deleteModal({{$data}});"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="18px" viewBox="0 0 24 24" version="1.1">										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">											<rect x="0" y="0" width="24" height="24"></rect>											<path d="M6,8 L6,20.5 C6,21.3284271 6.67157288,22 7.5,22 L16.5,22 C17.3284271,22 18,21.3284271 18,20.5 L18,8 L6,8 Z" fill="#5d78ff" fill-rule="nonzero"></path>											<path d="M14,4.5 L14,4 C14,3.44771525 13.5522847,3 13,3 L11,3 C10.4477153,3 10,3.44771525 10,4 L10,4.5 L5.5,4.5 C5.22385763,4.5 5,4.72385763 5,5 L5,5.5 C5,5.77614237 5.22385763,6 5.5,6 L18.5,6 C18.7761424,6 19,5.77614237 19,5.5 L19,5 C19,4.72385763 18.7761424,4.5 18.5,4.5 L14,4.5 Z" fill="#5d78ff" opacity="0.3"></path>										</g>									</svg>
											</button>
											</td>
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
<div class="modal fade" id="deleteSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Deleting Supplier</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<p>Are you sure?Do you really want to delete this?.</p>
			</div>
			<div class="modal-footer">
            <form action="{{route('deleteSupplierAdmin')}}" method="POST">
                @csrf
				<input type="hidden" name="id" id="re_id" value="">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
				<button type="submit" class="btn btn-danger">Yes</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="editSupplier" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="exampleModalLabel">Edit Supplier</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				</button>
			</div>
			<div class="modal-body">
				<form >
                    @csrf
                    <input type="hidden" name="id" id="id_feild" value="">

                    <div class="row">
                        {{-- <div class="col-lg-6">
                            <div class="form-group">
                                <label>ID Number:</label><br>
                                <input type="number" class="form-control" name="idnumber" placeholder="Enter ID:">
                            </div>
                        </div> --}}
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Supplier Name:</label><br>
                                <input type="text" class="form-control" name="suppliername" placeholder="Enter Supplier Name:">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Supplier Address:</label>
                                <input type="text" name="supplieraddress" class="form-control" placeholder="Enter Supplier Address:">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>City:</label>
                                <input type="text"  name="suppliercity" class="form-control" placeholder="Enter City:">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>County or State:</label>
                                <input type="text" name="supplierstate" class="form-control" placeholder="Enter Country or State:">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Post Code or Zip Code:</label>
                                <input type="text" name="supplierzip" class="form-control" placeholder="Enter Customer Contact Number:">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Country:</label>
                                <input type="text" name="suppliercountry"  class="form-control" placeholder="Enter Country">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Supplier Telephone:</label>
                                <input type="text" name="supplierphn" class="form-control" placeholder="Enter Supplier Telephone:">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Supplier Email::</label>
                                <input type="email" name="supplieremail" class="form-control" placeholder="Enter Supplier Email:">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label>Supplier Contact Name:</label>
                                <input type="text" name="supplierContactNumber" class="form-control" placeholder="Enter Supplier Contact Number:">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <label>Supplier Service:</label>
                                <input type="text" name="supplierservc" class="form-control" placeholder="Enter Supplier Service:">
                            </div>
                        </div>
                        </div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
            </div>
            </form>
		</div>
	</div>
</div>
@endsection

<script>
    function getEid(data){
        console.log(data);

         $("#id_feild").val(data.id);
         $("input[name='idnumber']").val(data.idnumber);
         $("input[name='supplierContactNumber']").val(data.supplierContactNumber);
         $("input[name='supplieraddress']").val(data.supplieraddress);
         $("input[name='suppliercity']").val(data.suppliercity);
         $("input[name='suppliercountry']").val(data.suppliercountry);
         $("input[name='supplieremail']").val(data.supplieremail);
         $("input[name='suppliername']").val(data.suppliername);
         $("input[name='supplierphn']").val(data.supplierphn);
         $("input[name='supplierservc']").val(data.supplierservc);
         $("input[name='supplierstate']").val(data.supplierstate);
         $("input[name='supplierzip']").val(data.supplierzip);
         $("#editSupplier").modal('show');
     }
     function deleteModal(data){
         $("#re_id").val(data.id);
         $("#deleteSupplier").modal('show');

     }
 </script>
