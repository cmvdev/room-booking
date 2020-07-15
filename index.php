<?php
require('includes/handler.php');
$show_modal = false;
if(isset($_POST['submit'])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $dateFrom = $_POST["dateFrom"];
    $dateTo = $_POST["dateTo"];
    $timeFrom = $_POST["timeFrom"];
    $timeTo = $_POST["timeTo"];
    $places = $_POST["places"];
    if(isset($_POST["catering"])){
        $catering = 1;
    }else{
        $catering = 0;
    }

    //echo  "Name:".$name ."Email:".$email ."Date:".$date .":TimeFrom:".$timeFrom. "TimeTo:".$timeTo ."Places:".$places .":catering:".$catering;
    addBooking ($name, $email, $dateFrom,$dateTo, $timeFrom, $timeTo, $places, $catering);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Meeting Booking Tools</title>

    <!-- css -->
    <link rel="stylesheet" type="text/css" href="css/jquery.dataTables.css">
    <link rel="stylesheet" href="css/bootstrap.min.css" >
    <link rel="stylesheet" href="css/font-awesome.css" >
    <link rel="stylesheet" href="css/style.css" >

    <!-- Js -->
    <script src="js/jquery-3.5.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/dataTables.bootstrap4.min.js"></script>
    <script src="js/jquery.dataTables.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/functions.js"></script>

</head>
<body>
<!-- Header -->
<header class="header">
    <div class="container h-100">
        <div class="row h-100 align-items-center">
            <div class="col-12 text-center">
                <h1 class="font-weight-light">Meeting Booking</h1>
                <p class="lead">Hier können Sie bequem den Meetingraum buchen</p>
            </div>
        </div>
    </div>
</header>

<!-- Content -->
<div class="container">
        <div class="row">
            <div class="col-md-12 pb-5">
                <button id="new_booking" type="button" class="btn btn-primary float-right mb-4" data-toggle="modal" data-target="#new-booking-modal" data-backdrop="static" data-keyboard="false">
                    Neue buchung
                </button>
                <h4>Aktuelle Buchungen</h4>
                <div class="table-responsive">

                <!-- Buchungen Tabelle -->
                    <table id="booking-table" class="table table-bordred table-striped">

                        <thead>
                        <!--<th><input type="checkbox" id="checkall" /></th>-->
                        <th>Id</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Anfangszeit</th>
                        <th>Endezeit</th>
                        <th>Bestuhlung</th>
                        <th>Catering</th>
                        <th>Delete</th>
                        </thead>
                        <tbody>

                        <?php
                        global $dbh;
                        $sql = "SELECT * FROM Booking";
                        $query = $dbh->prepare($sql);
                        if($query->execute()) {
                            $number_of_rows = $query->fetchColumn();
                            if($number_of_rows<1){ ?>
                                <p>Keine Buchung vorhanden</p>
                            <?php }
                            while($row = $query->fetch()) { ?>
                                <tr>
                                    <!--<td><input type="checkbox" class="checkthis" /></td>-->
                                    <td><?php  echo $row['Booking_id'] ?></td>
                                    <td><?php  echo $row['Name'] ?></td>
                                    <td><?php  echo $row['Email'] ?></td>
                                    <td><?php  echo $row['TimeFrom'] ?></td>
                                    <td><?php  echo $row['TimeTo'] ?></td>
                                    <td><?php  echo $row['Places'] ?></td>
                                    <td><?php $catering = ($row['Catering']=='1')? 'Ja':'Nein'; echo $catering?></td>
                                    <!--<td><span data-placement="top" data-toggle="tooltip" title="Edit"><button class="btn btn-primary btn-xs" data-title="Edit" data-toggle="modal" data-target="#edit" ><span class="fa fa-pencil "></span></button></span></td>-->
                                    <td><span data-placement="top" data-toggle="tooltip" title="Delete"><button id="delete-btn-<?php echo $row['Booking_id']?>" class="btn btn-danger btn-xs delete-btn" data-id="<?php echo $row['Booking_id']?>" data-title="Delete" data-toggle="modal" data-target="#delete-modal" ><span class="fa fa-trash "></span></button></span></td>
                                </tr>

                            <?php }
                        }
                        else {
                            echo "SQL Error <br />";
                            echo $query->queryString."<br />";
                            echo $query->errorInfo()[2];
                        } ?>
                        </tbody>
                    </table>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>


    <!-- Delete modal -->
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dimdiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                    <h4 class="modal-title custom_align" id="Heading">Buchung löschen</h4>
                </div>
                <div class="modal-body">

                    <div class="alert alert-danger"><span class="glyphicon glyphicon-warning-sign"></span> Wollen Sie wirklich die Buchung mit dem ID <span id="booking_id"></span> löschen?</div>

                </div>
                <div class="modal-footer ">
                    <button onclick="window.location.href += '?action=delete&id='+$('#booking_id').text()" type="button" class="btn btn-success" ><span class=""></span> Yes</button>
                    <button type="button" class="btn btn-default" data-dimdiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.Delete-dialog -->
    </div>




    <!-- Buchung Dialog -->
    <div id="new-booking-modal" class="modal" tabindex="-1" role="dialog" aria-labelledby="new-booking-modal" >
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Neue Buchung</h5>

                </div>
                <div class="modal-body">

    <!-- Buchung Form -->
    <form id="booking_form" method="post" enctype="multipart/form-data">

        <!-- Name -->
        <div class="form-group row">
            <label for="name" class="col-md-3 col-form-label">Name <span>*</span></label>
            <div class="col-md-9">
                <input id="name" type="text" name="name" class="form-control required"  placeholder="Name"  maxlength="30">
            </div>
        </div>

        <!-- Email -->
        <div class="form-group row">
            <label for="email" class="col-md-3 col-form-label">Email <span>*</span></label>
            <div class="col-md-9">
                <input id="email" name="email" type="email" class="form-control "  placeholder="Email"  maxlength="30">
            </div>
        </div>

        <!-- Anfangszeit -->
        <fieldset class="form-group">
            <div class="row">
                <legend class="col-form-label col-md-3 pt-0">Anfangszeit <span>*</span></legend>
                <div class="col-md-5">
                    <div class="form-group row">
                        <label for="dateFrom" class="col-md-3 col-form-label">Datum</label>
                        <div class="col-md-9">
                                    <input id="dateFrom" name="dateFrom" type="date" class="form-control " />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label for="timeFrom" class="col-md-3 col-form-label">Uhrzeit</label>
                        <div class="col-md-9">
                            <select id="timeFrom" name="timeFrom" class="form-control " >
                                <option value="" selected="selected">Auswählen</option>
                                <option value="09:00">9h:00</option>
                                <option value="09:30">9h:30</option>
                                <option value="10:00">10h:00</option>
                                <option value="10:30">10h:30</option>
                                <option value="11:00">11h:00</option>
                                <option value="11:30">11h:30</option>
                                <option value="12:00">12h:00</option>
                                <option value="12:30">12h:30</option>
                                <option value="13:00">13h:00</option>
                                <option value="13:30">13h:30</option>
                                <option value="14:00">14h:00</option>
                                <option value="14:30">14h:30</option>
                                <option value="15:00">15h:00</option>
                                <option value="15:30">15h:30</option>
                                <option value="16:00">16h:00</option>
                                <option value="16:30">16h:30</option>
                                <option value="17:00">17h:00</option>
                                <option value="17:30">17h:30</option>
                                <option value="18:00">18h:00</option>
                                <option value="18:30">18h:30</option>
                            </select>

                        </div>
                    </div>
                </div>
            </div>
        </fieldset>


        <!-- Endzeit -->
        <fieldset class="form-group">
            <div class="row">
                <legend class="col-form-label col-md-3 pt-0">Endzeit <span>*</span></legend>
                <div class="col-md-5">
                    <div class="form-group row">
                        <label for="dateTo" class="col-md-3 col-form-label">Datum</label>
                        <div class="col-md-9">
                            <input id="dateTo" name="dateTo" type="date" class="form-control " />
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group row">
                        <label for="timeTo" class="col-md-3 col-form-label">Uhrzeit</label>
                        <div class="col-md-9">
                            <select id="timeTo" name="timeTo" class="form-control " >
                                <option value="" selected="selected">Auswählen</option>
                                <option value="09:00">9h:00</option>
                                <option value="09:30">9h:30</option>
                                <option value="10:00">10h:00</option>
                                <option value="10:30">10h:30</option>
                                <option value="11:00">11h:00</option>
                                <option value="11:30">11h:30</option>
                                <option value="12:00">12h:00</option>
                                <option value="12:30">12h:30</option>
                                <option value="13:00">13h:00</option>
                                <option value="13:30">13h:30</option>
                                <option value="14:00">14h:00</option>
                                <option value="14:30">14h:30</option>
                                <option value="15:00">15h:00</option>
                                <option value="15:30">15h:30</option>
                                <option value="16:00">16h:00</option>
                                <option value="16:30">16h:30</option>
                                <option value="17:00">17h:00</option>
                                <option value="17:30">17h:30</option>
                                <option value="18:00">18h:00</option>
                                <option value="18:30">18h:30</option>
                            </select>

                        </div>
                    </div>
                </div>
            </div>
        </fieldset>

        <!-- Bestuhlung -->
        <div class="form-group row">
            <label for="places" class="col-md-3 col-form-label">Bestuhlung <span>*</span></label>
            <div class="col-md-9">
                <select id="places" name="places" class="form-control " >
                    <option value="" selected="selected">Auswählen</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                    <option value="9">9</option>
                    <option value="10">10</option>
                </select>
            </div>
        </div>

        <!-- Catering -->
        <div class="form-group row ">
            <label class="form-check-label col-md-3" for="gridCheck1">Mit Catering</label>
            <input id="catering" name="catering" value="ja" class="form-check-input col-md-9" type="checkbox" >
        </div>

        <input id="submit_btn" class="btn btn-primary invisible" name="submit" type="submit" />
        <button id="send" type="button" class="btn btn-success float-right" value="Senden" >Senden</button>
        <button type="button" onclick="$('#new-booking-modal').modal('hide')" class="btn btn-danger float-right mr-3" data-dimdiss="modal">Abbrechen</button>


    </form>
   </div>
            </div>
            </div>
    </div>

    <!-- Modal Box -->
    <div id="modal_dialog" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 id="modal_title" class="modal-title"></h5>
                    <button type="button" class="close" data-dimdiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div  class="alert alert-info"><p id="modal_text"></p><span class="fa fa-info-circle"></span></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dimdiss="modal">OK</button>                </div>
            </div>
        </div>
    </div>

    <?php if(isset($_GET['success'])):?>
    <?php if($_GET['success']==true):?>
            <script>
                window.location.href = 'http://localhost/decix-Meeting';
            </script>
    <?php elseif($_GET['success']==false):?>
    <script> showModal("Fehler" , "Ihr Buchungsanfrage könnte nicht gesendet werden");</script>
    <?php endif;?>
    <?php endif;?>

    <?php if(isset($_GET['action'])):?>
        <?php if($_GET['action']=='delete'):
            if(isset($_GET['id'])):
                if(deleteById($_GET['id'])):?>
                    <script>
                        window.location.href = 'http://localhost/decix-Meeting';
                    </script>
                <?php endif;?>
           <?php endif;?>



        <?php elseif($_GET['action']=='update'):?>
            <script> showModal("Fehler" , "Ihr Buchungsanfrage könnte nicht gesendet werden");</script>
        <?php endif;?>
    <?php endif;?>

    </div>

    <!-- Footer -->
    <footer class="page-footer font-mdall blue">

        <!-- Copyright -->
        <div class="footer-copyright text-center py-3">© 2020 Copyright:
            <a href="#"> Marvin Vounkeng</a>
        </div>

    </footer>
    <!-- Footer --->

</body>
</html>