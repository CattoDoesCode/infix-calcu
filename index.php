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
    <?php include 'infix_calcu.php'; ?>

    <div class="container mt-5">
        <h1 class="display-4">Infix Calculator</h1>
        <p>code by Jamora, Morales, Selerio | BSCS - C83</p>

        <div class="row p-3">
            <div class="row mb-3 align-items-center" style="padding: 0;">
                <form method="get">
                    <label>Enter Infix Expression:</label><br>
                    <input type="text" name="input-field" style="width:auto;  "><br>
                </form>
            </div>
            <div class="row mt-3 mb-3">
                <span class="lead align-text-bottom" id="ans" style="padding: 0;"> =
                    <?php
                    $calcu->infix = '1 + 2.5 - 3* 4 +(5 ^ 6) * 7 / 8 / 9 * -1 + 123';
                    $calcu->infix_to_postfix();
                    $calcu->stack_operation();
                    echo $calcu->final_ans;
                    ?>
                </span>
            </div>
            <div class="row mt-3 mb-3">
                <button type="button" class="btn btn-primary" style="width:auto;" data-bs-dismiss="alert" aria-label="Close" onclick="
                <?php ?>">Calculate</button>

                <button type="button" id="clear-btn" class="btn btn-secondary" onclick="clear_values()" style="width:auto;margin-left: 1rem;">Clear</button>
            </div>
        </div>
    </div>


</body>

</html>