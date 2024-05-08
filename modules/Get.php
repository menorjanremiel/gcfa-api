<?php

error_reporting(E_ERROR | E_PARSE);

class Get
{
	protected $gm;
	protected $pdo;

	public function __construct(\PDO $pdo){
		$this->gm = new GlobalMethods($pdo);
		$this->pdo = $pdo;
	}

	public function getStudents($dt){
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Unable to retrieve data";

		$sql = "SELECT * FROM student_tbl";
		if ($dt->studno_fld != null) {
			$sql .= " WHERE studno_fld = $dt->studno_fld";
		}

		$res = $this->gm->executeQuery($sql);
		if ($res['code'] == 200) {
			for ($i = 0; $i < sizeof($res['data']); $i++) {
				$payload[] = [
					"studno_fld" => $res['data'][$i]['studno_fld'],
					"studfname_fld" => $res['data'][$i]['studfname_fld'],
					"studmname_fld" => $res['data'][$i]['studmname_fld'],
					"studlname_fld" => $res['data'][$i]['studlname_fld'],
					"studextension_fld" => $res['data'][$i]['studextension_fld'],
					"studdept_fld" => $res['data'][$i]['studdept_fld'],
					"studprog_fld" => $res['data'][$i]['studprog_fld'],
				];
			}

			$code = 200;
			$remarks = "success";
			$message = "Retrieving data...";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	// public function getAdmin($dt)
	// {
	// 	$payload = [];
	// 	$code = 0;
	// 	$remarks = "failed";
	// 	$message = "Unable to retrieve data";

	// 	$sql = "SELECT * FROM admin_tbl";
	// 	if ($dt->adminid_fld != null) {
	// 		$sql .= " WHERE adminid_fld = $dt->adminid_fld";
	// 	}

	// 	$res = $this->gm->executeQuery($sql);
	// 	if ($res['code'] == 200) {
	// 		$payload = $res['data'];
	// 		$code = 200;
	// 		$remarks = "success";
	// 		$message = "Retrieving data...";
	// 	}
	// 	return $this->gm->response($payload, $remarks, $message, $code);
	// }




	public function getAllDoctor(){
		$payload = [];
		$code = 0;
	 	$remarks = "failed";
	 	$message = "Unable to retrieve data";

		$sql = "SELECT * FROM doctor";
	 	$res = $this->gm->executeQuery($sql);

		

	 	if ($res['code'] == 200) {
	 		$payload = $res['data'];
	 		$code = 200;
	 		$remarks = "success";
	 		$message = "Retrieving data...";
	 	}

		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function getAllPatients($dt){
		$payload = [null];
		$code = 0;
	 	$remarks = "failed";
	 	$message = "Unable to retrieve data";

		$sql = "SELECT * FROM patient";

	 	$res = $this->gm->executeQuery($sql);
	 	if ($res['code'] == 200) {
	 		$payload = $res['data'];
	 		$code = 200;
	 		$remarks = "success";
	 		$message = "Retrieving data...";
	 	}

		return $this->gm->response($payload, $remarks, $message, $code);
	}
	public function getPatient($dt){
		$payload = [];
		$code = 0;
	 	$remarks = "failed";
	 	$message = "Unable to retrieve data";

		$sql = "SELECT * FROM patient WHERE pid = ? LIMIT 1";
				$stmt = $this->pdo->prepare($sql);
				$stmt->execute([$dt->id]);
				$user = $stmt->fetch();
				$id = $user['pid'];
				$name = $user['pname'];
				
					if (true) {
						$payload = ["name" => $name,];
						$remarks = "success"; 
						$message = "Login success.";
					}
				

		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function getSpecialties($dt){
		$payload = [];
		$code = 0;
	 	$remarks = "failed";
	 	$message = "Unable to retrieve data";

		$sql = "SELECT * FROM specialties";
	
		$res = $this->gm->executeQuery($sql);
		 	if ($res['code'] == 200) {
	 		$payload = $res['data'];
	 		$code = 200;
	 		$remarks = "success";
	 		$message = "Retrieving data...";
	 	}
		return $this->gm->response($payload, $remarks, $message, $code);
	}
}
