<?php
#Получаем массив с пользователями
function getUsersList()
{
    $usersData = file_get_contents('usersdata.json');
    $usersArray = json_decode($usersData, true);
    return $usersArray;
};

#Проверяем существует ли пользователь с таким именем, Вернет массив с данными или false
function existsUser($username)
{
    $usersArray = getUsersList();

    foreach ($usersArray as $user) {
        if ($user['username'] === $username) {
            return $user;
        };
    };
    return false;
};

#Проверяем пароль
function checkPassword($username, $password)
{
    $user = existsUser($username);

    if ($user) {
        if (password_verify($password, $user['password'])) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
};

#Меняем нужный параметр пользователя в JSON
function changeUserData($user, $parameterName)
{
    //Ищем старый массив с данным пользователем
    $odlUserData = existsUser($user["username"]);

    //Получаем весь массив с пользователями
    $usersList = getUsersList();

    //Ищем в общем массиве индекс нашего пользователя
    foreach ($usersList as $key) {
        if ($odlUserData["username"] === $key["username"]) {
            $userIndex = array_search($key, $usersList);
        } else continue;
    };

    //Меняем параметр на новый в массиве
    $usersList[$userIndex][$parameterName] = $user[$parameterName];

    //Перезаписываем JSON
    $jsonUsersList = json_encode($usersList);
    file_put_contents('usersdata.json', $jsonUsersList);
};

#Проверяем юзера на ДР
function birthDayCheck()
{
    $currentDate = date('m-d');
    $userDate = mb_substr($_SESSION['date'], -5);

    if ($userDate === $currentDate) {
        return true;
    };
};

#Случайный набор букв для промокода
function randomCode()
{
    return substr(str_shuffle("qwertyuiopasdfghjklzxcvbnm"), 0, 6);
}

#Таймер акции первого посещения сайта
function firstVisitPromoTimer()
{
    $endOfPromo = $_SESSION['firstVisitPromoEnd'];

    //Сколько секунд осталось до конца
    $now = time();
    $secondsRemaining = $endOfPromo - $now;

    $hoursRemaining = floor($secondsRemaining / 3600); // часы до даты
    $secondsRemaining -= ($hoursRemaining * 3600);     //обновляем переменную

    $minutesRemaining = floor($secondsRemaining / 60); //минуты до даты
    $secondsRemaining -= ($minutesRemaining * 60);     //обновляем переменную

    //Выводим сообщение
    echo "До окончания акции осталось $hoursRemaining часов, $minutesRemaining минут, $secondsRemaining секунд";
};

#Таймер до ДР
function birthDayTimer()
{
    //Считаем когда будет следующая дата ДР
    $userDate = mb_substr($_SESSION['date'], -5);
    $currentYear = date("Y");
    $nextBirthDay = strtotime($currentYear . '-' . $userDate);

    $now = time();

    //Если ДР в этом году еще не было
    if ($nextBirthDay > $now) {
        $secondsRemaining = $nextBirthDay - $now;
        $daysRemaining = floor($secondsRemaining / 86400);

    //Если уже был
    } else {
        $nextYear = date('Y') + 1;
        $nextBirthDay = strtotime($nextYear . "-" . $userDate);

        $secondsRemaining = $nextBirthDay - $now;
        $daysRemaining = floor($secondsRemaining / 86400);
    };
    echo "$daysRemaining Дней";
};