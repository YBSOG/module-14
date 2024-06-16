<?php
session_start();
include 'fn.php';
$error = '';

if(isset($_SESSION['auth']) && $_SESSION['auth']) {
  header('Location: index.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['login'] ?? null;
  $password = $_POST['password'] ?? null;

  //Возвращает массив с данными пользователя
  $user = existsUser($username);

  if ($user) {

    //Если имя есть в системе
    if (checkPassword($username, $password)) {

      $_SESSION['auth'] = true;
      $_SESSION['username'] = $username;

      //Была ли уже индивидуальная акция
      if ($user['firstVisitPromoEnd'] !== "") {
        $_SESSION['firstVisitPromoEnd'] = $user['firstVisitPromoEnd'];
      } else {
        //Время конца индивидуальной акции
        $firstVisitPromoEnd = time() + 86400;
        $_SESSION['firstVisitPromoEnd'] = $firstVisitPromoEnd;

        //Записываем время конца акции в JSON
        $user['firstVisitPromoEnd'] = $firstVisitPromoEnd;
        changeUserData($user, 'firstVisitPromoEnd');
      };

      //Передаем в сессию ДР юзера, избегаем null на всякий
      if ($user['date'] !== null) {
        $_SESSION['date'] = $user['date'];
      } else {
        $_SESSION['date'] = '';
      }

      header('Location: index.php');
      exit;
      
    } else $error = 'Неверное имя пользователя или пароль';
  } else $error = 'Неверное имя пользователя или пароль';
};
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Вход</title>
  <link rel="stylesheet" href="css/login.css" />
</head>

<body>
  <div class="login-page">
    <div class="form">
      <form class="login-form" action="" method="post">
        <input name="login" type="text" placeholder="Логин" />
        <input name="password" type="password" placeholder="Пароль" />
        <button>Вход</button>
      </form>
      <?= $error ?>
    </div>
  </div>
</body>

</html>