<?php

error_reporting(E_ERROR | E_PARSE);

class Get
{
	protected $gm;
	protected $pdo;

	public function __construct(\PDO $pdo)
	{
		$this->gm = new GlobalMethods($pdo);
		$this->pdo = $pdo;
	}

	public function getStudents($dt)
	{
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

	public function getEnv($dt)
	{
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Unable to retrieve data";

		$sql = "SELECT * FROM env_tbl";
		if ($dt->envid_fld != null) {
			$sql .= " WHERE envid_fld = '$dt->envid_fld'";
		}

		$res = $this->gm->executeQuery($sql);
		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Retrieving data...";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function getPosition($dt)
	{
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Unable to retrieve data";

		$sql = "SELECT * FROM position_tbl WHERE envid_fld = '$dt->envid_fld'";

		$res = $this->gm->executeQuery($sql);
		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Retrieving data...";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function getCandidate($dt)
	{
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Unable to retrieve data";

		$sql = "SELECT * FROM candidate_tbl INNER JOIN student_tbl ON candidate_tbl.studno_fld = student_tbl.studno_fld WHERE candidate_tbl.envid_fld = '$dt->envid_fld'";

		if ($dt->studno_fld != null) {
			$sql .= "AND student_tbl.studno_fld = $dt->studno_fld";
		}

		$res = $this->gm->executeQuery($sql);
		if ($res['code'] == 200) {
			for ($i = 0; $i < sizeof($res['data']); $i++) {
				$payload[] = [
					"candidateid_fld" => $res['data'][$i]['candidateid_fld'],
					"posid_fld" => $res['data'][$i]['posid_fld'],
					"studno_fld" => $res['data'][$i]['studno_fld'],
					"partylist_fld" => $res['data'][$i]['partylist_fld'],
					"candidatedept_fld" => $res['data'][$i]['candidatedept_fld'],
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

	public function getVotes($dt)
	{
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Unable to retrieve data";

		$sql = "SELECT * FROM vote_candidate_tbl WHERE envid_fld = '$dt->envid_fld'";

		$res = $this->gm->executeQuery($sql);
		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Retrieving data...";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function getVoteHistory($dt)
	{
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Unable to retrieve data";

		$sql = "SELECT * FROM vote_candidate_tbl INNER JOIN env_tbl ON env_tbl.envid_fld = vote_candidate_tbl.envid_fld WHERE studno_fld = $dt->studno_fld";

		$res = $this->gm->executeQuery($sql);
		if ($res['code'] == 200) {
			$payload = $res['data'];
			$code = 200;
			$remarks = "success";
			$message = "Retrieving data...";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function getAdmin(){
		$payload = [];
		$code = 0;
	 	$remarks = "failed";
	 	$message = "Unable to retrieve data";

		$sql = "SELECT * FROM doctor";
	 	if ($dt->email != null) {
	 		$sql .= " WHERE email = $dt->email";
	 	}

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
