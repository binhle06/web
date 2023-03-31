<?php

include('header_admin.php');

?>
 <div class="Exam_List_Ct">
	<h1 id="Header_Examlist">User List</h1>
	
	<table id="course_table" class="table table-bordered table-striped">
            <thead bgcolor="#6cd8dc">
                        <tr class="table-primary">
							<th width="10%">Number</th>
                           <th width="30%">ID</th>
                           <th width="40%">Name</th>
                           <th width="10%">Occupation</th>
                           <th scope="col" width="5%">Edit</th>
                           <th scope="col" width="5%">Delete</th>
                        </tr>
            </thead>
		</table>
					<div align="right">
        <button type="button" id="add_button" data-toggle="modal" data-target="#userModal"
		class="btn btn-success">Add User</button>
    </div>
	<br>
</div>




<div class="modal" id="deleteModal">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<!-- Modal Header -->
      		<div class="modal-header">
        		<h4 class="modal-title">Delete Confirmation</h4>
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>

      		<!-- Modal body -->
      		<div class="modal-body">
        		<h3 align="center">Are you sure you want to delete this?</h3>
      		</div>

      		<!-- Modal footer -->
      		<div class="modal-footer">
      			<button type="button" name="ok_button" id="ok_button" class="btn btn-primary btn-sm">OK</button>
        		<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>



<div class="modal" id="formModal">
  	<div class="modal-dialog modal-lg">
    	<form method="post" id="user_form">
      		<div class="modal-content">
      			<!-- Modal Header -->
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title"></h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>

        		<!-- Modal body -->
        		<div class="modal-body">
					<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right"> ID <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="UserId" id="UserId" class="form-control" />
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right"> Name <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="UserFullname" id="UserFullname" class="form-control" />
	                		</div>
            			</div>
          			</div>
					  <div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right"> Password <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="password" name="pass" id="pass" class="form-control" />
	                		</div>
            			</div>
          			</div>
					  <div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">User Role <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<select name="role" id="role" class="form-control">
	                				<option value="">Select</option>
	                				<option value="Student">Student</option>
	                				<option value="Teacher">Teacher</option>
	                				<option value="Admin">Admin</option>
	                			</select>
	                		</div>
            			</div>
          			</div>
          			
        		</div>

	        	<!-- Modal footer -->
	        	<div class="modal-footer">
	        		<input type="hidden" name="online_exam_id" id="online_exam_id" />

	        		<input type="hidden" name="admin" value="User_list" />

	        		<input type="hidden" name="admin_action" id="admin_action" value="Add" />

	        		<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />

	          		<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
	        	</div>
        	</div>
    	</form>
  	</div>
</div>



<script type="text/javascript" language="javascript">

$(document).ready(function(){
	
   

	function reset_form()
	{
		$('#modal_title').text('Add Exam Details');
		$('#button_action').val('Add');
		$('#action').val('Add');
		$('#user_form')[0].reset();
		$('#user_form').parsley().reset();
		$('#button_action').removeAttr("disabled");
	}

	$('#add_button').click(function(){
		reset_form();
		$('#formModal').modal('show');
	});

	var dataTable = $('#course_table').DataTable({
        "paging":true,
        "processing":true,
        "serverSide":true,
        "order": [],
        "info":true,
        "ajax":{
            url:"../DatabaseConn/Ajax_func.php",
            type:"POST",
			data:{admin_action:'fetch', admin:'User_list'}
        },
        "columnDefs":[
           {
            "targets":[0,4,5],
            "orderable":false,
           },
        ],
     });


     $('#user_form').parsley();

	$('#user_form').on('submit', function(event){
		event.preventDefault();

		$('#UserId').attr('required', 'required');

		$('#UserFullname').attr('required', 'required');

		$('#pass').attr('required', 'required');

		$('#role').attr('required', 'required');


		if($('#user_form').parsley().validate())
		{
			$.ajax({
				url:"../DatabaseConn/Ajax_func.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function(){
					$('#button_action').attr('disabled', 'disabled');

					$('#button_action').val('Validate...');
				},
				success:function(data)
				{
					if(data.success)
					{
			
						reset_form();

						dataTable.ajax.reload();

						$('#formModal').modal('hide');
					}

				
				}
			});
		}
	});

	


	var user = '';

	$(document).on('click', '.edit', function(){
		user = $(this).attr('id');

		reset_form();

		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{admin_action:'edit_fetch', user:user, admin:'User_list'},
			dataType:"json",
			success:function(data)
			{
				$('#UserId').val(data.UserId);
				$('#UserFullname').val(data.UserFullname);
				$('#pass').val(data.pass);
				$('#role').val(data.role);

				$('#admin_action').val('Edit');

				$('#modal_title').text('Edit Account');

				$('#button_action').val('Edit');

				$('#formModal').modal('show');
			}
		})
	});




	$(document).on('click', '.delete', function(){
		user = $(this).attr('id');
		$('#deleteModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{user:user, admin_action:'delete', admin:'User_list'},
			dataType:"json",
			success:function(data)
			{
	
				$('#deleteModal').modal('hide');
				dataTable.ajax.reload();
			}
		})
	});



});

</script>
</body>
</html>