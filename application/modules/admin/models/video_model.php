<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Video_model extends CI_Model {

	public $variable;

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('url');
		
	}
	public function getCategory()
	{

		$this->db->select('*');
		$this->db->where('delete_flag', '0');
		$res = $this->db->get('video_categories');
		$res = $res->result_array();
		return $res;
	}
	public function insertVideo_model($title, $des, $time, $comment, $type, $category, $link, $thumb)
	{
		$this->load->helper('url');
		$this->load->helper('form');
		$object = array (
			'title'=>$title,
			'description'=>$des,
			'time'=>$time,
			'comment'=>$comment,
			'type'=>$type,
			'category_id'=>$category,
			'link'=>$link,
			'thumb'=>$thumb
		);
		$this->db->select('*');
		$this->db->insert('video', $object);
		return $this->db->insert_id();
	}
	public function getVideoById($id)
	{
		// $this->db->select('*');
		$this->db->where('id', $id);
		$res = $this->db->get('video');
		$res = $res->result_array();
		return $res;
	}
	public function updateVideo($id,$title, $des, $time, $comment, $type, $category, $link, $thumb)
	{
		$object = array (
			'title'=>$title,
			'description'=>$des,
			'time'=>$time,
			'comment'=>$comment,
			'type'=>$type,
			'category_id'=>$category,
			'link'=>$link,
			'thumb'=>$thumb
		);
		$this->db->select('*');
		$this->db->where('id', $id);
		return $this->db->update('video', $object);
	}
	public function updateThumbName($id, $new_name, $old_path, $new_path,$target)
	{
		rename($old_path, $new_path);
		$object = array ('thumb'=>$new_name);
		$this->db->select('*');
		$this->db->where('id', $id);
		$res = $this->db->update('video', $object);
		if($res){
			copy($new_path, $target);
			unlink($new_path);
		}
		return $res;
	}

}

/* End of file video_model.php */
/* Location: ./application/models/video_model.php */