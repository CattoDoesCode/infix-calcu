
<?php
class InfixCalculator
{
    public $infix;
    public $postfix;
    public $final_ans;

    public $nums = [];
    public $operators = [];

    public $supported_op = array("(", ")", "^", "+", "-", "*", "/");

    public $op_precedence = array("^" => 3, "*" => 2, "/" => 2, "+" => 1, "-" => 1);
    public $op_associativity = array("^" => "r-l", "*" => "l-r", "/" => "l-r", "+" => "l-r", "-" => "l-r");

    public $ET_error_code; // error trap code

    function error_trap()
    {
        $this->ET_error_code = "";

        // error trap # 1: alphabet & invalid chararacters
        for ($i = 0; $i < strlen($this->infix); $i++) {
            if (!in_array($this->infix[$i], $this->supported_op) && !preg_match('/\d/', $this->infix[$i]) && $this->infix[$i] != "." && $this->infix[$i] != " ") {
                if (strpos($this->ET_error_code, "1") === false) {
                    $this->ET_error_code .= "1";
                }
            }
        }

        // error trap # 2, 3, 4: excess: number/s, operator/s, parenthesis

        // segregate logic

        $nums = [];
        $operators = [];

        $open_paren = [];
        $close_paren = [];

        $temp_num = ""; // for detecting multiple digits 
        $last_val = ""; // for detecting signed numbers

        $infix_arr = str_split($this->infix); // convert infix string to array
        foreach ($infix_arr as $idx => $x) {
            if ($x == " ") {
                if ($temp_num != "") {
                    array_push($nums, $temp_num);
                }
                $temp_num = "";
                continue;
            } elseif ((preg_match('/\d/', $x) || $x == ".")) {
                $temp_num .= $x;
            } else if (in_array($x, $this->supported_op)) {
                if ($x == "-" && in_array($last_val, $this->supported_op) || $last_val == " ") {
                    $temp_num .= $x;
                    continue;
                } elseif ($x == "(") {
                    if (preg_match('/\d/', $last_val)) { // open parenthesis as multiplication
                        array_push($operators, "*");
                    }
                    array_push($open_paren, $x);
                    $last_val = "(";
                } elseif ($x == ")") {
                    // closing parenthesis as multiplication
                    if ($idx != count($infix_arr) - 1) {
                        if (preg_match('/\d/', $infix_arr[$idx + 1]) || preg_match('/\d/', $infix_arr[$idx + 2])) {
                            array_push($operators, "*");
                        }
                    }
                    array_push($close_paren, $x);
                    $last_val = ")";
                } else {
                    if ($temp_num != "") {
                        array_push($nums, $temp_num);
                    }
                    $temp_num = "";

                    array_push($operators, $x);
                }
            }
            $last_val = $x;
        }

        // push remaining numbers
        if ($temp_num != "") {
            array_push($nums, $temp_num);
        }

        // error trap # 2: excess number/s
        // error trap # 3: excess operator/s
        if (count($nums) > count($operators) + 1) {
            $this->ET_error_code .= "2";
        } elseif (count($nums) < count($operators) + 1) {
            $this->ET_error_code .= "3";
        }

        // error trap # 4: excess parentheses
        if (count($open_paren) != count($close_paren)) {
            $this->ET_error_code .= "4";
        }

        // TODO: error trap # 5: shuffled operators / shuffled numbers 
        // TODO: error trap # 6: shuffled parenthesis

        return $this->ET_error_code;
    }

    function print_arr($arr = array())
    {
        if (count($arr) != 0) {
            echo "[";
            for ($i = 0; $i < count($arr); $i++) {
                if ($i < count($arr) - 1) {
                    echo $arr[$i] . ", ";
                } else {
                    echo $arr[$i];
                }
            }
            echo "]" . "\n";
        }
    }

    function precedence_logic($incoming_op)
    {
        $incoming_op_precedence = $this->op_precedence[$incoming_op];

        foreach (array_reverse($this->operators) as $y) {
            if ($y == "(") {
                break;
            }

            $current_op = $y;
            $current_op_precedence = $this->op_precedence[$current_op];

            if (count($this->operators) > 0) {

                // same precedence
                if ($current_op_precedence == $incoming_op_precedence) {
                    $incoming_op_associativity = $this->op_associativity[$incoming_op];

                    if ($incoming_op_associativity == "l-r") { // left to right associativity
                        array_pop($this->operators);
                        array_push($this->nums, $current_op);
                    } elseif ($incoming_op_associativity == "r-l") { // right to left associativity
                        array_push($this->nums, $incoming_op);
                    }
                }

                // incoming_op is lower precedence than current_op
                elseif ($current_op_precedence > $incoming_op_precedence) {
                    array_pop($this->operators);
                    array_push($this->nums, $current_op);
                }
            } else {
                array_push($this->operators, $incoming_op);
            }
        }

        // for exponent case
        if ($incoming_op == "^") {
            if (end($this->nums) != $incoming_op) {
                array_push($this->operators, $incoming_op);
            }
        } else {
            array_push($this->operators, $incoming_op);
        }
    }

