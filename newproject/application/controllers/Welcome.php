<?php
	defined('BASEPATH') OR exit('No direct script access allowed');
	class Welcome extends CI_Controller 
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->helper('form');
			$this->load->helper('url');
			$this->load->database();
			$this->load->library('form_validation');
			$this->load->library('session');
			$this->load->model('SelectModel');
			$this->load->model('Addproject'); 
			$this->load->helper(array('form', 'url'));
			$this->load->library('upload');			
		}
		
		//load the starting page
		public function index()
		{ 
			// $this->checkisvalidated();
			
			if( ($this->session->userdata('id')) || ($this->session->userdata('userid')))
			{
				redirect('welcome/home');
			}
			$set['login']=NULL;
			$set['value']=NULL;
			$this->load->view('template/header',$set);
			$this->load->view('login.php');
			$this->load->view('template/footer');  
		}

		//load the registration page
		public function register()
		{ 
			$set['login']=NULL;
			$set['value']=NULL;
			if( $this->session->userdata('id'))
			{
				redirect('welcome/home');
			}
			$this->load->view('template/header',$set);	 
			$this->load->view('register');
			$this->load->view('template/footer'); 
		}

		//load the login page
		public function login()  
		{
			$set['login']=1;
			$set['value']=NULL;
			// $this->checkisvalidated();
			if( ($this->session->userdata('id')) || ($this->session->userdata('userid')))
			{
				redirect('welcome/home');
			}
			$this->load->view('template/header',$set); 	
			$this->load->view('login');
			$this->load->view('template/footer');
		}

		//load the homepage after login
		public function home()
		{ 
			$this->checkisvalidated();
			$set['login']=NULL;
			$set['value']=1;
			$this->load->view('template/header',$set);
			$this->load->view('home');
			$this->load->view('template/footer');
		}	

		//get user registration details
		public function getuserdata()
		{
			$f = $this->input->post("firstname");
			$l=  $this->input->post("lastname");
			$e = $this->input->post("email");
			$p= md5($this->input->post("password"));
			$this->form_validation->set_rules("firstname", "", "trim|required");
			$this->form_validation->set_rules("lastname", "", "trim|required");
			$this->form_validation->set_rules("email", "", "trim|required|valid_email");
			$this->form_validation->set_rules("password", "", "trim|required");
			$this->form_validation->set_rules("confirmpassword", "", "trim|required|matches[password]");
			if ($this->form_validation->run() == TRUE)
			{
				$this->load->model('SelectModel'); 
				$usr_result = $this->SelectModel->getregisterdata($f,$l,$e,$p);
				echo "h";
			}
			else
			{  
				echo "validation_error";
				$set['login']=NULL;
				$set['value']=NULL;
				$this->load->view('template/header',$set);
				$this->load->view('register');
				$this->load->view('template/footer',$set);
			} 		  
		}	  
				
		//user login function
		public function login_user() 
		{
			$this->form_validation->set_rules("user_email", "", "trim|required|valid_email");
			$this->form_validation->set_rules("user_password", "", "trim|required");
			if ($this->form_validation->run() == TRUE)
			{
				$data = $this->SelectModel->login_user();
				if($data) 
				{
					echo "success";
				} 
			}		 
		}
			
		// to add new project details and displaying as table
		public function addproject($userfile=array()) 
		{   
			$set['login']=NULL;
			$set['value']=1;
			$this->load->view('template/header',$set);
			$this->load->view('addproject');
			$this->load->view('template/footer',$set); 
			if($this->input->post('submit') == "submit")
			{
				$projectname = $this->input->post("projectname");
				$projectstatus=  $this->input->post("projectstatus");
				$projectrating = $this->input->post("projectrating");
				$projecthead= $this->input->post("projecthead");
				$userid= $this->session->userdata('id');
				$projectdate=$this->input->post("projectdate");
				$userfilename= $userfile['upload_data']['file_name'];
				$userfilepath= $userfile['upload_data']['file_path'];
				$usr_result = $this->Addproject->insertprojectdata($projectname,$projectstatus,$projectrating,$projecthead,$userid,$projectdate,$userfilename,$userfilepath); 
			}
			// $data['projectdata']=$this->Addproject->listprojectdata(); 
			// $set['login']=NULL;
			// $set['value']=1;
			// $this->load->view('project_view',$data); 
			// $this->load->view('template/footer',$set); 
		}

		public function project_list()
		{
			$data['projectdata']=$this->Addproject->listprojectdata(); 
			$set['login']=NULL;
			$set['value']=1;
			$this->load->view('template/header',$set);
			$this->load->view('project_view',$data); 
			$this->load->view('template/footer',$set); 
		}

			
		//delete a project
		public function deleteproject($id) 
		{
			$where = array('projectid' => $id); 
			$res=$this->Addproject->deleteRecord('addproject',$where);
			redirect('/welcome/project_list');
		}
				
		//view project details on clicking view/update
		public function viewproject($projectid) 
		{
			$data['projectdata']=$this->Addproject->viewprojectdata($projectid);
			$data['userslist']=$this->Addproject->userslist();
			$data['country']=$this->Addproject->country();
			$set['login']=NULL;
			$set['value']=1;
			$this->load->view('template/header',$set);
			$this->load->view('update_view',$data,$projectid);
			$this->load->view('template/footer',$set);
		}
			
		//update project details
		public function updateproject() 
		{
			$projectid=$this->input->post('projectid');
			$data= array(
				'projectname'=>$this->input->post('projectname'),
				'projectstatus'=>$this->input->post('projectstatus'),
				'projectrating'=>$this->input->post('projectrating'),
				'projecthead'=>$this->input->post('projecthead'),
				'projectdate'=>$this->input->post('projectdate'),
				'users'=>implode(',',$this->input->post('users')),
				'country'=>$this->input->post('country'),
				'state'=>$this->input->post('state'),
				'city'=>$this->input->post('city'),
				'address'=>$this->input->post('addresses'),
			);  
			$res=$this->Addproject->updateproject($projectid,$data);
			echo "u";     
		}
				
		//logout from homepage and userprofile
		public function logout() 
		{
			$this->session->sess_destroy();
			redirect('welcome/login');
		}
					
		//to load the user profile page
		public function userprofile()
		{
			$query=$this->Addproject->fetchuserprofile();
			$data['userdata'] = null;
			if($query)
			{
				$data['userdata'] =  $query;
			}
			$set['login']=NULL;
			$set['value']=1;
			$this->load->view('template/header', $set);
			$this->load->view('userprofile.php',$data);
			$this->load->view('template/footer');
		}

		//to upload file
		public function do_upload() 
		{  
			$config['upload_path'] ='./uploads/';
			$config['allowed_types'] = 'doc|docx|pdf|jpg';
			$config['max_size'] = 1000;
			$this->upload->initialize($config);
			if (!is_dir($config['upload_path']))  die("The upload directory doesnot exist") ;
			if (!$this->upload->do_upload('userfile'))
			{
				$error = array('error' => $this->upload->display_errors());
				redirect('/welcome/addproject/');
			}
			else
			{     
				$userfile = array('upload_data' => $this->upload->data());
				$this->addproject($userfile);
			}
		}

		 //to upload image
		public function uploadimage()
		{  
			$config['upload_path'] ='./images/';
			$config['allowed_types'] = 'jpg|jpeg|png';
			$config['max_size'] = 1000;
			$this->upload->initialize($config);
			if (!is_dir($config['upload_path']))  die("The upload directory doesnot exist") ;
			if (!$this->upload->do_upload('userimage'))
			{
				$error = array('error' => $this->upload->display_errors());
				print_r($error);
			}
			else
			{  
				$userimage = array('upload_data' => $this->upload->data());
				$image= $userimage['upload_data']['file_name'];
				$data=$this->Addproject->insertimage($image);
				$this->view_image();
			}
		}

		public function view_image()
		{   
			$set['login']=NULL;
			$set['value']=1;
			$this->load->view('template/header', $set);
			$data['name']=$this->Addproject->view_image();
			$this->load->view('image',$data);
			print_r($data);
			$this->load->view('template/footer', $set);
		}

		// to download file
		public function downloadfile($projectid)
		{
			if(!empty($projectid))
			{
				$this->load->helper('download');
				$fileInfo = $this->Addproject->downloadfile(array('projectid' => $projectid));
				$file = './uploads/'.$fileInfo[0]->filename;
				if(file_exists($file))
				{
					$data = file_get_contents ($file);
					force_download($fileInfo[0]->filename, $data);
				}
			}
		}
			
		public function checkisvalidated()
		{
			if(!$this->session->userdata("validated"))
			{
				redirect('welcome/login');
			}
		}

		public function ajax_state_list()
		{
			$countryid=$this->input->post('country_id');
			$data['state'] = $this->Addproject->state($countryid);
			$output = null;
			foreach ($data['state'] as $row)
			{
				$output .= "<option value='".$row->id."'>".$row->name."</option>";
			}
			echo $output;
		}

		public function ajax_city_list()
		{
			$stateid=$this->input->post('state_id');
			$data['city'] = $this->Addproject->city($stateid);
			$output = null;
			foreach ($data['city'] as $row)
			{
				$output .= "<option value='".$row->id."'>".$row->name."</option>";
			}
			echo $output;
		}
	}		
?>