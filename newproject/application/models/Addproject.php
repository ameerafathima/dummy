<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class Addproject extends CI_Model 
  {
    function __construct() 
    {
      parent::__construct();
    }

    //insert project details in database  
    function insertprojectdata($pname, $pstatus, $prating, $phead,$userdata,$date,$userfilename,$userfilepath) 
    {
      $data = array('projectname' => $pname, 'projectstatus' => $pstatus, 'projectrating' => $prating, 'projecthead'=>$phead,'user_id'=>$userdata ,'projectdate'=>$date,'filename'=>$userfilename,'filepath'=>$userfilepath);
      return $this->db->insert('addproject', $data);
    }
   
    //list project data in table
    function listprojectdata()
    {
      $user_id = $this->session->userdata('id');
      if($user_id)
      { 
        $this->db->select("addproject.projectid, projectname, projectstatus,projectrating, projecthead, user_id, projectdate, filename, country, state, city, address, 
        userdata1.duser FROM addproject 
        left join (SELECT addproject.projectid, GROUP_CONCAT(employee.duser) duser
        FROM addproject INNER JOIN employee ON FIND_IN_SET(employee.id, addproject.duser) > 0 
        GROUP BY addproject.projectid) userdata1 ON userdata1.projectid = addproject.projectid");
        $this->db->where('user_id',$user_id);
        $query = $this->db->get();
        return $query->result();
      }
      else
      {
        $this->db->select("addproject.projectid, projectname, projectstatus,projectrating, projecthead, user_id, projectdate, filename, country, state, city, address, 
        userdata1.duser FROM addproject 
        left join (SELECT addproject.projectid, GROUP_CONCAT(employee.duser) duser 
        FROM addproject INNER JOIN employee ON FIND_IN_SET(employee.id, addproject.users) > 0 
        GROUP BY addproject.projectid) userdata1 ON userdata1.projectid = addproject.projectid ");
        $query = $this->db->get();
        return $query->result();
      }
    }

    //viewing project details on clicking update/view button
    function viewprojectdata($projectid) 
    {  
      $this->db->select("projectid,projectname,projectstatus,projectrating,projecthead,projectdate");
      $this->db->from('addproject');
      $this->db->where('projectid',$projectid);
      $query = $this->db->get();
      return $query->result();
    }

    // displaying user details on clicking myprofile
    function fetchuserprofile() 
    {
      $user_id = $this->session->userdata('id');
      $this->db->select("id,firstname,lastname,email,password"); 
      $this->db->from('newproject');
      $this->db->where('id',$user_id);
      $query = $this->db->get();
      return  $query->result();
    }

    //delete a project
    function deleteRecord($table, $where = array()) 
    {
      $this->db->where($where);
      $result = $this->db->delete($table); 
      return;
    }

    //updating project details on clicking update/view
    function updateproject($projectid,$data)
    {
      $this->db->where('projectid',$projectid);
      $this->db->update('addproject',$data);
      return;
    }

    function downloadfile($params=array())
    {
      $this->db->select('*');
      $this->db->from('addproject');
      if(array_key_exists('projectid',$params) && !empty($params['projectid']))
      {
        $this->db->where('projectid',$params['projectid']);
        $query = $this->db->get();
        $result = ($query->num_rows() > 0)?$query->row_array():FALSE;
      }
      else
      {
        if(array_key_exists("start",$params) && array_key_exists("limit",$params))
        {
          $this->db->limit($params['limit'],$params['start']);
        }
        elseif(!array_key_exists("start",$params) && array_key_exists("limit",$params))
        {
          $this->db->limit($params['limit']);
        }
        $query = $this->db->get();
        $result = ($query->num_rows() > 0)?$query->result_array():FALSE;
      }
      return $query->result();
    }

    function country()
    {
      $this->db->select('*');
      $query = $this->db->get('countries');
      return $query->result();
    }

    function state($countryid='')
    {
      $this->db->select('*');
      $this->db-> where('country_id', $countryid);
      $query = $this->db->get('states');
      return $query->result();
    }

    function city($stateid='')
    {
      $this->db->select('*');
      $this->db-> where('state_id', $stateid);
      $query = $this->db->get('cities');
      return $query->result();
    }

    function insertimage($image)
    { 
      $data = array('imagename' => $image);
      $this->db->insert('image', $data);
    }

    function view_image()
    {
      $this->db->select('imagename');
      $this->db->from('image');
      $query = $this->db->get();
      return $query->result();
    }


    function userslist()
    {
      $this->db->select('id,duser');
      $this->db->from('employee');
      $query=$this->db->get();
      return $query->result();
    }
  }     
?>

 