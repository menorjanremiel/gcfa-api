<?php
class Auth
{
	protected $gm;
	protected $pdo;
	protected $get;

	public function __construct(\PDO $pdo)
	{
		$this->gm = new GlobalMethods($pdo);
		$this->get = new Get($pdo);
		$this->pdo = $pdo;
	}



	// JWT Methods

	protected function generate_header()
	{
		$header = [
			"typ" => 'PWA',
			"alg" => 'HS256',
			"ver" => '1.0.0',
			"dev" => 'BSIT 3A'
		];
		return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
	}

	protected function generate_payload($id, $email)
	{
		$payload = [
			'uid' => $id,
			'un' => $email,
			'iby' => 'BSIT 3A',
			'ie' => '201811028@gordoncollege.edu.ph',
			'idate' => date_create(),
			'exp' => time() + (60 * 60 * 24 * 7)
		];
		return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($payload)));
	}

	protected function generate_token($id, $email)
	{
		$header = $this->generate_header();
		$payload = $this->generate_payload($id, $email);
		$hashSignature = hash_hmac('sha256', $header . "." . $payload, "www.gordoncollege.edu.ph");
		$signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($hashSignature));

		return $header . "." . $payload . "." . $signature;
	}

	// User Authorization Methods

	public function encrypt_password($password)
	{
		$hashFormat = "$2y$10$";
		$saltLength = 22;
		$salt = $this->generate_salt($saltLength);
		return crypt($password, $hashFormat . $salt);
	}

	public function decrypt_password($password)
	{
		return base64_decode($password);
	}

	public function generate_salt($len)
	{
		$urs = md5(uniqid(mt_rand(), true));
		$b64String = base64_encode($urs);
		$mb64String = str_replace('+', '.', $b64String);
		return substr($mb64String, 0, $len);
	}

	public function password_check($password, $existingHash)
	{
		$hash = crypt($password, $existingHash);
		if ($hash === $existingHash) {
			return true;
		}
		return false;
	}

	protected function get_payload($token)
	{
		$token = explode('.', $token);
		return $token[1];
	}

	protected function is_authorized($token)
	{
		$token = explode('.', $token);
		$payload = json_decode(base64_decode($token[1]));
		$exp = $payload->exp;
		$now = time();
		if ($now < $exp) {
			return true;
		}
		return false;
	}

	protected function check_auth($token)
	{
		if ($this->is_authorized($token)) {
			return $this->get_payload($token);
		}
		return false;
	}

	protected function create_folder($id)
	{
		$file_path = 'request/img/' . $id;

		if (!file_exists($file_path)) {
			mkdir($file_path, 0777, true);
		}
	}

	public function base64_to_jpeg($d)
	{
		$this->create_folder($d->id);
		$filename_path = "request/img/" . $d->id . "/2x2.jpg";
		$data = explode(',', $d->img);
		$decoded = base64_decode($data[1]);
		file_put_contents($filename_path, $decoded);

		return $this->gm->response($filename_path, 'success', 'Image uploaded successfully', 200);
	}

	public function delete_img($d)
	{
		$filename_path = "request/img/" . $d->id . "/2x2.jpg";
		if (file_exists($filename_path)) {
			unlink($filename_path);
		}

		return $this->gm->response($filename_path, 'success', 'Image deleted successfully', 200);
	}

	// Login

	public function login($data)
	{
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Login failed. Check you account credential.";

		$sql = "SELECT * FROM webuser WHERE email = '$data->email'";
		$res = $this->gm->executeQuery($sql);

		if ($res['code'] == 200) {
			$usertype = $res['data'][0]['usertype'];
			if ($usertype=='d') {
				$email = $res['data'][0]['email'];
				// $token = $this->generate_token($email);
				// $this->gm->update('admin_tbl', ['admintoken_fld' => $token], $id);
				$faculty = "SELECT * FROM doctor WHERE docemail = '$data->email'";
				$fdt = $this->gm->executeQuery($faculty);
				$password =$fdt['data'][0]['docpassword'];
				if ($password == $data->password) {
					$payload = $res['data'][0]['usertype'];
					$code = 200;					
					$remarks = "success";
					$message = "Login success.";
				}
			}
			if ($usertype=='a') {
				$email = $res['data'][0]['email'];
				// $token = $this->generate_token($email);
				// $this->gm->update('admin_tbl', ['admintoken_fld' => $token], $id);
				$admin = "SELECT * FROM admin WHERE aemail = '$data->email'";
				$adt = $this->gm->executeQuery($admin);
				$password =$adt['data'][0]['apassword'];
				if ($password == $data->password) {
					$payload = $res['data'][0]['usertype'];
					$code = 200;					
					$remarks = "success";
					$message = "Login success.";
				}
			}
			if ($usertype=='p') {
				$email = $res['data'][0]['email'];
				// $token = $this->generate_token($email);
				// $this->gm->update('admin_tbl', ['admintoken_fld' => $token], $id);
				$patient = "SELECT * FROM patient WHERE pemail = '$data->email'";
				$pdt = $this->gm->executeQuery($patient);
				$password =$pdt['data'][0]['ppassword'];
				if ($password == $data->password) {
					$payload = $res['data'][0]['usertype'];
					$code = 200;					
					$remarks = "success";
					$message = "Login success.";
				}
			}
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function loginStudent($data)
	{
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Login failed. Check you account credential.";

		$sql = "SELECT * FROM student_tbl WHERE studno_fld = $data->studno_fld LIMIT 1";
		$res = $this->gm->executeQuery($sql);

		if ($res['code'] == 200) {
			if ($this->password_check($data->studpass_fld, $res['data'][0]['studpass_fld'])) {
				$id = $res['data'][0]['studid_fld'];
				$email = $res['data'][0]['studno_fld'];
				$token = $this->generate_token($id, $email);

				$this->gm->update('student_tbl', ['studtoken_fld' => $token], $id);

				if ($this->check_auth($token)) {
					$payload = $this->check_auth($token);
					$code = 200;
					$remarks = "success";
					$message = "Login success.";
				}
			}
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	// Add

	public function addStudent($dt)
	{
		$code = 0;
		$payload = [];
		$remarks = "failed";
		$message = "Unable to add data";
		$data = array('studno_fld' => $dt->studno_fld, 'studpass_fld' => $this->encrypt_password($dt->studpass_fld), 'studfname_fld' => $dt->studfname_fld, 'studmname_fld' => $dt->studmname_fld, 'studlname_fld' => $dt->studlname_fld, "studextension_fld" => $dt->studextension_fld, "studdept_fld" => $dt->studdept_fld, "studprog_fld" => $dt->studprog_fld);

		$res = $this->gm->insert('student_tbl', $data);

		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Student added to database";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function addAdmin($dt)
	{
		$code = 0;
		$payload = null;
		$remarks = "failed";
		$message = "Unable to add data";
		$data = array('adminuser_fld' => $dt->adminuser_fld, 'adminpass_fld' => $this->encrypt_password($dt->adminpass_fld), 'adminfname_fld' => $dt->adminfname_fld, 'adminmname_fld' => $dt->adminmname_fld, 'adminlname_fld' => $dt->adminlname_fld, "adminext_fld" => $dt->adminext_fld, "admindept_fld" => $dt->admindept_fld, "adminpos_fld" => $dt->adminpos_fld);

		$res = $this->gm->insert('admin_tbl', $data);

		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Admin added to database";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function addEnvironment($dt)
	{
		$code = 0;
		$payload = null;
		$remarks = "failed";
		$message = "Unable to Add Environment";

		$res = $this->gm->insert('env_tbl', $dt);

		if ($res['code'] == 200) {
			$payload = $dt;
			$code = 200;
			$remarks = "success";
			$message = "Voting Environment successfully added";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function addPosition($dt)
	{
		$code = 0;
		$payload = null;
		$remarks = "failed";
		$message = "Unable to Add Position";

		$res = $this->gm->insert('position_tbl', $dt);

		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Position successfully added";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function addCandidate($dt)
	{
		$code = 0;
		$payload = null;
		$remarks = "failed";
		$message = "Unable to Add Candidate";

		$res = $this->gm->insert('candidate_tbl', $dt);

		if ($res['code'] == 200) {
			$payload = $res;
			$code = 200;
			$remarks = "success";
			$message = "Candidate successfully added";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function AddVote($dt)
	{
		$code = 0;
		$payload = null;
		$remarks = "failed";
		$message = "Unable to Add Vote";

		$res = $this->gm->insert('vote_candidate_tbl', $dt);

		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Vote successfully added";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}
}
