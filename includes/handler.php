<?php
/**
 * Created by PhpStorm.
 * User: Marvin
 * Date: 14.07.20
 * Time: 12:16
 */

//CREATE TABLE `decix_meeting`.`Name` ( `Buchung_Id` INT NOT NULL AUTO_INCREMENT , `Name` VARCHAR(30) NOT NULL , `Email` VARCHAR(30) NOT NULL , `Von` DATETIME NOT NULL , `Bis` DATETIME NOT NULL , `Bestuhlung` INT NOT NULL , PRIMARY KEY (`Buchung_Id`)) ENGINE = InnoDB;
// DB credentials.
require ('dbconnect.php');


// Funktion für Neue Buchung
function addBooking ($name, $email, $dateFrom,$dateTo, $timeFrom, $timeTo, $places, $catering){
    global  $dbh;
    $_timeFrom = strval(date("Y-m-d H:i:s", strtotime($dateFrom." ".$timeFrom)));
    $_timeTo = strval(date("Y-m-d H:i:s", strtotime($dateTo." ".$timeTo)));
    $sql="INSERT INTO Booking (Name,Email,TimeFrom,TimeTo,Places,catering)values(:name,:email,:timeFrom,:timeTo,:places,:catering)";

    try {
        $query = $dbh->prepare($sql);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':timeFrom', $_timeFrom, PDO::PARAM_STR);
        $query->bindParam(':timeTo', $_timeTo, PDO::PARAM_STR);
        $query->bindParam(':places', $places, PDO::PARAM_STR);
        $query->bindParam(':catering', $catering, PDO::PARAM_STR);
        if(!$query->execute()) {
            header("Location: " . "http://" .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] . '?success=false');
        }
    }
    catch (Exception $e){
        header("Location: " . "http://" .$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']  . '?success=false');
        echo "Error: " . $e->getMessage();

    }

}

//Check if a table exists in the current database.
function tableExists($table) {
    global $dbh;
    // Try a select statement against the table
    try {
        $result = $dbh->query("SELECT 1 FROM $table LIMIT 1");
    } catch (Exception $e) {
        // We got an exception == table not found
        return FALSE;
    }
    // Result is either boolean FALSE (no table found) or PDOStatement Object (table found)
    return $result !== FALSE;
}


// Funktion für Buchung nach ID löschen
function deleteById($id){
    global $dbh;
    $result = false;
    try {
        $sql = "DELETE FROM Booking WHERE Booking_id=:id";
        $query = $dbh->prepare($sql);
        $query->bindParam(':id', $id, PDO::PARAM_INT);
        $query->execute();
        if ($query->execute()) {
            $result=true;
        }
    } catch (Exception $e){
        echo  $e->getMessage();
    }
    return $result;
}
