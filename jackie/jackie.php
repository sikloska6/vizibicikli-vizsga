<?php

//2. feladat

require_once('./config.php');
$conn = mysqli_connect($server, $user, $password, $db);
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
};
$file = fopen('./adat.txt', 'r');
while (!feof($file))
{
    $content = fgets($file);
    $carray = explode(';', $content);
    list($year,$races,$wins,$podiums,$poles,$fastest)=$carray;
    $sql = "INSERT INTO `jackie` (`year`,`races`,`wins`,`podiums`,`poles`,`fastest`) values ('$year','$races','$wins','$podiums','$poles','$fastest')";
    $conn->query($sql);
}
fclose($file);

//3.feladat
print('3.feladat | összesen : ');
$sql1 ="SELECT COUNT(*) AS `osszes` FROM `jackie`";
$result = mysqli_query($conn, $sql1);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else {
    while ($row = mysqli_fetch_array($result)) {
        print($row['osszes']);
        print('<hr>');
    
    }
}

//4.feladat mikor volt a legtobb versenye
print('4.feladat  : ');
$query ="SELECT `YEAR`, max(`races`) FROM `jackie`";
$result = mysqli_query($conn, $query);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else {
    while ($row = mysqli_fetch_array($result)) {
        print($row['YEAR']);
        print('<hr>');
    
    }
}

//5.feladat melyik evtized volt a legsikeresbb
print('5.feladat  : '.'<br>');
$query ="Select `YEAR` AS `ev`, sum(`wins`) AS `nyert` FROM `jackie` WHERE `YEAR` BETWEEN 1960 AND 1969";
$result = mysqli_query($conn, $query);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else 
{
    while ($row = mysqli_fetch_array($result)) {
        print('hatvanas évek : '.$row['nyert']);
        print('<br>');
    }
} 
$query ="Select `YEAR` AS `ev`, sum(`wins`) AS `nyert` FROM `jackie` WHERE `YEAR` BETWEEN 1970 AND 1979";
$result = mysqli_query($conn, $query);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else 
{
    while ($row = mysqli_fetch_array($result)) {
        print('hetvenes évek : '.$row['nyert']);
        print('<hr>');
    }
} 

//6.feladat html
print('6. feladat: kész');
$query ="Select `year`, `races`, `wins` FROM jackie order by year desc";
$result = mysqli_query($conn, $query);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else 
{
    $file = 'jackie.html';
    $current = file_get_contents($file);
    $current .= "<!doctype html><html>
                <head><meta charset='utf-8'><style>td { border:1px solid black;}</style>
                </head><body><h1>JAckie Stewart</h1>";
    while ($row = mysqli_fetch_array($result)) {
        
        $current .= "
                    <table>
                    <tr><td>$row[year]</td><td>$row[races]</td><td>$row[wins]</td></tr>
                    </table>
                    </body></html>
                    ";
        file_put_contents($file, $current,);

    }
} 
 mysqli_close($conn);

 ?>




