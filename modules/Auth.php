<?php
class Auth
{
	protected $gm;
	protected $pdo;
	protected $get;

	public function __construct(\PDO $pdo){
		$this->gm = new GlobalMethods($pdo);
		$this->get = new Get($pdo);
		$this->pdo = $pdo;
	}
// JWT Methods
	protected function generate_header(){
		$header = [
			"typ" => 'PWA',
			"alg" => 'HS256',
			"ver" => '1.0.0',
			"dev" => 'BSIT 3A'
		];
		return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode(json_encode($header)));
	}

	protected function generate_payload($id, $email){
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

	protected function generate_token($id, $email){
		$header = $this->generate_header();
		$payload = $this->generate_payload($id, $email);
		$hashSignature = hash_hmac('sha256', $header . "." . $payload, "www.gordoncollege.edu.ph");
		$signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($hashSignature));

		return $header . "." . $payload . "." . $signature;
	}
// User Authorization Methods
	public function encrypt_password($password){
		$hashFormat = "$2y$10$";
		$saltLength = 22;
		$salt = $this->generate_salt($saltLength);
		return crypt($password, $hashFormat . $salt);
	}

	public function decrypt_password($password){
		return base64_decode($password);
	}

	public function generate_salt($len){
		$urs = md5(uniqid(mt_rand(), true));
		$b64String = base64_encode($urs);
		$mb64String = str_replace('+', '.', $b64String);
		return substr($mb64String, 0, $len);
	}

	public function password_check($password, $existingHash){
		$hash = crypt($password, $existingHash);
		if ($hash === $existingHash) {
			return true;
		}
		return false;
	}

	protected function get_payload($token){
		$token = explode('.', $token);
		return $token[1];
	}

	protected function is_authorized($token){
		$token = explode('.', $token);
		$payload = json_decode(base64_decode($token[1]));
		$exp = $payload->exp;
		$now = time();
		if ($now < $exp) {
			return true;
		}
		return false;
	}

	protected function check_auth($token){
		if ($this->is_authorized($token)) {
			return $this->get_payload($token);
		}
		return false;
	}

	protected function getTokenSignature($d) {
			$token = explode('.', $d);
			return $token[2];
	}

	public function checkValidSignature($param1, $param2) {
			$sql = "SELECT * FROM token_tbl WHERE userid_fld = ?";
			$prep = $this->pdo->prepare($sql);
			$prep->execute([
				$param1,
			]);

			if ($res = $prep->fetchAll()) {
				$userToken = explode('.', $param2);

				return $this->getTokenSignature($res[0]['tokenlog_fld']) == $userToken[2];
			}
			return false;
	}
		
	public function check_token_log($id,$token){
			$sql = "SELECT * FROM token_tbl WHERE userid_fld = $id";
			$res = $this->gm->executeQuery($sql);

			switch($res['code']) {
				case 200:
					if (strlen($res['data'][0]['tokenlog_fld']) > 0) {
						return true;
					} else {
						$this->gm->update('token_tbl', ['tokenlog_fld'=>$token], "userid_fld = $id");
					}
				break;

				case 404:
					$payload = [
						"userid_fld" => $id,
						"tokenlog_fld" => $token,
					];
					
					$this->gm->insert("token_tbl", $payload);
				break;

				default:
					return false;
				break;
			}
	}	

	public function logout($d) {
		$payload = [];	
		$code = 200;
		$remarks = "failed";
		$message = "Logout failed";

		$res = $this->gm->delete('token_tbl', "userid_fld = $d->id");
		
			if ($res['code'] == 200) {
				$code = 200;
				$remarks = "success";
				$message = "Logout successfully";
			}
			return $this->gm->response($payload, $remarks, $message, $code);
	}
