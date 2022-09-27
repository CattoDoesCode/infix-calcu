<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infix Calculator</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <script src="jquery-3.6.1.min.js"></script>
    <style>
        body {
            background-color: rgb(29, 29, 29);
            color: whitesmoke;
        }
    </style>
</head>

<body>
    <?php include 'infix_calcu.php'?>

    <div class="container mt-5">
        <div class="row">
            <h1 class="display-4">Infix Calculator</h1>
            <p id="header">code by Jamora, Morales, Selerio | BSCS - C83</p>
        </div>

        <div id="liveAlertPlaceholder"></div>

        <div class="row mt-4 mb-4" style="padding: 0;">
            <label>Enter Infix:</label><br>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" name="fname" id="input-field">
                <button type="submit" id="Calculate" class="btn btn-primary" style="width:auto;">Calculate</button>
                <button type="button" id="clear-btn" class="btn btn-secondary" onclick="clear_values()" style="width:auto;">Clear</button>
            </form>
        </div>

        <div class="row">
            <span class="lead" id="ans" style="padding-left: 1ren;font-size: 2rem;"> =
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    $input = htmlspecialchars($_REQUEST['fname']); // collect value of input field
                    $calcu->infix = $input;

                    if ($calcu->error_trap() != 0) {
                        if ($calcu->error_trap() == 1) {
                            echo '<script type="text/javascript" src="index.js"> </script>';
                            echo '<script> bootstrap_alert("input error!", " letter detected", "danger") </script>';
                        } else if ($calcu->error_trap() == 2) {
                            echo '<script type="text/javascript" src="index.js"> </script>';
                            echo '<script>bootstrap_alert("Invalid Input!", " invalid character detected", "danger")</script>';
                        }
                        // else if ($calcu->error_trap() == 3) {
                        //     echo '<script>alert("Invalid Input! excess number detected")</script>';
                        // }
                        
                    } else {
                        $calcu->infix_to_postfix();
                        $calcu->stack_operation();

                        if (empty($input)) {
                            echo "0";
                            echo '<script> $("#step-by-step").hide(); </script>';
                        } else {
                            echo $calcu->final_ans;
                        }
                    }
                }
                ?>
            </span>
        </div>

    </div>

    <div class="container mt-5" id="step-by-step">
        <p style="font-style: italic;" id="infix">Infix:</p>
        <?php echo $calcu->infix ?>
        <br><br>
        <p style="font-style: italic;" id="postfix">Postfix:</p>
        <?php $calcu->print_arr($calcu->postfix) ?>
    </div>

    <script src="index.js"></script>
</body>

</html>
