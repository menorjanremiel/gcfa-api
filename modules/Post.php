<?php 
    class Post {
        protected $gm;
        protected $pdo;
        protected $get;
        protected $auth;

        public function __construct(\PDO $pdo) {
            $this->pdo = $pdo;
            $this->gm = new GlobalMethods($pdo);
            $this->get = new Get($pdo);
            $this->auth = new Auth($pdo);
        }

         public function updateDoctor($dt) {
            $payload = [];
            $code = 0;
            $remarks = "failed";
            $message = "Unable to update data";
            $sql = "SELECT * FROM specialties WHERE id = ? LIMIT 1";
            $docspec = $dt->specialties;
		    $stmt = $this->pdo->prepare($sql);
            $stmt->execute([$docspec]);
		    $sname = $stmt->fetch();
            $query = "SELECT * FROM doctor WHERE docid = '$dt->docid'";
            $res = $this->gm->executeQuery($query);
            $old_password = $res['data'][0]['docpassword'];
		    $data = array('docemail' => $dt->docemail,'docname'=> $dt->docname, 'docpassword' => $this->auth->encrypt_password($dt->docpassword),'docnic' => $dt->docnic, "doctel" => $dt->doctel , "specialties" => $sname['sname']);
            if ($this->auth->password_check($dt->docpassword, $old_password)) {
            $res = $this->gm->update("doctor", $data, "docid = '$dt->docid'");
                if ($res['code'] == 200) {
                    $payload = $dt;
                    $code = 200;
                    $remarks = "success";
                    $message = "Updating data...";
                 }
             } else {
                $code = 200;
                $remarks = "failed";
                $message = "Failed to update data";
            }
            return $this->gm->response($payload, $remarks, $message, $code);
        }

        // public function studentChangePassword($dt) {
        //     $code = 0;
        //     $payload = null;
        //     $remarks = "failed";
        //     $message = "Unable to retrieve data";

        //     $query = "SELECT * FROM student_tbl WHERE studno_fld = ". $dt->studno_fld;
        //     $res = $this->gm->executeQuery($query);
        //     $old_password = $res['data'][0]['studpass_fld'];


        //     if ($this->auth->password_check($dt->old_pass, $old_password)) {
        //         $res = $this->gm->update('student_tbl', ['studpass_fld'=>$this->auth->encrypt_password($dt->studpass_fld)], "studno_fld = ". $dt->studno_fld);

        //         if($res['code'] == 200) {
        //             $code = 200;
        //             $remarks = "success";
        //             $message = "Password updated successfully";
        //         }
        //     } else {
        //         $code = 0;
        //         $remarks = "failed";
        //         $message = "Old password is incorrect";
        //     }
        //     return $this->gm->response($payload, $remarks, $message, $code);
        // }

        // public function adminChangePassword($dt) {
        //     $code = 0;
        //     $payload = null;
        //     $remarks = "failed";
        //     $message = "Unable to retrieve data";

        //     $query = "SELECT * FROM admin_tbl WHERE adminid_fld = ". $dt->adminid_fld;
        //     $res = $this->gm->executeQuery($query);
        //     $old_password = $res['data'][0]['adminpass_fld'];


        //     if ($this->auth->password_check($dt->old_pass, $old_password)) {
        //         $res = $this->gm->update('admin_tbl', ['adminpass_fld'=>$this->auth->encrypt_password($dt->adminpass_fld)], "adminid_fld = ". $dt->adminid_fld);

        //         if($res['code'] == 200) {
        //             $code = 200;
        //             $remarks = "success";
        //             $message = "Password updated successfully";
        //         }
        //     } else {
        //         $code = 0;
        //         $remarks = "failed";
        //         $message = "Old password is incorrect";
        //     }
        //     return $this->gm->response($payload, $remarks, $message, $code);
        // }
        
        // public function deleteStudent($dt) {
        //     $code = 0;
        //     $payload = null;
        //     $remarks = "failed";
        //     $message = "Unable to retrieve data";

        //     $query = "SELECT * FROM admin_tbl WHERE adminid_fld = ". $dt->adminid_fld;
        //     $res = $this->gm->executeQuery($query);
        //     $old_password = $res['data'][0]['adminpass_fld'];


        //     if ($this->auth->password_check($dt->adminpass_fld, $old_password)) {
        //         $res = $this->gm->delete('student_tbl', "studno_fld = ". $dt->studno_fld);

        //         if($res['code'] == 200) {
        //             $code = 200;
        //             $remarks = "success";
        //             $message = "Data successfully deleted";
        //         }
        //     } else {
        //         $code = 404;
        //         $remarks = "failed";
        //         $message = "Failed to delete data";
        //     }
        //     return $this->gm->response($payload, $remarks, $message, $code);
        // }

        public function deleteDoctor($dt) {
            $code = 0;
            $payload = [$res];
            $remarks = "failed";
            $message = "Unable to retrieve data";
     
            $query = "SELECT * FROM doctor WHERE docid = '$dt->docid'";
            $res = $this->gm->executeQuery($query);
            $old_password = $res['data'][0]['docpassword'];

            if ($this->auth->password_check($dt->docpassword, $old_password)) {
                $res = $this->gm->delete('doctor', "docid = '$dt->docid'");
                $res = $this->gm->delete('webuser', "email = '$dt->docemail'");
                if($res['code'] == 200) {
                    $code = 200;
                    $remarks = "success";
                    $message = "Data successfully deleted";
                }
            } else {
                $code = 404;
                $remarks = "failed";
                $message = "Failed to delete data";
            }
            return $this->gm->response($payload, $remarks, $message, $code);
        }

        // public function DeleteEnvironment($dt) {
        //     $code = 0;
        //     $payload = null;
        //     $remarks = "failed";
        //     $message = "Unable to delete environment";

        //     $res = $this->gm->delete('env_tbl', "envid_fld = '$dt->envid_fld'");

        //     if($res['code'] == 200) {
        //         $code = 200;
        //         $remarks = "success";
        //         $message = "Environment successfully deleted";
        //     }
        //     return $this->gm->response($payload, $remarks, $message, $code);
        // }

        // public function deletePosition($dt) {
        //     $code = 0;
        //     $payload = null;
        //     $remarks = "failed";
        //     $message = "Unable to delete position";

        //     $res = $this->gm->delete('position_tbl', "posid_fld = $dt->posid_fld");

        //     if($res['code'] == 200) {
        //         $code = 200;
        //         $remarks = "success";
        //         $message = "Position successfully deleted";
        //     }
        //     return $this->gm->response($payload, $remarks, $message, $code);
        // }

        // public function deleteCandidate($dt) {
        //     $code = 0;
        //     $payload = null;
        //     $remarks = "failed";
        //     $message = "Unable to delete candidate";

        //     $res = $this->gm->delete('candidate_tbl', "candidateid_fld = $dt->candidateid_fld");

        //     if($res['code'] == 200) {
        //         $code = 200;
        //         $remarks = "success";
        //         $message = "Candidate successfully deleted";
        //     }
        //     return $this->gm->response($payload, $remarks, $message, $code);
        // }

	    public function addSession($dt){
		$code = 0;
		$payload = [];
		$remarks = "failed";
		$message = "Unable to add data";
        
		$data = array('docid' => $dt->docid, 'title' => $dt->title, 'scheduledate' => $dt->scheduledate, 'scheduletime' => $dt->scheduletime, 'nop' => $dt->nop);
		$res = $this->gm->insert('schedule', $data);
		if ($res['code'] == 200) {
			$code = 200;
			$remarks = "success";
			$message = "Session added to database";
		}
		return $this->gm->response($payload, $remarks, $message, $code);
	    }



    }
