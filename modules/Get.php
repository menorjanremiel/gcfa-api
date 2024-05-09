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

	public function getDoctor($dt){
		$payload = [];
		$code = 0;
	 	$remarks = "failed";
	 	$message = "Unable to retrieve data";

		$sql = "SELECT * FROM doctor WHERE docid = ? LIMIT 1";
				$stmt = $this->pdo->prepare($sql);
				$stmt->execute([$dt->id]);
				$user = $stmt->fetch();
				$id = $user['docid'];
				$name = $user['docname'];
				
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

	public function getAllSession($dt){
		$payload = [null];
		$code = 0;
	 	$remarks = "failed";
	 	$message = "Unable to retrieve data";

		$sql = "SELECT * FROM schedule";

	 	$res = $this->gm->executeQuery($sql);

	 	if ($res['code'] == 200) {
	 		$payload = $res['data'];
	 		$code = 200;
	 		$remarks = "success";
	 		$message = "Retrieving data...";
	 	}

		return $this->gm->response($payload, $remarks, $message, $code);
	}

		public function getSession($dt){
		$payload = [];
		$code = 0;
	 	$remarks = "failed";
	 	$message = "Unable to retrieve data";

		$sql = "SELECT * FROM schedule WHERE docid = ? ";
				$stmt = $this->pdo->prepare($sql);
				$stmt->execute([$dt->id]);
				$user = $stmt->fetchAll();
			
					if (true) {
						$payload = [$user];
						$remarks = "success"; 
						$message = "Login success.";
					}
				

		return $this->gm->response($payload, $remarks, $message, $code);
	}
}
