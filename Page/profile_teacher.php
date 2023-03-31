<?php

include('header_teacher.php');

$exam->query = "
	SELECT * FROM User_info 
	WHERE UserId = '".$_SESSION['UserId']."'
";

$result = $exam->query_result();
?>



    <section class="Sec_Pro">
        <div class="Sec_Pro_Tab">
        <h2>Personal Information</h2>
        <div class="Tab">         
            <?php
        		foreach($result as $row)
        		{
        	?>
            <table class="Profile-tab">
                <tr class="Profile-tab-tr">
                    <th class="Profile-tab-th">ID</th>
                    <td class="Profile-tab-td"> <?php echo $row["UserId"]; ?> </td>
                </tr>
                <tr class="Profile-tab-tr">
                    <th class="Profile-tab-th">Name</th>
                    <td class="Profile-tab-td"> <?php echo $row["UserFullname"]; ?></td>
                </tr>
                <tr class="Profile-tab-tr">
                    <th class="Profile-tab-th">Occupation</th>
                    <td class="Profile-tab-td"> <?php echo $row["User_role"]; ?></td>
                </tr>
                <tr class="Profile-tab-tr">
                    <th class="Profile-tab-th">Date Created</th>
                    <td class="Profile-tab-td"> <?php echo $row["Date_Created"]; ?></td>
                </tr>
            </table>
            <br>
            <div align="center">
        <button type="button" id="add_button" data-toggle="modal" data-target="#userModal"
		class="btn btn-success">Change password</button>
    </div>
            </div>
        <?php
			}
		?>
        </div>


		
    </section>



    <div class="modal" id="formModal">
  	<div class="modal-dialog modal-lg">
    	<form method="post" id="user_form">
      		<div class="modal-content">
      			<!-- Modal Header -->
        		<div class="modal-header">
          			<h4 class="modal-title" id="modal_title">Change password</h4>
          			<button type="button" class="close" data-dismiss="modal">&times;</button>
        		</div>

        		<!-- Modal body -->
        		<div class="modal-body">
					  <div class="form-group">
            			<div class="row">
              				<label class="col-md-4 text-right"> Password <span class="text-danger">*</span></label>
	              			<div class="col-md-8">
	                			<input type="password" name="pass" id="pass" class="form-control" />
	                		</div>
            			</div>
          			</div>      			
        		</div>

	        	<!-- Modal footer -->
	        	<div class="modal-footer">

	        		<input type="hidden" name="teacher" value="Profile_list" />

	        		<input type="hidden" name="teacher_action" id="teacher_action" value="Edit" />

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
		$('#modal_title').text('Edit Password');
		$('#button_action').val('Edit');
		$('#teacher_action').val('Edit');
		$('#user_form')[0].reset();
		$('#user_form').parsley().reset();
		$('#button_action').removeAttr("disabled");
	}

	


     $('#user_form').parsley();

	$('#user_form').on('submit', function(event){
		event.preventDefault();

		$('#pass').attr('required', 'required');

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

	



	$(document).on('click', '#add_button', function(){

		reset_form();

		$.ajax({
			url:"../DatabaseConn/Ajax_func.php",
			method:"POST",
			data:{teacher_action:'edit_fetch', teacher:'Profile_list'},
			dataType:"json",
			success:function(data)
			{

				$('#pass').val(data.pass);

				$('#modal_title').text('Edit Password');

				$('#button_action').val('Edit');

				$('#teacher_action').val('Edit');

				$('#formModal').modal('show');
			}
		})
	});


});


</script>



</body>
</html>


