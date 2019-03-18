<?php
// обрабатываем форму авторизации
if (isset($_POST['submit'])){
    $username=$_POST['username'];
    $email=$_POST['email'];
    $password=$_POST['password'];
} else {
	$username="";
    $email="";
    $password="";
}
// выбор языка пользователем
//$languages=["en" => "English", "ua" => "Українська", "ru" => "Русский", "de" => "Deutsch", "fr" => "Français"];
$languages = ["English","Українська","Русский","Deutsch","Français"];

//cоздатем массив пользователей сайта 
$users = [];
    $users["5"] = ["name" => "Denis", "email" => "denis@test.com", "lang" => "ru"];
    $users["10"] = ["name" => "Vlad", "email" => "anton@gmail.com", "lang" => "en"];
    $users["125"] = ["name" => "Alex", "email" => "alex@test.com", "lang" => "ua"];
    $users["24"] = ["name" => "Nikola", "email" => "nikola@gmail.com", "lang" => "en"];
    $users["65"] = ["name" => "Maryna", "email" => "maryna@test.com", "lang" => "de"];
    $users["3"] = ["name" => "Vlad", "email" => "vlad@gmail.com", "lang" => "ua"];
    $users["7"] = ["name" => "Denis", "email" => "denis@test.com", "lang" => "ua"];
    $users["11"] = ["name" => "Anton", "email" => "anton@gmail.com", "lang" => "en"];
    $users["155"] = ["name" => "Alex", "email" => "alex@test.com", "lang" => "ua"];
    $users["28"] = ["name" => "Alex", "email" => "nikola@gmail.com", "lang" => "de"];
    $users["165"] = ["name" => "Maryna", "email" => "maryna@test.com", "lang" => "en"];
    $users["13"] = ["name" => "Vlad", "email" => "vlad@gmail.com", "lang" => "en"];
    //print_r($users);
echo "Общее количество пользователей сайта: ", count($users),"<br />";
ksort($users);
// пользователя с максимальным и минимальным айди
reset ($users);
$minId = key($users);
echo "Пользователь c минимальным айди: ", $users[$minId]['name'],"<br />";

end($users);
$maxId = key($users);
echo "Пользователь c max айди: ", $users[$minId]['lang'],"<br />";
// приветствуем пользователя на его языке
$nativaLang = ["ua" => "Привіт!", "en" => "Hello!","de" => "Hallo!","ru" => "Привет!"];
if ($users[$minId]['lang'] == $users[$maxId]['lang']){
	$key = $users[$minId]['lang'];
	echo $nativaLang[$key],"<br />";
} else {
	$key1 = $users[$minId]['lang'];
	$key2 = $users[$maxId]['lang'];
	echo $nativaLang[$key1],"<br />", $nativaLang[$key2],"<br />";
}
//выведите на экран имена пользователей который встречаются более одного раза и количество повторений имени
$nameUser = [];
foreach ($users as $key => $value) {
	foreach ($value as $key1 => $value1) {
		if ($key1=="name") {
		$nameUser[] = $value1; continue;
		}
	}
}
$nameUserCount = array_count_values($nameUser);
foreach ($nameUserCount as $key => $value) {
	if ($value > 1)
	    echo "$key &nbsp; совпадает &nbsp;$value раза <br />";
}
//разделите пользователей на массивы по языку
$ua = [];
$en = [];
$de = [];
$ru = [];
foreach ($users as $key => $value) {
	foreach ($value as $key1 => $value1) {
		switch ($key1) {
		    case $value1 == "ua":
                $ua[] = $value;
                break;
            case $value1 == "en":
                $en[] = $value;
                break;
            case $value1 == "de":
                $de[] = $value;
                break;
            case $value1 == "ru":
                $ru[] = $value;
                break;    
		}
	}
}
print_r($ua);
echo "<br /><br />";
print_r($en);
echo "<br /><br />";
print_r($de);
echo "<br /><br />";
print_r($ru);
echo "<br /><br />";
?>

<html>
    <head>
    <title> </title>
    </head>
    <body>
	     <form name ="authorization" action="" method= "POST" align="center">
	     	<p><select size="1">
	     		<?php 
	     		for ($i = 0; $i < count($languages); $i++) {
                    echo "<option> $languages[$i] </option>";
                }
	     	    ?>
            </select></p>
	     	<label>Имя &nbsp; &nbsp;</label>
	     	<input type="text" name="username" value="<?php echo $username; ?>"><br /><br />
	     	<label>Email  &nbsp; </label>
	     	<input type="email" name="email" value="<?php echo $email; ?>"><br /><br />
	     	<label>Пароль</label>
	     	<input type="password" name="password" value="<?php echo $password; ?>"><br /><br / >
	     	<input type="submit" name="submit">
	     	<input type="reset" name="reset" value="Очистить форму">
	     </form>   
	</body>
</html>