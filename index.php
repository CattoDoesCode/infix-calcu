<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Infix Calculator</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.css">
    <style>
        body {
            background-color: rgb(29, 29, 29);
            color: whitesmoke;
        }
        
        .accordion-button:not(.collapsed) {
            background: rgb(29, 29, 29);
            color: whitesmoke;
        }

        .accordion-button:not(.collapsed)::after {
            filter: brightness(0%) invert(70%);
        }

        .accordion-button:focus {
            box-shadow: inherit;
        }
    </style>
</head>

<body>
    <?php include 'infix_calcu.php' ?>

    <div class="container mt-5 mb-5">
        <div class="row">
            <h1 class="display-4">Infix Calculator</h1>
            <p id="header">code by Jamora, Morales, Selerio | BSCS - C83</p>
        </div>

        <div id="liveAlertPlaceholder"></div>

        <div class="col-lg-6 mt-5 mb-5" style="padding: 0;">
            <label>Enter Infix:</label><br>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" name="fname" id="input-field" style="width: 70%">
                <button type="submit" id="Calculate" class="btn btn-primary" style="width:auto;">Calculate</button>
                <button type="button" id="clear-btn" class="btn btn-secondary" onclick="clear_values()" style="width:auto;">Clear</button>
            </form>
        </div>


    </div>

    <div class="container mt-5">

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $input = $_POST['fname']; // collect value of input field

            if (empty($input)) {
                echo "0";
            }
            $calcu->infix = $input;

            if ($calcu->error_trap() != "") {
                for ($i = 0; $i < strlen($calcu->error_trap()); $i++) {
                    if ($calcu->error_trap()[$i] == "1") {
                        echo '<script type="text/javascript" src="index.js"> </script>';
                        echo '<script> bootstrap_alert("input error!", " invalid character detected", "danger") </script>';
                    } else if ($calcu->error_trap()[$i] == "2") {
                        echo '<script type="text/javascript" src="index.js"> </script>';
                        echo '<script>bootstrap_alert("Invalid Input!", " excess number/s detected", "warning")</script>';
                    } else if ($calcu->error_trap()[$i] == "3") {
                        echo '<script type="text/javascript" src="index.js"> </script>';
                        echo '<script>bootstrap_alert("Invalid Input!", " excess operator/s detected", "info")</script>';
                    } else if ($calcu->error_trap()[$i] == "4") {
                        echo '<script type="text/javascript" src="index.js"> </script>';
                        echo '<script>bootstrap_alert("Invalid Input!", " excess parenthesis detected", "dark")</script>';
                    }
                }
            } else { ?>
                <div class="col-lg-6">
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingOne">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                    Infix to Postfix
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body"> <?php $calcu->infix_to_postfix(); ?> </div>
                            </div>
                        </div>
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="flush-headingTwo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                    Stack Operation
                                </button>
                            </h2>
                            <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body"> <?php $calcu->stack_operation(); ?> </div>
                            </div>
                        </div>
                    </div>
                </div>

        <?php

                if (!empty($input)) {
                    echo "<br>" . "= " . $calcu->final_ans . "<br>";
                }
            }
        }
        ?>
    </div>

    <script src="index.js"></script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>
