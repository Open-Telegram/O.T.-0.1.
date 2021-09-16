<?php if($thisUser == 1):?>
	<?=$user['name']?>, подключите телеграм бота к своему аккаунту:<br>
	Зайдите в телеграм бота @CigitusBot , нажмите кнопку 'подключить аккаунт' и введите сюда те данные, которые даст бот.
	<input id="telegramId" <?php if(!empty($user['telegram_id'])){echo 'value="'.$user['telegram_id'].'"';} ?> type="text"><button id="telegrambut">Подключить</button>
	<div id="success"></div>
	<script>
		$('button#telegrambut').click(function()
		{
			var id = $('input#telegramId').val();
			$.ajax({
			  url: '/?route=users/connectTelegram&telegram_id='+id,
			  success: function(){
			  	$('#success').html('Ваш телеграм подключён, ожидайте сообщения от бота.');
			  	$('#telegrambut').prop('disabled', true);
				}
			});
		});
	</script>
<?php else: ?>
аккаунт пользователя <?=$user['name']?>
<?php endif; ?>