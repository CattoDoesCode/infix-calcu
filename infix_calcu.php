
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

    function print_arr($arr = array())
    {
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

    // function __construct($infix)
    // {
    //     $this->infix = $infix;
    // }

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
            if ($this->nums[-1] != $incoming_op) {
                array_push($this->operators, $incoming_op);
            }
        } else {
            array_push($this->operators, $incoming_op);
        }
    }

    function infix_to_postfix()
    {
        $temp_num = ""; // for detecting multiple digits 
        $last_val = ""; // for detecting signed numbers

        $this->infix = preg_replace('/\s+/', '', $this->infix); // remove all whitespace https://stackoverflow.com/a/2109339

        // traverse through the infix input
        $infix_arr = str_split($this->infix); // convert infix string to array
        foreach ($infix_arr as $x) {

            if ($x == "(") {

                if (preg_match('/\d/', $last_val)) { // parenthesis as multiplication
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

        $this->postfix = $this->nums;
    }

    function stack_operation()
    {
        $stack = [];

        for ($i = 0; $i < count($this->nums); $i++) {

            if (in_array($this->postfix[$i], $this->supported_op)) {
                $ans = 0;

                end($stack);
                $z = prev($stack);

                $minus_two = (float)$z;
                $minus_one = (float)end($stack);

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
    }
}

$calcu = new InfixCalculator();
// infix:   1 + 2.5 - 3* 4 +(5 ^ 6) * 7 / 8 / 9 * -1 + 123
// postfix: 1 2.5 + 3 4 * - 5 6 ^ 7 * 8 / 9 / -1 * + 123 +
// final ans: -1404.59722222
?>