

	<div class="users_wrap">
	<?php foreach($users as $key=> $user):?>
		
		<div class="users_item_wrap">
		<div class="users_item">
			<div class="users_title">
				<div class="users_name"><?=$user['name']?></div>
				<div class="users_role">NUMBER OF REQUESTS - <?=$user['message_count']?></div>
				<div class="users_role">
					<form method="POST" action="?route=users/send_message">
						<input hidden type="text" name='telegram_id' value="<?=$user['telegram_id']?>" >

						<input hidden type="text" name='message'><br>
						<button class="btn-sm btn-success">Send a message</button>
					</form>
					
				</div>
				
			</div>
	     	
		</div>
		</div>
		
	<?php endforeach;?>
    </div>
 <script>    
 	$('form').submit(function (e) {
 		var message = $(this).find('input[name=message]');
 		// var id = $(this).find('input[name=telegram_id]');
 		if(message.val() == '')
 		{
 			e.preventDefault();
 			message.prop('hidden',false);
 		}
	}); 
 </script>

