<?php
$okay = false;
if (!empty($_POST)) {
    $okay = true;
    foreach ($_POST as $key => $value) {
        if (empty($value)) {
            $okay = false;
        }
    }
}
?>
<?php if (!$okay): ?>

    <?php
    if (isset($_SERVER['HTTPS'])) {
        $scheme = $_SERVER['HTTPS'];
    } else {
        $scheme = '';
    }
    if (($scheme) && ($scheme != 'off')) {
        $scheme = 'https';
    } else {
        $scheme = 'http';
    }
    if ($scheme != 'https') {
        echo 'У вас нет SSL сертификата, либо он не настроен. Бот работать не будет!<br>';
    }
    ?>


    Удалите этот файл после установки системы! Его путь -  <?php echo __DIR__.'/install.php'; ?>
    <hr>
    <form method="POST">
        <input type="text" required name="project_name">Название проекта<br>
        <input type="text" required name="admin_name">Ваше имя<br>
        <input type="text" required name="login">Придумайте логин<br>
        <input type="text" required name="pass">Придумайте пароль<br>
        <input type="text" required name="email">Ваш email<br>
        <input type="text" required name="telegramApi">Введите телеграм апи код.
        <hr>

        <input type="text" required name="DB_log">Введите логин БД<br>
        <input type="text" required name="DB_pass">Введите пароль БД<br>
        <input type="text" required value="localhost" name="DB_host">Хост. Обычно - localhost<br>
        <input type="text" required name="DB_name">Название базы данных
        <hr>

        <input type="text" value="<?php echo RandomString().rand(rand(1000, 1000000),
                rand(10000000, 10000000000)).RandomString(); ?>" required name="key_password">Ваш ключ проета -
        сохраните его!
        <hr>

        <button type="success">Готово.</button>
    </form>


<?php else: ?>
    <?php
    $db = mysqli_connect($_POST['DB_host'], $_POST['DB_log'], $_POST['DB_pass'],
        $_POST['DB_name']) or die('<script>alert("Не удалось подключиться к базе данных");window.location = window.location.href;</script>');

    getSQL($db);
    $botAPI = $_POST['telegramApi'];
    $url = "https://".$_SERVER['SERVER_NAME']."?id=$botAPI";
    file_get_contents("https://api.telegram.org/bot$botAPI/deleteWebhook");
    file_get_contents("https://api.telegram.org/bot$botAPI/setWebhook?url=$url");


    $config_text = '<?php 
	return[
	"Telegram_api"=> "'.$_POST['telegramApi'].'",
	"admin_log"=> "'.$_POST['login'].'",
	"admin_pass"=> "'.$_POST['pass'].'",
	"key_password" => "'.$_POST['key_password'].'",
	"admin_name" => "'.$_POST['admin_name'].'",
	"admin_email" => "'.$_POST['email'].'",
	"project_name" => "'.$_POST['project_name'].'",
	"layout" => "default",
	"debug" => "0",
	"DB_log" => "'.$_POST['DB_log'].'",
	"DB_pass" => "'.$_POST['DB_pass'].'",
	"DB_host" => "'.$_POST['DB_host'].'",
	"DB_name" => "'.$_POST['DB_name'].'",
	]; ?>';

    file_put_contents('system/config.php', $config_text);
    ?>
    <script type="text/javascript">window.location.href = window.location.href; </script>
<?php endif; ?>

<?php
function RandomString()
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 3; $i++) {
        $randstring .= $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

function getSQL($db)
{
    mysqli_query($db, 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";');
    mysqli_query($db, "CREATE TABLE `buffer` (
  `buffer_id` int(11) NOT NULL,
  `buffer_data` longtext NOT NULL,
  `buffer_key` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    mysqli_query($db, "CREATE TABLE `buttons` (
  `button_id` int(11) NOT NULL,
  `button_name` varchar(255) NOT NULL,
  `procedure_id` varchar(255) NOT NULL,
  `button_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    mysqli_query($db, "ALTER TABLE `buttons` CHANGE `button_type` `button_type` VARCHAR(255) CHARACTER SET utf8 COLLATE utf8_general_ci NULL;");

    mysqli_query($db, "CREATE TABLE `commands` (
  `command_id` int(11) NOT NULL,
  `command_text` varchar(255) NOT NULL,
  `procedure_id` int(11) NOT NULL,
  `command_type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    mysqli_query($db, "CREATE TABLE `functions` (
  `function_id` int(11) NOT NULL,
  `module_id` int(11) NOT NULL,
  `function_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    mysqli_query($db, "CREATE TABLE `function_procedure` (
  `procedure_id` int(11) NOT NULL,
  `function_id` int(11) NOT NULL,
  `return_id` int(11) NOT NULL,
  `method` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    mysqli_query($db, "CREATE TABLE `modules` (
  `module_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `version` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    mysqli_query($db, "CREATE TABLE `returns` (
  `return_id` int(11) NOT NULL,
  `return_type` varchar(255) NOT NULL DEFAULT 'text',
  `return_data` longtext NOT NULL,
  `method` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    mysqli_query($db, "CREATE TABLE `system` (
  `id` int(11) NOT NULL,
  `cron_bot_token` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");

    mysqli_query($db, "CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `telegram_id` varchar(255) NOT NULL,
  `message_count` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;");


    mysqli_query($db, "ALTER TABLE `buttons`
  ADD PRIMARY KEY (`button_id`);");

    mysqli_query($db, "ALTER TABLE `buffer`
  ADD PRIMARY KEY (`buffer_id`);");

    mysqli_query($db, "ALTER TABLE `commands`
  ADD PRIMARY KEY (`command_id`);");

    mysqli_query($db, "ALTER TABLE `functions`
  ADD PRIMARY KEY (`function_id`);");

    mysqli_query($db, "ALTER TABLE `function_procedure`
  ADD PRIMARY KEY (`procedure_id`);");

    mysqli_query($db, "ALTER TABLE `modules`
  ADD PRIMARY KEY (`module_id`);");

    mysqli_query($db, "ALTER TABLE `returns`
  ADD PRIMARY KEY (`return_id`);");

    mysqli_query($db, "ALTER TABLE `system`
  ADD PRIMARY KEY (`id`);");

    mysqli_query($db, "ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);");

    mysqli_query($db, "ALTER TABLE `buffer`
  MODIFY `buffer_id` int(11) NOT NULL AUTO_INCREMENT;");

    mysqli_query($db, "ALTER TABLE `buttons`
  MODIFY `button_id` int(11) NOT NULL AUTO_INCREMENT;");

    mysqli_query($db, "ALTER TABLE `commands`
  MODIFY `command_id` int(11) NOT NULL AUTO_INCREMENT;");

    mysqli_query($db, "ALTER TABLE `functions`
  MODIFY `function_id` int(11) NOT NULL AUTO_INCREMENT;");

    mysqli_query($db, "ALTER TABLE `function_procedure`
  MODIFY `procedure_id` int(11) NOT NULL AUTO_INCREMENT;");

    mysqli_query($db, "ALTER TABLE `modules`
  MODIFY `module_id` int(11) NOT NULL AUTO_INCREMENT;");

    mysqli_query($db, "ALTER TABLE `returns`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT;");

    mysqli_query($db, "ALTER TABLE `system`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");

    mysqli_query($db, "ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;");
}

?>