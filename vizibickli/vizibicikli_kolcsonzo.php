<!--vizsgafeladat Siklos Claudia-->
<?php
//2.feladat

abstract class kolcsonzes
{
    public $nev,$JAzon,$Eoraperc,$Voraperc;
	public function __construct($nev,$JAzon,$Eoraperc,$Voraperc)
    {
        $this-> nev = $nev;
        $this-> JAzon = $JAzon;
        $this-> Eoraperc = $Eoraperc;
        $this-> Voraperc = $Voraperc;
	}
    //3.feladat
    function getnev() 
    {
        return $this->nev;
    }
    function getJAzon() 
    {
        return $this->JAzon;
    }
    function getEoraperc() 
    {
        return $this->Eoraperc;
    }
    function getVoraperc() 
    {
        return $this->Voraperc;
    }
    
    public function setNev($nev): void 
    {
        $this->nev = $nev;
    }
    
    public function setJAzon($JAzon): void 
    {
        $this->JAzon = $JAzon;
    }
    public function setEoraperc($Eoraperc): void 
    {
        $this->Eoraperc = $Eoraperc;
    }
    public function setVoraperc($Voraperc): void 
    {
        $this->Voraperc = $Voraperc;
    }
    
}
//4.feladat feltöltés
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
    list($nev,$JAzon,$Eoraperc,$Voraperc)=$carray;
    $sql = "INSERT INTO `vizibicikli` (nev,JAzon,Eoraperc,Voraperc) values ('$nev','$JAzon','$Eoraperc','$Voraperc')";
    $conn->query($sql);
}
fclose($file);

//5.feladat hány kölcsönzés volt összesen az állományban
$sql1 ="SELECT COUNT(*) AS osszes FROM vizibicikli";
$result = mysqli_query($conn, $sql1);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else {
    while ($row = mysqli_fetch_array($result)) {
        print('5. kérdés | Összesen: '.$row['osszes']);
        print('<hr>');
    
    }
}

//6.feladat válassz egy nevet hányszor vitte el
print('6. feladat'.'<br>');
$query = "SELECT nev, Eoraperc AS elvitte, Voraperc AS visszahozta FROM vizibicikli WHERE nev ='Kata';";
$result = mysqli_query($conn, $query);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else {
    while ($row = mysqli_fetch_array($result)) {
        print($row['nev'].'|'.$row['elvitte'].'-'.$row['visszahozta'].'<br>');
    }
    print('<hr>');
}

//7. feladat óra kivalasztasa
print('7. feladat választott óra 13:00'.'<br>');
$query = "SELECT Eoraperc AS elvitte, Voraperc AS visszahozta, nev FROM vizibicikli WHERE Eoraperc LIKE '%13%'";
$result = mysqli_query($conn, $query);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else {
    while ($row = mysqli_fetch_array($result)) {
        print($row['elvitte'].'-'.$row['visszahozta'].' '.$row['nev'].'<br>');
    }
    print('<hr>');
}

//8. feladat félorankent 2400 ár
print('8. feladat'.'<br>');
$query = "SELECT  SUM(CEIL((TIME_TO_SEC (TIMEDIFF(Voraperc, Eoraperc))/60)/30)*2400) AS ido FRom vizibicikli";
$result = mysqli_query($conn, $query);
if (!$result) {
    print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
} else {
    while ($row = mysqli_fetch_array($result)) {
        print('napi bevétel : '.$row['ido'].'Ft');
    }
    print('<hr>');
}

//9.feladat txt.be kiiratas
print('9. feladat'.'<br>');
$fh = fopen('f.txt', 'w');
    $query =("SELECT JAzon, Eoraperc AS elvitte, Voraperc AS visszahozta, nev FROM vizibicikli WHERE JAzon = 'F'");   
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {          
        $num = mysqli_num_fields($result) ;    
        $last = $num - 1;
        for($i = 0; $i < $num; $i++) {            
            fwrite($fh, $row[$i]);                       
            if ($i != $last) {
                fwrite($fh, ",");
            }
        }                                                                 
        fwrite($fh, "\n");
        
    }
    fclose($fh);
    echo 'kész!';
    print('<hr>');

    //10.feladat statisztika egy jarmuvet hanyszor kolcsonoztek ki
    print('10. feladat'.'<br>');
    //SELECT JAzon AS azonosito, COUNT(JAzon) AS mennyi FROM vizibicikli GROUP BY JAzon ORDER BY JAzon asc
    $query = 'SELECT JAzon AS azonosito, COUNT(JAzon) AS darab FROM vizibicikli GROUP BY JAzon ORDER BY JAzon asc';
    $result = mysqli_query($conn, $query);
    if (!$result) {
        print(mysqli_error($conn) . ' ' . mysqli_errno($conn));
    } else {
        while ($row = mysqli_fetch_array($result)) {
            print($row['azonosito'].'-'.$row['darab'].' '.'<br>');
        }
        print('<hr>');
    }
?>