    function infix_to_postfix()
    {

        // UI - infix
        echo '<p style="font-style: italic;">Infix:</p>' . $this->infix . "<br>";

        $temp_num = ""; // for detecting multiple digits 
        $last_val = ""; // for detecting signed numbers

        $this->infix = preg_replace('/\s+/', '', $this->infix); // remove all whitespace https://stackoverflow.com/a/2109339

        // traverse through the infix input
        $infix_arr = str_split($this->infix); // convert infix string to array
        foreach ($infix_arr as $idx => $x) {

            echo "<br>" . "current element: " . $x . "<br>" . "<br>"; // UI
            echo "nums: ";
            echo $this->print_arr($this->nums) . "<br>";
            echo "operators: ";
            echo $this->print_arr($this->operators) . "<br>";


            $this->current_element = $x;

            if ($x == "(") {

                if (preg_match('/\d/', $last_val)) { // open parenthesis as multiplication
                    if ($temp_num != "") {
                        array_push($this->nums, $temp_num);
                    }
                    $temp_num = "";

                    $this->precedence_logic("*");
                }

                array_push($this->operators, $x);

                if ($temp_num != "") {
                    array_push($this->nums, $temp_num);
                }
                $temp_num = "";

                $last_val = "("; // for multiple parenthesis

                continue;
            }
            // check if x is digit https://stackoverflow.com/a/14231097
            else if (preg_match('/\d/', $x) || $x == ".") {
                $temp_num .= $x;
            } else if (in_array($x, $this->supported_op)) {

                if ($x == "-" && in_array($last_val, $this->supported_op) || $last_val == "") {
                    $temp_num .= $x;
                    continue;
                } elseif (count($this->operators) == 0) {
                    array_push($this->nums, $temp_num);
                    $temp_num = "";

                    array_push($this->operators, $x);
                } else {
                    if ($temp_num != "") {
                        array_push($this->nums, $temp_num);
                    }
                    $temp_num = "";

                    $current_op = end($this->operators);
                    $incoming_op = $x;

                    // parenthesis logic

                    if ($current_op == "(") {
                        array_push($this->operators, $incoming_op);
                        continue;
                    }

                    if ($incoming_op == ")") {

                        # pop all operators and push to nums[] until ( is reached
                        foreach (array_reverse($this->operators) as $z) {
                            if ($z == "(") {
                                array_pop($this->operators);

                                if (count($this->operators) != 0) {
                                    $current_op = end($this->operators);
                                }

                                // closing parenthesis as multiplication
                                if ($idx != count($infix_arr) - 1) {
                                    if (preg_match('/\d/', $infix_arr[$idx + 1])) {
                                        $this->precedence_logic("*");
                                    }
                                }

                                break;
                            }

                            array_push($this->nums, $z);
                            array_pop($this->operators);
                        }
                        continue;
                    }

                    // precedence logic
                    $this->precedence_logic($incoming_op);
                }
            }

            $last_val = $x;
        }

        // push remaining numbers
        if ($temp_num != "") {
            array_push($this->nums, $temp_num);
        }

        // push remaining operators
        foreach (array_reverse($this->operators) as $op) {
            array_push($this->nums, $op);
        }

        echo "<br>" . "push remaining numbers and operators..." . "<br>" . "<br>"; // UI
        echo "nums: ";
        echo $this->print_arr($this->nums) . "<br>";


        $this->postfix = $this->nums;

        // UI - postfix
        echo "<br>" . '<p style="font-style: italic;">Postfix:</p>';
        echo $this->print_arr($this->postfix) . "<br>";
    }

    function stack_operation()
    {
        // UI - postfix
        echo "<br>" . '<p style="font-style: italic;">Postfix:</p>';
        echo $this->print_arr($this->postfix) . "<br>";

        $stack = [];

        for ($i = 0; $i < count($this->nums); $i++) {

            // UI - stack
            echo "<br>" . "current element: " . $this->postfix[$i] . "<br>" . "<br>";

            echo "stack: ";
            echo $this->print_arr($stack) . "<br>";

            if (in_array($this->postfix[$i], $this->supported_op)) {
                $ans = 0;

                end($stack);
                $z = prev($stack);

                $minus_two = (float) $z;
                $minus_one = (float) end($stack);

                if ($this->postfix[$i] == "^") {
                    $ans = $minus_two ** $minus_one;
                } elseif ($this->postfix[$i] == "*") {
                    $ans = $minus_two * $minus_one;
                } elseif ($this->postfix[$i] == "/") {
                    $ans = $minus_two / $minus_one;
                } elseif ($this->postfix[$i] == "+") {
                    $ans = $minus_two + $minus_one;
                } elseif ($this->postfix[$i] == "-") {
                    $ans = $minus_two - $minus_one;
                }

                array_push($stack, $ans);
                array_splice($stack, -3, -2); // pop index -3
                array_splice($stack, -2, -1); // pop index -2
            } else {
                array_push($stack, $this->postfix[$i]);
            }
        }

        $this->final_ans = implode(" ", $stack);
        echo "<br>". "final ans: ". $this->final_ans. "<br>";
    }
}

$calcu = new InfixCalculator();

// infix w/ multi-digit num, signed num, decimal, w/ & w/o whitespace
// 
// infix:   1 + 2.5 - 3* 4 +(5 ^ 6) * 7 / 8 / 9 * -1 + 123
// postfix: 1 2.5 + 3 4 * - 5 6 ^ 7 * 8 / 9 / -1 * + 123 +
// final ans: -1404.59722222

// infix w/
?>
