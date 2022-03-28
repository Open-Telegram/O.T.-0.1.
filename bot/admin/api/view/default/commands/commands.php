<div class="row">
	<div class="commands col-sm-12">
		<form action="?route=commands/save" method="POST">
			<div class="commandWrap">
				<h2 style="color: white;">Commands</h2>
				<?php $commandLastId = 0; ?>
				<?php foreach($commands as $key => $command): ?>
					<div class="panel panel-default" data-id="<?=$command['command_id'];?>"  id="panelComm<?=$command['command_id'];?>">
						<div class="panel-heading">
							<?php if ($command['command_type'] == 'on_my_message'): ?>
								<p class="inf"></p>
								<input type="text" value="<?=$command['command_text']; ?>" name="commands[<?=$command['command_id'];?>][command_text]">
								<select onchange="selectFunctional(<?=$command['command_id'];?>);" name="commands[<?=$command['command_id'];?>][command_type]">
									<option selected value="on_my_message">Response to a message I have defined</option>
									<option value="all_messages">Reply to all messages</option>
									<option value="new_user">Response To a new user (for groups)</option>
								</select>
							<?php elseif($command['command_type'] == 'all_messages'): ?>
								<p class="inf">Все сообщения</p>
								<input type="text" hidden value="all_messages" name="commands[<?=$command['command_id'];?>][command_text]">
								<select onchange="selectFunctional(<?=$command['command_id'];?>);" name="commands[<?=$command['command_id'];?>][command_type]">
									<option value="on_my_message">Response to a message I have defined</option>
									<option selected value="all_messages">Reply to all messages</option>
									<option value="new_user">Response To a new user (for groups)</option>
								</select>

							<?php elseif($command['command_type'] == 'new_user'): ?>
								<p class="inf">Новый пользователь</p>
								<input type="text" hidden value="new_user" name="commands[<?=$command['command_id'];?>][command_text]">
								<select onchange="selectFunctional(<?=$command['command_id'];?>);" name="commands[<?=$command['command_id'];?>][command_type]">
									<option value="on_my_message">Response to a message I have defined</option>
									<option value="all_messages">Reply to all messages</option>
									<option selected value="new_user">Response To a new user (for groups)</option>
								</select>
								
							<?php endif ?>
							<span onclick="$(this).parent().parent().remove();"><i>DELETE</i></span>
						</div>
						<div style="display: none;" class="panel-body">
							<p>Function</p>
							<select onchange="updateFunctionInputs($(this));" name="commands[<?=$command['command_id'];?>][function_name]">
									<option disabled>Select function</option>
								<?php foreach ($functions as $key => $function) :?>
									<option <?php if($command['function_name'] == $key){ echo ' selected ';}?> value="<?=$key;?>"><?=$function['name'];?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div  style="display: none;" class="panel-footer">
							<?php $return_data = json_decode($command['return_data'],true); ?>
							<?php echo $functions[$command['function_name']]['enter_html']; ?>
						</div>
					</div>
					<script>
						var ret_data = <?php echo ($command['return_data'] != null) ? $command['return_data'] : '{}';?>;

						<?php 
							$bh = '';
							foreach ($buttons as $button_) 
							{
								$bh .= '<option value="'.$button_['button_id'].'">'.$button_['button_name'].'</option>';
							}
						?>
						
						$('#panelComm<?=$command['command_id'];?>').find('.panel-footer').find('select[name=buttons]').html('<?=$bh;?>');
						$.each(ret_data,function(index,value)
							{
								if(index == 'buttons')
								{
									$.each(value,function(index2,CommID)
										{
											$('#panelComm<?=$command['command_id'];?>').find('.panel-footer').find('select[name='+index+'] option[value='+CommID+']').prop('selected', true);
										});
									$('#panelComm<?=$command['command_id'];?>').find('.panel-footer').find('[name='+index+']').attr('name','commands[<?=$command['command_id']?>][return_data]['+index+'][]');
								}else
								{
									$('#panelComm<?=$command['command_id'];?>').find('.panel-footer').find('textarea[name='+index+']').html(value);
									$('#panelComm<?=$command['command_id'];?>').find('.panel-footer').find('[name='+index+']').val(value);
									$('#panelComm<?=$command['command_id'];?>').find('.panel-footer').find('[name='+index+']').attr('name','commands[<?=$command['command_id']?>][return_data]['+index+']');
								}


							});
					$('#panelComm<?=$command['command_id'];?>').find('.panel-footer').find('[name=buttons]').attr('name','commands[<?=$command['command_id']?>][return_data][buttons][]');
					</script>
				<?php $commandLastId = $command['command_id']; ?>
				<?php endforeach; ?>
			</div>
			<button type="button" onclick="addCommand();" class="btn btn-success">+</button>
			<button type="success" class="btn btn-success">Save</button>
		</form>
	</div>
