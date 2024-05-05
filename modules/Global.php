<?php
	class GlobalMethods {
		protected $pdo;

		public function __construct(\PDO $pdo) {
			$this->pdo = $pdo;
		}

		public function executeQuery($sql) {
			$data = array();	
			$errmsg = "";
			$code = 0;
			try {
				if($result = $this->pdo->query($sql)->fetchAll()){
					foreach ($result as $record)
						array_push($data, $record);
					$result = null;
					$code = 200;
					return array("code"=>$code, "data"=>$data);
				} else {
					$errmsg = 'No records found.';
					$code = 404;
				}
			} catch (\PDOException $e) {
				$errmsg = $e->getMessage();
				$code = 403;
			}
			return array("code"=>$code, "errmsg"=>$errmsg);
		}

		public function response($payload, $remarks, $message, $code) {
			$status = array("remarks"=>$remarks, "message"=>$message);
			http_response_code($code);
			return array("status"=>$status, "payload"=>$payload, "timestamp"=>date_create());
		}

		public function insert($table, $data) {
			$i = 0; $fields=[]; $values=[];
			foreach ($data as $key => $value) {
				array_push($fields, $key); 
				array_push($values, $value);
			}
			try {
				$ctr=0;
				$sqlstr = "INSERT INTO $table(";
				foreach ($fields as $value) {
					$sqlstr.=$value; $ctr++;
					if($ctr<count($fields)) {
						$sqlstr.=", ";
					} 	
				} 
				$sqlstr.=") VALUES (".str_repeat("?, ", count($values)-1)."?)";
				$sql = $this->pdo->prepare($sqlstr);
				$sql->execute($values);

				$lastId = $this->pdo->lastInsertId();
				

				return array("code"=>200, "remarks"=>"success", "data"=>$lastId);
			} catch (\PDOException $e) {
				$errmsg = $e->getMessage();
				$code = 403;
			}
			return array("code"=>$code, "errmsg"=>$errmsg);
		}


		public function update($table, $data, $conditionStringPassed){
			$fields=[]; $values=[];
			$setStr = "";
			foreach ($data as $key => $value) {
				array_push($fields, $key);
				array_push($values, $value);
			}
			try{
				$ctr = 0;
				$sqlstr = "UPDATE $table SET ";
				foreach ($data as $key => $value) {
					$sqlstr .="$key=?"; $ctr++;
					if($ctr<count($fields)){
						$sqlstr.=", ";
					}
				}
				$sqlstr .= " WHERE ".$conditionStringPassed;
				$sql = $this->pdo->prepare($sqlstr);
				$sql->execute($values);
					
				return array("code"=>200, "remarks"=>"success");	
			}
			catch(\PDOException $e){
				$errmsg = $e->getMessage();
				$code = 403;
			}
			return array("code"=>$code, "errmsg"=>$errmsg);
		}

		public function delete($table, $conditionStringPassed){
			try{
				$sqlstr = "DELETE FROM $table WHERE ".$conditionStringPassed;
				$sql = $this->pdo->prepare($sqlstr);
				$sql->execute();
				return array("code"=>200, "remarks"=>"success");
			
			}catch(\PDOException $e){
				$errmsg = $e->getMessage();
				$code = 403;
			}
			return array("code"=>$code, "errmsg"=>$errmsg);

		}	
	}
?>