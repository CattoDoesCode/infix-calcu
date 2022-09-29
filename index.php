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
    <?php include 'infix_calcu.php' ?>

    <div class="container mt-5">
        <div class="row">
            <h1 class="display-4">Infix Calculator</h1>
            <p id="header">code by Jamora, Morales, Selerio | BSCS - C83</p>
        </div>

        <div id="liveAlertPlaceholder"></div>

        <div class="row mt-5 mb-5" style="padding: 0;">
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

                    if (empty($input)) {
                        echo "0";
                    } else if ($calcu->error_trap() != "0") {
                        for ($i = 0; $i < strlen($calcu->error_trap()); $i++) {
                            if ($calcu->error_trap()[$i] == "1") {
                                echo '<script type="text/javascript" src="index.js"> </script>';
                                echo '<script> bootstrap_alert("input error!", " invalid character detected", "danger") </script>';
                            } else if ($calcu->error_trap()[$i] == "2") {
                                echo '<script type="text/javascript" src="index.js"> </script>';
                                echo '<script>bootstrap_alert("Invalid Input!", " invalid character detected", "danger")</script>';
                            } else if ($calcu->error_trap()[$i] == "3") {
                                echo '<script type="text/javascript" src="index.js"> </script>';
                                echo '<script>bootstrap_alert("Invalid Input!", " excess number detected", "danger")</script>';
                            }
                        }
                    } else {
                        $calcu->infix_to_postfix();
                        $calcu->stack_operation();

                        if (!empty($input)) {
                            echo $calcu->final_ans;
                        }
                    }
                }

                ?>
            </span>
        </div>

    </div>

    <div class="container mt-5">
        <p style="font-style: italic;" id="infix">Infix:</p>
        <p id="infix"><?= $calcu->infix ?></p>
        <br><br>
        <p style="font-style: italic;" id="postfix">Postfix:</p>
        <p id="postfix">
            <?php
            if ($calcu->infix != null && $calcu->error_trap() == 0) {
                $calcu->print_arr($calcu->postfix);
            }
            ?></p>

    </div>

    <div class="container mt-5">
        <p>ERROR TRAPS:</p>
        <p>nums: <?php $calcu->print_arr($calcu->ET_nums) ?></p>
        <p>operators: <?php $calcu->print_arr($calcu->ET_ops) ?></p>
        <p>open paren: <?php $calcu->print_arr($calcu->ET_open_paren) ?></p>
        <p>close paren: <?php $calcu->print_arr($calcu->ET_close_paren) ?></p>
    </div>

    <script src="index.js"></script>
</body>

</html>
