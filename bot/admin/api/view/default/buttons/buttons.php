<div class="row">
	<div class="col-sm-12">
		<form action="?route=buttons/save" method="POST">
			<div class="buttonsWrap">
				<?php $buttonLastId = 0; ?>
				<h2 style="color: white;">Buttons</h2>
					<?php foreach($buttons as $key => $button): ?>
						<div class="panel panel-default"  data-id="<?=$button['button_id'];?>" id="panelButt<?=$button['button_id'];?>">
							<div class="panel-heading">
								<input type="text" value="<?=$button['button_name']; ?>" name="buttons[<?=$button['button_id']?>][button_name]">
								<span onclick="$(this).parent().parent().remove();"><i>DELETE</i></span>
							</div>
							<div style="display: none;" class="panel-body">
								<p>Function</p>
								<select onchange="updateFunctionInputs($(this),false);" name="buttons[<?=$button['button_id']?>][function_name]">
									<option disabled>Select function</option>
									<?php foreach ($functions as $key => $function) :?>
										<option <?php if($button['function_name'] == $key){ echo ' selected ';}?> value="<?=$key;?>"><?=$function['name'];?></option>
									<?php endforeach; ?>
								</select>
							</div>
							<div  style="display: none;" class="panel-footer">
								<?php $return_data = json_decode($button['return_data'],true); ?>
								<?php echo  str_replace(array("\r\n", "\r", "\n"), ' ',$functions[$button['function_name']]['enter_html']); ?>
							</div>
						</div>
						<script>
							var ret_data = <?php echo ($button['return_data'] != null) ? $button['return_data'] : '{}';?>;
							<?php 
								$bh = '';
								foreach ($buttons as $button_) 
								{
									$bh .= '<option value="'.$button_['button_id'].'">'.$button_['button_name'].'</option>';
								}
							?>
							
							$('#panelButt<?=$button['button_id'];?>').find('.panel-footer').find('select[name=buttons]').html('<?=$bh;?>');
							$.each(ret_data,function(index,value)
								{
									if(index == 'buttons')
									{
										$.each(value,function(index2,butID)
											{
												$('#panelButt<?=$button['button_id'];?>').find('.panel-footer').find('select[name='+index+'] option[value='+butID+']').prop('selected', true);
												
											});
										$('#panelButt<?=$button['button_id'];?>').find('.panel-footer').find('[name='+index+']').attr('name','buttons[<?=$button['button_id']?>][return_data]['+index+'][]');
									}else
									{
										$('#panelButt<?=$button['button_id'];?>').find('.panel-footer').find('textarea[name='+index+']').html(value);
										$('#panelButt<?=$button['button_id'];?>').find('.panel-footer').find('[name='+index+']').val(value);
										$('#panelButt<?=$button['button_id'];?>').find('.panel-footer').find('[name='+index+']').attr('name','buttons[<?=$button['button_id']?>][return_data]['+index+']');
									}


								});
						</script>
				<?php $buttonLastId = $button['button_id']; ?>
				<?php endforeach; ?>
			</div>
			<button onclick="addButton()" type="button" class="btn btn-success">+</button>
			<button type="success" class="btn btn-success"> Save </button>
		</div>
	</form>
</div>

<script>
	
	var buttonId = '<?=$buttonLastId;?>';
	buttonId++;

	<?php 
		$bh = '';
		foreach ($buttons as $button_) 
		{
			$bh .= '<option value="'.$button_['button_id'].'">'.$button_['button_name'].'</option>';
		}
	?>
	var buttonsHtmlOption = '<?=$bh;?>';
	function addButton() {
		var html = "";

		html += '<div class="panel panel-default" data-id="'+buttonId+'" id="panelButt'+buttonId+'">';
		html += '<div class="panel-heading">';
		html += '<input type="text" value="" name="buttons['+buttonId+'][button_name]"><span onclick="$(this).parent().parent().remove();"><i>DELETE</i></span>';
		html += '</div>';
		html += '<div style="display: none;" class="panel-body">';
		html += 	'<p>функция</p>';
		html += 	'<select onchange="updateFunctionInputs($(this),false);" name="buttons['+buttonId+'][function_name]">';
		html += `<option disabled>Select function</option>`;
		<?php foreach ($functions as $key => $function) :?>
			html += '<option value="<?=$key;?>"><?=$key;?></option>';
		<?php endforeach; ?>
		html += '</select>';
		html += '</div>';
		html += '<div  style="display: none;" class="panel-footer">';
		html += '</div>';
		html += "<scr"+"ipt>$('#panelButt"+buttonId+"').find('select').change();</scr"+"ipt>";
		html += '</div>';
		$('.buttonsWrap').append(html);
		$("#panelButt"+buttonId).smkPanel({hide:'remove,full'});
		buttonId++;
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

				break;
			<?php endforeach ?>
		}
				_this.parent().parent().find('.panel-footer').html(html);

				$('#panelButt'+id).find('.panel-footer').find('select[name="buttons"]').html(buttonsHtmlOption);


				$('#panelButt'+id).find('.panel-footer').find('select').each(function()
				{
					var index = $(this).attr('name');
					if(index == 'buttons')
					{
						$(this).attr('name','buttons['+id+'][return_data]['+index+'][]');
					}else
					{
						$(this).attr('name','buttons['+id+'][return_data]['+index+']');
					}
				});

				$('#panelButt'+id).find('.panel-footer').find('input').each(function()
				{
					var index = $(this).attr('name');
					$(this).attr('name','buttons['+id+'][return_data]['+index+']');
				});

				$('#panelButt'+id).find('.panel-footer').find('textarea').each(function()
				{
					var index = $(this).attr('name');
					$(this).attr('name','buttons['+id+'][return_data]['+index+']');
		// console.log(_this.parent().parent());
				});

		// html = html.replace(/commands[00000]/g, "buttons["+id+"]");
	}
</script>
