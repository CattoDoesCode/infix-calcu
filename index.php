<!DOCTYPE html>
<html lang="en">

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $("#fname").keypress(function(e) {
                if (e.keyCode >= 65 && e.keyCode <= 90) {
                    e.preventDefault();
                }
            })
        });
    </script>
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
    <?php include 'infix_calcu.php';

    $input_val = "";
    $ans_final = "";
    ?>

    <div class="container mt-5">
        <h1 class="display-4">Infix Calculator</h1>
        <p>code by Jamora, Morales, Selerio | BSCS - C83</p>

        <div class="row p-3">
            <div class="row mb-3 align-items-center" style="padding: 0;">
                <label>Enter Infix Expression:</label><br>
                <form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                    <input type="text" name="fname" class="fname" id="fname">
                    <button type="submit" id="Calculate" class="btn btn-primary" style="width:auto;" onclick="">Calculate</button>
                    <button type="button" id="clear-btn" class="btn btn-secondary" onclick="clear_values()" style="width:auto;">Clear</button>
                </form>
            </div>
            <div class="row mt-3 mb-3">
                <span class="lead" id="ans" style="padding: 0; font-size: 2rem;"> =
                    <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $input = htmlspecialchars($_REQUEST['fname']); // collect value of input field
                        $calcu->infix = $input;
                        if ($calcu->error_trap() != 0) {
                            if ($calcu->error_trap() == 1) {
                                ##ALERT FOR ALPHABET
                            }
                            else if ($calcu->error_trap() == 2) {
                                ##ALERT FOR INVALID SPECIAL CHARACTERS
                            }

                        }
                        else {
                            $calcu->infix_to_postfix();
                            $calcu->stack_operation();

                            if (empty($input)) {
                                echo "0";
                            } else {
                                echo $calcu->final_ans;
                            }
                        }
                    }
                    ?>
                </span>
            </div>
            <div class="row mt-3 mb-3">
                <div id="liveAlertPlaceholder"></div>   

            </div>
        </div>
    </div>

    <script>
        function clear_values() {
            document.getElementById("fname").value = "";
            document.getElementById("ans").innerHTML = "= 0";
        }
    </script>

</body>

</html>
