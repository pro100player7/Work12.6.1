<?php
$example_persons_array = [ // массив, предложенный в задании
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getPartsFromFullname($str)  // Разбиение и объединение ФИО
{
    $massKey = ['surname','name','patronomyc']; // создадим массив для записи ФИО 
    $newSimpleArr = explode( ' ', $str); // разобъем строку 'Пащенко Владимир Александрович' на массив с ФИО
    return array_combine($massKey, $newSimpleArr); // вернем массив где ключи это значения из $massKey, а значения это значения из $newSimpleArr
}
// print_r(getPartsFromFullname('Пащенко Владимир Александрович'));

function getFullnameFromParts($surname, $name, $patronomyc) // Разбиение и объединение ФИО
{
    return $surname . ' ' . $name . ' ' . $patronomyc; // вернем строку склеенную пробелами
}

// print_r(getFullnameFromParts('Шварцнегер', 'Арнольд', 'Густавович'));

function getShortName($fio) // Сокращение ФИО
{
    $simpleFio = getPartsFromFullname($fio); // используем ранне созданную фукнцию чтобы разбить ФИО на отдельные слова
    foreach($simpleFio as $key => $value) 
    {
        $shortName = $simpleFio['name'] . ' ' . mb_substr($simpleFio['surname'], 0, 1) . '.'; // склеиваю строку так, чтобы получилась короткая запись ФИО, а точнее имя $simpleFio['name'] и первая буква фамилии mb_substr($simpleFio['surname'], 0, 1)
    }
    return $shortName;
}

// print_r(getShortName('Шварцнегер Арнольд Густавович'));

function getGenderFromName($newFio) // Функция определения пола по ФИО (извините за громосткость функции)
{
   $simpleFio = getPartsFromFullname($newFio);
   $gender = 0;
   // признаки женкского пола
   if(mb_substr($simpleFio['patronomyc'], mb_strlen($simpleFio['patronomyc']) - 3, 3) === 'вна') // оставляю только последние символы из слова и проверяю их на соответсвие,(а именно нахожу длинну слова и отнимаю колличество символов в проверке в данном случае 3 "вна") и так в последующих 5 проверок
   {
        $gender--;
   }
   if(mb_substr($simpleFio['name'], mb_strlen($simpleFio['name']) - 1, 1) === 'а')
   {
        $gender--;
   }
   if(mb_substr($simpleFio['surname'], mb_strlen($simpleFio['surname']) - 2, 2) === 'ва')
   {
        $gender--;
   }
   // признаки мужского пола
   if(mb_substr($simpleFio['patronomyc'], mb_strlen($simpleFio['patronomyc']) - 2, 2) === 'ич')
   {
        $gender++;
   }
   if(mb_substr($simpleFio['name'], mb_strlen($simpleFio['name']) - 1, 1) === 'й' || mb_substr($simpleFio['name'], mb_strlen($simpleFio['name']) - 1, 1) === 'н')
   {
        $gender++;
   }
   if(mb_substr($simpleFio['surname'], mb_strlen($simpleFio['surname']) - 1, 1) === 'в')
   {
        $gender++;
   }
   // определение пола
   if($gender > 0)
   return 1;
   elseif ($gender < 0)
   return -1;
   else
   return 0;
}

// print_r(getGenderFromName('Цой Владимир Антонович'));

function getGenderDescription($mass) // Определение возрастно-полового состава (извините за громосткость функции)
{
    foreach ($mass as $key => $value) // на каждом шаге мы берем строку из массива $example_persons_array передаём в функцию getPartsFromFullname, на выходе получается массив из 3 слов, потом склеиваем слова и переводим массив в строку, потом эту строку отправляем в нам уже знакомую функцию getGenderFromName и записываем результаты опять в массив
    {
        $simpleFio = implode(' ', getPartsFromFullname($mass[$key]['fullname'])); // так как в getPartsFromFullname передаётся массив со строкой, то нужно из массива сделать строку а то фукнция не будет работать, за это отвечает функция implode
        $result[$key] = getGenderFromName($simpleFio); // строка проходит обработку и получаем ответ, и сразу записываем в массив
    }

    $counter = count($result);   // считаем общее колличество результатов 
    $m = 0; // все мужчины
    $w = 0; // все женшины
    $un = 0; // не удалось определить
    foreach ($result as $key => $value) 
    {
        if($value === 1)
        $m++; // колличество мужчин из списка
        elseif($value === -1)
        $w++; // колличество женщин из списка
        else
        $un++; // колличество неопределенных из списка
    }
    return 'Гендерный состав аудитории:' . '<br>' 
    . '-------------------------------------' . '<br>' 
    . 'Мужчины - ' . round($m*100/$counter) . '%<br>' // математические подсчеты % из общего списка с округлением до ближайшего значения
    . 'Женщины - ' . round($w*100/$counter) . '%<br>' 
    . 'Не удалось определить - ' . round($un*100/$counter) . '%<br>';
}

// print_r(getGenderDescription($example_persons_array));

function getPerfectPartner($surname, $name, $patronomyc, $massName) // Идеальный подбор пары (извините за громосткость функции)
{
    $word1Surname = mb_substr($surname, 0, 1); // находим первую букву фамилии
    $wordRestSurname = mb_substr($surname, 1, mb_strlen($surname)-1); // находим остальные буквы фамилии
    $wordTogetherSurname = mb_strtoupper($word1Surname) . mb_strtolower($wordRestSurname); // делаем 1 букву фамилии заглавной, а остальные прописными и склеиваем

    $word1Name = mb_substr($name, 0, 1);  // находим первую букву имени
    $wordRestName = mb_substr($name, 1, mb_strlen($name)-1); // находим остальные буквы имени
    $wordTogetherName = mb_strtoupper($word1Name) . mb_strtolower($wordRestName); // делаем 1 букву имени заглавной, а остальные прописными и склеиваем
    
    $word1Patronomyc = mb_substr($patronomyc, 0, 1); // находим первую букву отчества
    $wordRestPatronomyc = mb_substr($patronomyc, 1, mb_strlen($patronomyc)-1); // находим остальные буквы отчества
    $wordTogetherPatronomyc = mb_strtoupper($word1Patronomyc) . mb_strtolower($wordRestPatronomyc); // делаем 1 букву отчества заглавной, а остальные прописными и склеиваем

    $newStrFio = getFullnameFromParts($wordTogetherSurname, $wordTogetherName, $wordTogetherPatronomyc); // получаем строку из правильно написанного ФИО


    $firstHuman = getGenderFromName($newStrFio); // определяем пол ФИО (первых 3 входных параметров функции)
    $chooseHuman = $massName[rand(0, count($massName)-1)]['fullname']; // выбираем случайно из нашего массива строку ФИО 
    $secondHuman = getGenderFromName($chooseHuman); // оопределяем пол выбранного случайно ФИО
    if($firstHuman !== $secondHuman && $secondHuman !== 0) // если пол у двух ФИО разный и нету неопределенных
    {
       return getShortName($newStrFio) . ' + ' . getShortName($chooseHuman) . ' = ' . '<br>' .  '♡ Идеально на ' . rand(50, 100) . '% ♡'; // то значит всё хорошо
    } 
    else 
    return getShortName($newStrFio) . ' + ' . getShortName($chooseHuman) . ' = ' . '<br>' .  'не судьба быть вместе!'; // иначе всё плохо
}

// print_r(getPerfectPartner('ИваНов', 'Иван', 'иванович',$example_persons_array));