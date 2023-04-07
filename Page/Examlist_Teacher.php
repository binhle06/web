<?php

include('header_teacher.php');

?>
 <div class="Exam_List_Ct">
	<h1 id="Header_Examlist">Exam List</h1>
	
	<table id="course_table" class="table table-bordered table-striped">
            <thead bgcolor="#6cd8dc">
                        <tr class="table-primary">
						<th width="5%">Test ID</th>
                           <th width="30%">Test Name</th>
                           <th width="5%">Question Total</th>
                           <th width="5%">Time limit</th>
						   <th width="15%">Arthor</th>
						   <th width="5%">Status</th>
                           <th scope="col" width="5%">Edit</th>
                           <th scope="col" width="5%">Delete</th>
						   <th scope="col" width="5%">Question</th>
						   <th scope="col" width="10%">Change Status</th>
						   <th scope="col" width="10%">View Student</th>

                        </tr>
            </thead>
		</table>
					<div align="right">
        <button type="button" id="add_button" data-toggle="modal" data-target="#userModal"
		class="btn btn-success">Add Test</button>
    </div>
	<br>
</div>



<div class="modal" id="formModal">
  	<div class="modal-dialog modal-lg">
    	<form method="post" id="exam_form">
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
              				<label class="col-md-4 text-right">Exam ID <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="TestId" id="TestId" class="form-control" />
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Exam Title <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="TestName" id="TestName" class="form-control" />
	                		</div>
            			</div>
          			</div>
					  <div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Total Question <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<select name="total_question" id="total_question" class="form-control">
	                				<option value="">Select</option>
	                				<option value="5">5 Question</option>
	                				<option value="10">10 Question</option>
	                				<option value="25">25 Question</option>
	                				<option value="50">50 Question</option>
	                				<option value="100">100 Question</option>
	                				<option value="200">200 Question</option>
	                				<option value="300">300 Question</option>
	                			</select>
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Exam Duration <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<select name="online_exam_duration" id="online_exam_duration" class="form-control">
	                				<option value="">Select</option>
	                				<option value="5">5 Minute</option>
	                				<option value="30">30 Minute</option>
	                				<option value="60">1 Hour</option>
	                				<option value="120">2 Hour</option>
	                				<option value="180">3 Hour</option>
	                			</select>
	                		</div>
            			</div>
          			</div>
          			
        		</div>

	        	<!-- Modal footer -->
	        	<div class="modal-footer">
	        		<input type="hidden" name="online_exam_id" id="online_exam_id" />

	        		<input type="hidden" name="teacher" value="exam_list" />

	        		<input type="hidden" name="teacher_action" id="teacher_action" value="Add" />

	        		<input type="submit" name="button_action" id="button_action" class="btn btn-success btn-sm" value="Add" />

	          		<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
	        	</div>
        	</div>
    	</form>
  	</div>
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




<div class="modal" id="ChangeModal">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<!-- Modal Header -->
      		<div class="modal-header">
        		<h4 class="modal-title">Changing Status Confirmation</h4>
        		<button type="button" class="close" data-dismiss="modal">&times;</button>
      		</div>

      		<!-- Modal body -->
      		<div class="modal-body">
        		<h3 align="center">Are you sure you want to do this?</h3>
      		</div>

      		<!-- Modal footer -->
      		<div class="modal-footer">
      			<button type="button" name="ok_button2" id="ok_button2" class="btn btn-primary btn-sm">OK</button>
        		<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
      		</div>
    	</div>
  	</div>
</div>





<script type="text/javascript" language="javascript">

$(document).ready(function(){
	
   
    function reset_form()
	{
		$('#modal_title').text('Add Exam Details');
		$('#button_action').val('Add');
		$('#teacher_action').val('Add');
		$('#exam_form')[0].reset();
		$('#exam_form').parsley().reset();
		$('#button_action').removeAttr("disabled");
	}

	$('#add_button').click(function(){
		reset_form();
		$('#formModal').modal('show');
		$('#message_operation').html('');
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
			data:{teacher_action:'fetch', teacher:'exam_list'}
        },
        "columnDefs":[
           {
            "targets":[0,6,7,8,9],
            "orderable":false,
           },
        ],
     });


     $('#exam_form').parsley();

	$('#exam_form').on('submit', function(event){
		event.preventDefault();

		$('#TestId').attr('required', 'required');

		$('#TestName').attr('required', 'required');

		$('#total_question').attr('required', 'required');

		$('#online_exam_duration').attr('required', 'required');

		if($('#exam_form').parsley().validate())
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
						$('#message_operation').html('<div class="alert alert-success">'+data.success+'</div>');
						reset_form();

						dataTable.ajax.reload();

						$('#formModal').modal('hide');
					}

				
				}
			});
		}
	});

	


	var TestId = '';

	$(document).on('click', '.edit', function(){
		TestId = $(this).attr('id');

		reset_form();

		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{teacher_action:'edit_fetch', TestId:TestId, teacher:'exam_list'},
			dataType:"json",
			success:function(data)
			{
				$('#TestId').val(data.TestId);

				$('#TestName').val(data.TestName);

				$('#total_question').val(data.Question_total);

				$('#online_exam_duration').val(data.Time_limit_minute);

				$('#modal_title').text('Edit Exam Details');

				$('#button_action').val('Edit');

				$('#teacher_action').val('Edit');

				$('#formModal').modal('show');
			}
		})
	});




	$(document).on('click', '.delete', function(){
		TestId = $(this).attr('id');
		$('#deleteModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{TestId:TestId, teacher_action:'delete', teacher:'exam_list'},
			dataType:"json",
			success:function(data)
			{
	
				$('#deleteModal').modal('hide');
				dataTable.ajax.reload();
			}
		})
	});	


	
	$(document).on('click', '.dark', function(){
		TestId = $(this).attr('id');
		$('#ChangeModal').modal('show');
	});


	$('#ok_button2').click(function(){
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{TestId:TestId, teacher_action:'change', teacher:'exam_list'},
			dataType:"json",
			success:function(data)
			{
	
				$('#ChangeModal').modal('hide');
				dataTable.ajax.reload();
			}
		})
	});	


	$(document).on('click', '.view', function(){
		TestId = $(this).attr('id');
		console.log(TestId);


		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{teacher_action:'To_Question', TestId:TestId, teacher:'exam_list'},
			dataType:"json",
			success:function(data)
			{
				location.href = "QuestionList_Teacher.php";
			}
		})
		
		
	});


	$(document).on('click', '.info', function(){
		TestId = $(this).attr('id');
		console.log(TestId);


		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{teacher_action:'To_Result', TestId:TestId, teacher:'exam_list'},
			dataType:"json",
			success:function(data)
			{
				location.href = "ResultList_Teacher.php";
			}
		})
		
		
	});	
	
		



});

</script>
</body>
</html>
