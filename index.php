<?php
session_start();
include 'fn.php';
$welcome = '';
$logInBtn = '';
?>

<!DOCTYPE html>
<html>

<head>
  <title>Спа Ламинария</title>
  <link rel="stylesheet" href="css/style.css" />
</head>

<body>
  <header>
    <h1>Spa-салон Ламинария</h1>
    <!-------- Если авторизован -------->
    <?php
    if (isset($_SESSION['auth']) && $_SESSION['auth']) {
      $logInBtn = 'Выйти';
      $welcome = 'Добро пожаловать, ' . $_SESSION['username'];

      //Кнопка выхода
      if (isset($_POST['btnLogOut'])) {
        $_SESSION = [];
        header('Location: index.php');
      };
    ?>
      <?= $welcome ?>
      <form method="post">
        <input type="submit" class="login" name="btnLogOut" value="<?= $logInBtn ?>">
      </form>

      <!-------- Если НЕ авторизован -------->
    <?php
    } else {
      $logInBtn = 'Войти';

      //Кнопка входа
      if (isset($_POST['btnLogIn'])) {
        header('Location: login.php');
        exit;
      };
    ?>
      <form method="post">
        <input type="submit" class="login" name="btnLogIn" value="<?= $logInBtn ?>">
      </form>
    <?php
    }; ?>
  </header>

  <!-------- Спрашиваем дату рождения -------->
  <?php
  if (isset($_SESSION['auth']) && $_SESSION['auth'] && $_SESSION['date'] === '') {
    if (isset($_POST['birthDate'])) {

      //Чтобы форма больше не появлялась
      $_SESSION['date'] = $_POST['birthDate'];

      //Для передачи массива с данными пользователя в функцию
      $user = ["username" => "", "date" => ""];
      $user['date'] = $_POST['birthDate'];
      $user['username'] = $_SESSION['username'];

      //Записываем дату в JSON
      changeUserData($user, 'date');

      header('Location: index.php');
    };
  ?>
    <div class="dateInput">
      Укажите дату Вашего рождения, чтобы получить персональную скидку!
      <form method="post" id="bday">
        <input type="date" name="birthDate" min="1930-01-01" max="2008-01-01" value="1">
        <br>
        <input type="submit" value="Подтвердить" id="bdayconfirm">
      </form>
    </div>
  <?php
  } else { ?>
    <br />
  <?php
  }
  ?>

  <section class="news">

    <!-------- Акция на ДР -------->
    <?php
    if (isset($_SESSION['auth']) && $_SESSION['auth'] && birthDayCheck()) {
      $code = randomCode();
    ?>
      <article>
        <a href="#">
          <h2>С днем рождения, <?= $_SESSION['username'] ?></h2>
        </a>
        <div class="article-meta" id="bday">
          <img src="images/bday.avif" />
          <p>
            В этот прекрасный день, мы дарим Вам скидку 5% на все услуги салона!
            <br>
            Ваш промокод: <?= $code ?>
          </p>
        </div>
      </article>

      <!-------- Таймер до ДР -------->
    <?php
    } elseif (isset($_SESSION['auth']) && $_SESSION['auth'] && $_SESSION['date'] !== "" && !birthDayCheck()) {
    ?>
      <article>
        <a href="#">
          <h2>К сожалению, только раз в году...</h2>
        </a>
        <div class="article-meta" id="bday">
          <p>
            До вашего дня рождения осталось:
            <br>
            <?= birthDayTimer(); ?>
          </p>
        </div>
      </article>
    <?php
    }
    ?>

    <!-------- Индивидуальная Акция -------->
    <?php
    if (isset($_SESSION['auth']) && $_SESSION['auth'] && $_SESSION['firstVisitPromoEnd'] > time()) {
    ?>
      <article>
        <a href="#">
          <h2>Индивидуальная Акция</h2>
        </a>
        <div class="article-meta">
          <p>
            Всем новым клиентам скидка на любую услугу в размере 5%
            <br>
            <?= firstVisitPromoTimer(); ?>
          </p>
        </div>
      </article>
    <?php
    }; ?>

    <!-------- Дальше услуги -------->
    <article>
      <a href="#">
        <h2>Как положено</h2>
      </a>
      <div class="article-meta">
        <img src="images/classic.avif" />
        <p>
          Неизменная классика. Спина, шея, плечи, руки, ноги, голова.
          Комплексное восстановление работоспособности.
        </p>
      </div>
    </article>

    <article>
      <a href="#">
        <h2>Релакс</h2>
      </a>
      <div class="article-meta">
        <img src="images/relax.avif" />
        <p>
          Замечательно подходит, если хотите снять стресс и напряжение, и
          отдохнуть душой и телом.
        </p>
      </div>
    </article>

    <article>
      <a href="#">
        <h2>Спорт</h2>
      </a>
      <div class="article-meta">
        <img src="images/sport.avif" />
        <p>
          Идеально до или после тренировок. Комплексная проработка мышц,
          восстановление рабочего тонуса.
        </p>
      </div>
    </article>

    <article>
      <a href="#">
        <h2>Антицел</h2>
      </a>
      <div class="article-meta">
        <img src="images/anticel.avif" />
        <p>
          Корректирующий уход за телом для тех, кому небезразлична своя
          фигура.
        </p>
      </div>
    </article>

    <article>
      <a href="#">
        <h2>Фейс-практика</h2>
      </a>
      <div class="article-meta">
        <img src="images/face-procedure.avif" />
        <p>
          Программа по уходу за лицом, сочетающая элементы миофасциального,
          точечного и скульптурирующего массажа лица.
        </p>
      </div>
    </article>
  </section>

  <footer>
    <div class="links">
      <a href="#">Вакансии</a>
      <a href="#">Контакты</a>
      <a href="#">О нас</a>
    </div>
  </footer>
</body>

</html>