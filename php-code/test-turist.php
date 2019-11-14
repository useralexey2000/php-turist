<?php 
echo "start\n";

$pass1 = new Passerby(1, 87.342, 34.30, array(
   new Instruction(10, 0)));
$pass2 = new Passerby(2, 2.6762, 75.2811, array(
    new Instruction(40, -45.0),
    new Instruction(60, 40.0)));
$pass3 = new Passerby(3, 58.518, 93.508, array(
    new Instruction(50, 270),
    new Instruction(40, 90),
    new Instruction(5, 13)));
$tur = new Turist(array($pass1, $pass2, $pass3));

$tur->calc_res();

echo "\nfinish\n";

class Turist {
    public $passbys;

    function __construct($passbys){
        $this->passbys = $passbys;
    }

    function get_passbys(){
        return $this->passbys;
    }

    function set_passbys($passbys){
        $this->passbys = $passbys;
    }

    function calc_pass_paths($passbys){
        for ($i=0; $i < sizeof($passbys); $i++) {
            $id = $passbys[$i]->get_id();
            $x =  $passbys[$i]->get_x();
            $y = $passbys[$i]->get_y();
            $angle = 0;
            $instr = $passbys[$i]->get_instructs();
        
            for ($j=0; $j <sizeof($instr); $j++) {
                $angle = $angle + $instr[$j]->get_angle();
                $x = round($x + cos(deg2rad($angle)) * $instr[$j]->get_steps(),  4);
                $y = round($y + sin(deg2rad($angle)) * $instr[$j]->get_steps(), 4);
            }
            echo "\nbefore id: ".$id.":\n";
            print_r($passbys[$i]->get_x());
            echo " : ";
            print_r($passbys[$i]->get_y());
            
            $passbys[$i]->set_x($x);
            $passbys[$i]->set_y($y);
            
            echo "\nafter id: ".$id."\n";
            print_r($passbys[$i]->get_x());
            echo " : ";
            print_r($passbys[$i]->get_y());
        }
        return $passbys;
    }

    function calc_goal($passbys){
        $temp_x =0;
        $temp_y =0;
        $num_of_passbys = sizeof($passbys);
        for ($i=0; $i <$num_of_passbys; $i++) { 
           $temp_x = $temp_x + $passbys[$i]->get_x();
           $temp_y = $temp_y + $passbys[$i]->get_y();
        }
        $x = $temp_x / $num_of_passbys;
        $y = $temp_y / $num_of_passbys;

        echo "\n\ngoal is: ".$x." : ".$y."\n";
        return array($x, $y);
    }

    function calc_worst_dist($passbys, $goal_x, $goal_y){
        $temp;
        $worst = 0;
        for ($i=0; $i < sizeof($passbys); $i++) { 
            $temp = sqrt(pow($goal_x - $passbys[$i]->get_x(), 2)
                     + pow($goal_y - $passbys[$i]->get_y(), 2));
            if($temp > $worst) {
                $worst = $temp;
            }
        }
        echo "\nworst dist is: ".$worst."\n";
        return $worst;
    }

    function calc_res(){
        $passbys = $this->calc_pass_paths($this->passbys);
        $coords = $this->calc_goal($passbys);
        $worst = $this->calc_worst_dist($passbys, $coords[0], $coords[1]);
    }
}
class Passerby {
    private $id;
    private $x_coord;
    private $y_coord;
    private $instructs;

    function __construct($id, $x_coord, $y_coord, $instructs){
        $this->id = $id;
        $this->x_coord = $x_coord;
        $this->y_coord = $y_coord;
        $this->instructs = $instructs;
    }

    function get_id(){
        return $this->id;
    }

    function get_x(){
        return $this->x_coord;
    }

    function get_y(){
        return $this->y_coord;
    }

    function set_x($x_coord){
        $this->x_coord = $x_coord;
    }

    function set_y($y_coord){
        $this->y_coord = $y_coord;
    }

    function get_instructs(){
        return $this->instructs;
    }
}

class Instruction {
    private $angle;
    private $steps;

    function __construct($steps=0, $angle=0){
        $this->angle = $angle;
        $this->steps = $steps;
    }
    function get_angle(){
        return $this->angle;
    }
    function get_steps(){
        return $this->steps;
    }
}

?>