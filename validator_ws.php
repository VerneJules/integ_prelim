<?php
class Student{
	private $student_id;
	private $code;
	public $nickname;
	public $fullname;
	public $gender;
	public function __construct($student_id, $code, $nickname, $fullname, $gender) {
		$this->student_id = $student_id;
		$this->code = $code;
		$this->nickname = $nickname;
		$this->fullname = $fullname;
		$this->gender = $gender;
	}
	public function validate($student_id, $code){
		return $this->student_id === $student_id && $this->code === $code;
	}
}
$students = [new Student("A001", "as#2@KJ", "kate", "kathryn bailey beckinsale", "female"),
			new Student("A002", "AG#%2f", "bob", "robert john downey jr", "male"),
			new Student("A003", "Nf*&la3", "jessica", "jessica claire biel", "female"),
			new Student("A004", "NV3(o", "scarjo", "scarlett johansson", "female"),
			new Student("A005", "x02*lL", "brad", "william bradley pitt", "male")];

$student_id = "";
if(isset($_POST["student_id"])){
	$student_id = $_POST["student_id"];
}
$code = "";
if(isset($_POST["code"])){
	$code = $_POST["code"];
}
$valid = false;
foreach($students as $s){
	if($s->validate($student_id, $code)){
		echo json_encode($s);
		return;
	}
}
echo "No Data Found"."student_id"."=".$student_id."&"."code"."=".$code;
return;
?>