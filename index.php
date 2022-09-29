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

        <div class="row mt-5 mb-5" style="padding: 0;">
            <label>Enter Infix:</label><br>
            <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                <input type="text" name="fname" id="input-field">
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
                        echo '<script>bootstrap_alert("Invalid Input!", " excess number/s detected", "danger")</script>';
                    } else if ($calcu->error_trap()[$i] == "3") {
                        echo '<script type="text/javascript" src="index.js"> </script>';
                        echo '<script>bootstrap_alert("Invalid Input!", " excess operator/s detected", "danger")</script>';
                    } else if ($calcu->error_trap()[$i] == "4") {
                        echo '<script type="text/javascript" src="index.js"> </script>';
                        echo '<script>bootstrap_alert("Invalid Input!", " excess detected", "danger")</script>';
                    }
                }
            } else { ?>
                <div class="accordion accordion-flush bg-dark" id="accordionFlushExample">
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
        <?php

                if (!empty($input)) {
                    echo "<br>" . "= " . $calcu->final_ans . "<br>";
                }
            }
        }
        ?>
    </div>

    <script>
        function bootstrap_alert(title, message, type) {
            const alertPlaceholder = document.getElementById('liveAlertPlaceholder')
            const alert = (title, message, type) => {
                const wrapper = document.createElement('div')
                wrapper.innerHTML = [
                    `<div class="alert alert-${type} alert-dismissible d-flex align-items-center fade show" role="alert">`,
                    `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
    <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
  </svg>`,
                    `   <div><strong>${title}</strong>${message}</div>`,
                    '   <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>',
                    '</div>'
                ].join('')

                alertPlaceholder.append(wrapper)
            }

            alert(title, message, type)
        }

        function clear_values() {
            document.getElementById("input-field").value = "";
        }
    </script>
    <script src="bootstrap/js/bootstrap.bundle.js"></script>
</body>

</html>