// Login
	public function login($data){
		$payload = [];
		$code = 0;
		$remarks = "failed";
		$message = "Login failed. Check you account credential.";
		$sql = "SELECT * FROM webuser WHERE email = '$data->email'";
		$res = $this->gm->executeQuery($sql);
		if ($res['code'] == 200) {
			$usertype = $res['data'][0]['usertype'];
			if ($usertype=='a') {
				$asql = "SELECT * FROM admin WHERE aemail = ? LIMIT 1";
				$stmt = $this->pdo->prepare($asql);
				$stmt->execute([$data->email]);
				$user = $stmt->fetch();
				if ($this->password_check($data->password, $user['apassword'])) {
					$id = $user['aid'];
					$email = $user['aemail'];
					$role = 'a';
					$token = $this->generate_token($id, $email);	
						if (!$this->check_token_log($id, $token)) {
							$payload = ["id" => $id, "role" => $role, "token" => $token];
							$remarks = "success"; 
							$message = "Login success.";
						} else {
							$remarks = "failed";
							$message = "Authorization failed. You already logged in with another device.";
						}
				}
			}
			
			if ($usertype=='d') {
				$dsql = "SELECT * FROM doctor WHERE docemail = ? LIMIT 1";
				$stmt = $this->pdo->prepare($dsql);
				$stmt->execute([$data->email]);
				$user = $stmt->fetch();
				if ($this->password_check($data->password, $user['docpassword'])) {
					$id = $user['docid'];
					$email = $user['docemail'];
					$role = 'd';
					$token = $this->generate_token($id, $email);	
						if (!$this->check_token_log($id, $token)) {
							
							$payload = ["id" => $id, "role" => $role, "token" => $token];
							$remarks = "success"; 
							$message = "Login success.";
						} else {
							$remarks = "failed";
							$message = "Authorization failed. You already logged in with another device.";
						}
				}
			}

			if ($usertype=='p') {
				$psql = "SELECT * FROM patient WHERE pemail = ? LIMIT 1";
				$stmt = $this->pdo->prepare($psql);
				$stmt->execute([$data->email]);
				$user = $stmt->fetch();
				if ($this->password_check($data->password, $user['ppassword'])) {
					$id = $user['pid'];
					$email = $user['pemail'];
					$role = 'p';
					$token = $this->generate_token($id, $email);	
						if (!$this->check_token_log($id, $token)) {
							$payload = ["id" => $id, "role" => $role, "token" => $token];
							$remarks = "success"; 
							$message = "Login success.";
						} else {
							$remarks = "failed";
							$message = "Authorization failed. You already logged in with another device.";
						}
				}
			}
		return $this->gm->response($payload, $remarks, $message, $code);
	}
}

  
// Add
	public function addPatient($dt){
		$code = 0;
		$payload = [];
		$remarks = "failed";
		$message = "Unable to add data";
		$data = array('pemail' => $dt->pemail,'pname'=> $dt->pname, 'ppassword' => $this->encrypt_password($dt->ppassword), 'paddress' => $dt->paddress, 'pnic' => $dt->pnic, 'pdob' => $dt->pdob, "ptel" => $dt->ptel);
		$usertype = 'p';
		$res = $this->gm->insert('patient', $data);
		$res = $this->gm->insert('webuser', array('email' => $dt->pemail, 'usertype' => $usertype));

		
		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Student added to database";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function addAdmin($dt){
		$code = 0;
		$payload = [];
		$remarks = "failed";
		$message = "Unable to add data";
		$data = array('aemail' => $dt->aemail, 'apassword' => $this->encrypt_password($dt->apassword));
		$usertype = 'a';
		$res = $this->gm->insert('admin', $data);
		$res = $this->gm->insert('webuser', array('email' => $dt->aemail, 'usertype' => $usertype));


		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Admin added to database";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

	public function addDoctor($dt){
		$code = 0;
		$payload = [];
		$remarks = "failed";
		$message = "Unable to add data";
		$docid = $this->generateShortUUID();
		$docspec = $dt->specialties;
		$sql = "SELECT * FROM specialties WHERE id = ? LIMIT 1";
		$stmt = $this->pdo->prepare($sql);
		$stmt->execute([$docspec]);
		$sname = $stmt->fetch();
		$data = array('docid' => $docid,'docemail' => $dt->docemail,'docname'=> $dt->docname, 'docpassword' => $this->encrypt_password($dt->docpassword),'docnic' => $dt->docnic, "doctel" => $dt->doctel , "specialties" => $sname['sname']);
		$usertype = 'd';
		$res = $this->gm->insert('doctor', $data);
		$res = $this->gm->insert('webuser', array('email' => $dt->docemail, 'usertype' => $usertype));

		


		if ($res['code'] == 200) {
			$payload = ["Specialties" => $sname];
			$code = 200;
			$remarks = "success";
			$message = "Faculty added to database";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	}

//others
	public function delete_img($d){
		$filename_path = "request/img/" . $d->id . "/2x2.jpg";
		if (file_exists($filename_path)) {
			unlink($filename_path);
		}
		return $this->gm->response($filename_path, 'success', 'Image deleted successfully', 200);
	}

	protected function create_folder($id){
		$file_path = 'request/img/' . $id;

		if (!file_exists($file_path)) {
			mkdir($file_path, 0777, true);
		}
	}

	public function base64_to_jpeg($d){
		$this->create_folder($d->id);
		$filename_path = "request/img/" . $d->id . "/2x2.jpg";
		$data = explode(',', $d->img);
		$decoded = base64_decode($data[1]);
		file_put_contents($filename_path, $decoded);

		return $this->gm->response($filename_path, 'success', 'Image uploaded successfully', 200);
	}

	private function generateShortUUID(){
	$sql = "SELECT UUID_SHORT() AS uuid";	
	$stmt = $this->pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $result['uuid'];
}

}