</div>

<script>
	
	var commandId = '<?=$commandLastId;?>';
	commandId++;
	<?php 
		$bh = '';
		foreach ($buttons as $button_) 
		{
			$bh .= '<option value="'.$button_['button_id'].'">'.$button_['button_name'].'</option>';
		}
	?>
	var buttonsHtmlOption = '<?=$bh;?>';

	function selectFunctional(id)
	{
		var type = $('#panelComm'+id+' .panel-heading select').val();
		console.log(type);
		if(type == 'all_messages')
		{
			$('#panelComm'+id).find('.panel-heading input').val('all_messages');
			$('#panelComm'+id).find('.panel-heading .inf').html('Все сообщения');
			$('#panelComm'+id).find('.panel-heading input').prop('hidden',true);
			$('.panel').each(function(index,value)
			{
				var thisId = $(this).attr('data-id');
				if(thisId != id)
				{
					$(this).remove();
				}
			});
		}else if(type == 'on_my_message')
		{
			$('#panelComm'+id).find('.panel-heading .inf').html('');
			$('#panelComm'+id).find('.panel-heading input').val('');
			$('#panelComm'+id).find('.panel-heading input').prop('hidden',false);

		}else if(type == 'new_user')
		{
			$('#panelComm'+id).find('.panel-heading input').val('new_user');
			$('#panelComm'+id).find('.panel-heading .inf').html('New user');
			$('#panelComm'+id).find('.panel-heading input').prop('hidden',true);
		}
	}

	function addCommand()
	{
		var html = "";

		html += '<div class="panel panel-default" data-id="'+commandId+'" id="panelComm'+commandId+'">';
		html += '<div class="panel-heading">';

		html += '<p class="inf"></p>'
		html += '<input type="text" value="" name="commands['+commandId+'][command_text]">'
		html += '<select onchange="selectFunctional('+commandId+');" name="commands['+commandId+'][command_type]">'
		html += '<option value="on_my_message">Response to a message I have defined</option>'
		html += '<option value="all_messages">Reply to all messages</option>'
		html += '<option value="new_user">Response To a new user (for groups)</option>'
		html += '</select>'
		html += '</div>';
		html += '<div style="display: none;" class="panel-body">';
		html += 	'<p>функция</p>';
		html += 	'<select onchange="updateFunctionInputs($(this));" name="commands['+commandId+'][function_name]">';
		html += `<option disabled>Select function</option>`;
		<?php foreach ($functions as $key => $function) :?>
			html += '<option value="<?=$key;?>"><?=$key;?></option>';
		<?php endforeach; ?>
		html += '</select>';
		html += "<scr"+"ipt>$('#panelComm"+commandId+"').find('.panel-body select').change();</scr"+"ipt>";
		html += '</div>';
		html += '<div  style="display: none;" class="panel-footer">';
		html += '</div>';
		html += '</div>';
		$('.commandWrap').append(html);
		$("#panelComm"+commandId).smkPanel({hide:'remove,full'});
		commandId++;
	}

	function updateFunctionInputs(_this,commands = true)
	{
		var html = '';
		var id =_this.parent().parent().attr('data-id');
		switch(_this.val()) 
		{
			<?php foreach ($functions as $key => $function): ?>
				case '<?=$key;?>':
					<?php 
					$ec = str_replace(array("\r\n", "\r", "\n"), ' ',$function['enter_html']);
					$ec = str_replace('<script>', "<scr'+'ipt>",$ec);
					$ec = str_replace('</script>', "</scr'+'ipt>",$ec);
					echo "html = '$ec';";
					?>

				break
			<?php endforeach ?>
		}
				_this.parent().parent().find('.panel-footer').html(html);

				$('#panelComm'+id).find('.panel-footer').find('select[name="buttons"]').html(buttonsHtmlOption);


				$('#panelComm'+id).find('.panel-footer').find('select').each(function()
				{
					var index = $(this).attr('name');
					if(index == 'buttons')
					{
						$(this).attr('name','commands['+id+'][return_data]['+index+'][]');
					}else
					{
						$(this).attr('name','commands['+id+'][return_data]['+index+']');
					}
				});

				$('#panelComm'+id).find('.panel-footer').find('input').each(function()
				{
					var index = $(this).attr('name');
					$(this).attr('name','commands['+id+'][return_data]['+index+']');
				});

				$('#panelComm'+id).find('.panel-footer').find('textarea').each(function()
				{
					var index = $(this).attr('name');
					$(this).attr('name','commands['+id+'][return_data]['+index+']');
		// console.log(index);
		// console.log(_this.parent().parent());
				});

		// html = html.replace(/commands[00000]/g, "buttons["+id+"]");
	}
</script>