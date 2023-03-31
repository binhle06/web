<?php

include('header_admin.php');

?>
 <div class="Exam_List_Ct">
	<h1 id="Header_Examlist">Question List: <?php
        print($_SESSION['TempId']);
    ?></h1>
	
	<table id="course_table" class="table table-bordered table-striped">
            <thead bgcolor="#6cd8dc">
                        <tr class="table-primary">
                            <th width="5%">Question Id</th>
                           <th width="45%">Question</th>
                           <th width="40%">Answer</th>
                           <th scope="col" width="5%">Edit</th>
                           <th scope="col" width="5%">Delete</th>
                        </tr>
            </thead>
		</table>
					<div align="right">
        <button type="button" id="add_button" data-toggle="modal" data-target="#userModal"
		class="btn btn-success">Add Question</button>
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



<div class="modal" id="questionModal">
  	<div class="modal-dialog modal-lg">
    	<form method="post" id="question_form">
      		<div class="modal-content">
      			<!-- Modal Header -->
        		<div class="modal-header">
          			<h4 class="modal-title" id="question_modal_title"></h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>

        		<!-- Modal body -->
        		<div class="modal-body">
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Question Title <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="question_detail" id="question_detail" autocomplete="off" class="form-control" />
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Option 1 <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="option_detail_1" id="option_detail_1" autocomplete="off" class="form-control" />
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Option 2 <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="option_detail_2" id="option_detail_2" autocomplete="off" class="form-control" />
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Option 3 <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="option_detail_3" id="option_detail_3" autocomplete="off" class="form-control" />
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Option 4 <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="text" name="option_detail_4" id="option_detail_4" autocomplete="off" class="form-control" />
	                		</div>
            			</div>
          			</div>
          			<div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right">Answer <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<select name="answer_option" id="answer_option" class="form-control">
	                				<option value="">Select</option>
	                				<option value="1">1 Option</option>
	                				<option value="2">2 Option</option>
	                				<option value="3">3 Option</option>
	                				<option value="4">4 Option</option>
	                			</select>
	                		</div>
            			</div>
          			</div>
        		</div>

	        	<!-- Modal footer -->
	        	<div class="modal-footer">
	        		<input type="hidden" name="question_id" id="question_id" />

	        		<input type="hidden" name="TestId" id="hidden_TestId" />

	        		<input type="hidden" name="admin" value="Question_list" />

	        		<input type="hidden" name="admin_action" id="admin_action" value="Add" />

	        		<input type="submit" name="question_button_action" id="question_button_action" class="btn btn-success btn-sm" value="Add" />

	          		<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
	        	</div>
        	</div>
    	</form>
  	</div>
</div>



<script type="text/javascript" language="javascript">

$(document).ready(function(){
	
   

	function reset_question_form()
	{	
		$('#question_modal_title').text('Add Queston');
		$('#question_button_action').val('Add');
		$('#admin_action').val('Add');
		$('#question_form')[0].reset();
		$('#question_form').parsley().reset();
		$('#question_button_action').removeAttr("disabled");
	}

	$('#add_button').click(function(){
		reset_question_form();
		$('#questionModal').modal('show');
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
			data:{admin_action:'fetch', admin:'Question_list'}
        },
        "columnDefs":[
           {
            "targets":[0,3,4],
            "orderable":false,
           },
        ],
     });


     $('#question_form').parsley();

	$('#question_form').on('submit', function(event){
		event.preventDefault();

		$('#question_detail').attr('required', 'required');

		$('#option_detail_1').attr('required', 'required');

		$('#option_detail_2').attr('required', 'required');

		$('#option_detail_3').attr('required', 'required');

		$('#option_detail_4').attr('required', 'required');

		$('#answer_option').attr('required', 'required');

		if($('#question_form').parsley().validate())
		{
			$.ajax({
				url:"../DatabaseConn/Ajax_func.php",
				method:"POST",
				data:$(this).serialize(),
				dataType:"json",
				beforeSend:function(){
					$('#question_button_action').attr('disabled', 'disabled');

					$('#question_button_action').val('Validate...');
				},
				success:function(data)
				{
					if(data.success)
					{
			
						reset_question_form();

						dataTable.ajax.reload();

						$('#questionModal').modal('hide');
					}

				
				}
			});
		}
	});

	


	var question_id = '';

	$(document).on('click', '.edit', function(){
		question_id = $(this).attr('id');

		reset_question_form();

		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{admin_action:'edit_fetch', question_id:question_id, admin:'Question_list'},
			dataType:"json",
			success:function(data)
			{
				$('#question_detail').val(data.question_detail);
				$('#option_detail_1').val(data.option_detail_1);
				$('#option_detail_2').val(data.option_detail_2);
				$('#option_detail_3').val(data.option_detail_3);
				$('#option_detail_4').val(data.option_detail_4);
				$('#answer_option').val(data.answer_option);
				$('#question_id').val(question_id);
				$('#question_modal_title').text('Edit Question Details');

				$('#admin_action').val('Edit');

				$('#question_button_action').val('Edit');

				$('#questionModal').modal('show');
			}
		})
	});




	$(document).on('click', '.delete', function(){
		question_id = $(this).attr('id');
		$('#deleteModal').modal('show');
	});

	$('#ok_button').click(function(){
		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{question_id:question_id, admin_action:'delete', admin:'Question_list'},
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