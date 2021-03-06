`<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Videos extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		$this->load->view('videos/homepage_view');
	}
	public function addVideo($id=0)
	{
		if($id == 0) {
				$data_video_value = array (
					'id'=>0,
					'title'=>"",
					'description'=>"",
					'time'=>"",
					'comment'=>"",
					'type'=>"",
					'thumb'=>"",
					'category'=>"	",
					'link'=>"",
				);
				$data_video_value = array (0=>$data_video_value);

			}
			else {
				$this->load->model('video_model');
				$data_video_value = $this->video_model->getVideoById($id);

			}

		$data = array ('video_value'=>$data_video_value);
		$this->load->view('videos/addvideo_view', $data, FALSE);
	}
	public function insertVideo($id = 0){
		if(!$this->input->post()){
			$this->load->model('video_model');
			$cat = $this->video_model->getCategory();
			if($id == 0) {
				$data_video_value = array (
					'id'=>0,
					'title'=>"",
					'description'=>"",
					'time'=>"",
					'comment'=>"",
					'type'=>"",
					'thumb'=>"",
					'category_id'=>"	",
					'link'=>"",
				);
				$data_video_value = array (0=>$data_video_value);

			}
			else {
				$this->load->model('video_model');
				$data_video_value = $this->video_model->getVideoById($id);

			}

			$data = array ('video_value'=>$data_video_value, 'category'=>$cat);
			$this->load->view('videos/addvideo_view', $data, FALSE);
		}else {
			if($id == 0){
				$title = $this->input->post('title');
				$des = $this->input->post('description');
				$time = $this->input->post('time');
				$comment = $this->input->post('comment');
				$type = $this->input->post('type');
				$category = $this->input->post('category');
				$link = $this->input->post('link');	
				//làm cái upload thumbnail
				$config['upload_path'] = './uploads_temp/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']  = '10000';
				$config['max_width']  = '102400';
				$config['max_height']  = '76800';
				$config['file_name'] = strval(time());
				$thumb = $config['file_name'];
				$target_folder = "./uploads/";
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload("thumb")){
					$error = array('error' => $this->upload->display_errors());
							echo "<pre>";
							print_r($error);
							echo "</pre>";
							exit;
				}
				else{
					$data = array('upload_data' => $this->upload->data());
					echo "success";
					$file_ext = $data['upload_data']['file_ext'];
					//gọi model insert data vào DB

					$this->load->model('video_model');
					$id_thumb = $this->video_model->insertVideo_model($title, $des, $time, $comment, $type, $category, $link, $thumb);
					if ($id_thumb){
						$new_thumb_name = strval($id_thumb)."_".strval($thumb).$file_ext;
						$old_path = $config['upload_path'].$thumb.$file_ext;
						$new_target = $target_folder.$new_thumb_name;
						$new_path = $config['upload_path'].$new_thumb_name;
						$this->load->model('video_model');
						$res = $this->video_model->updateThumbName($id_thumb, $new_thumb_name, $old_path, $new_path,$new_target);
						if($res){
							header("Location: ".base_url()."videos/listVideo");
						}
						else {
							header("location ".base_url()."videos/loi-insert_view");
						}
					} else {
						header("location ".base_url()."videos/loi-insert_view");
					}
				}
			}else {
				$title = $this->input->post('title');
				$des = $this->input->post('description');
				$time = $this->input->post('time');
				$comment = $this->input->post('comment');
				$type = $this->input->post('type');
				$category = $this->input->post('category');
				$link = $this->input->post('link');	
				//làm cái upload thumbnail
				$config['upload_path'] = './uploads_temp/';
				$config['allowed_types'] = 'gif|jpg|png';
				$config['max_size']  = '10000';
				$config['max_width']  = '102400';
				$config['max_height']  = '76800';
				$config['file_name'] = strval(time());
				$thumb = $config['file_name'];
				$target_folder = "./uploads/";
				$this->load->library('upload', $config);
				
				if ( ! $this->upload->do_upload("thumb")){
					$error = array('error' => $this->upload->display_errors());
					$thumb_old = $this->input->post('thumb_old');
					$this->load->model('video_model');
					if($this->video_model->updateVideo($id, $title, $des, $time, $comment, $type, $category, $link, $thumb_old))
					{
						header("Location: ".base_url()."admin/videos/listVideo_admin");

					}else {
						header("location ".base_url()."videos/loi-insert_view");

					}
				}
				else{
					$data = array('upload_data' => $this->upload->data());
					echo "success";
					$file_ext = $data['upload_data']['file_ext'];
					//gọi model insert data vào DB
					$this->load->model('video_model');
					if($this->video_model->updateVideo($id, $title, $des, $time, $comment, $type, $category, $link, $thumb))
					{
						$new_thumb_name = strval($id)."_".strval($thumb).$file_ext;
						$old_path = $config['upload_path'].$thumb.$file_ext;
						$new_path = $config['upload_path'].$new_thumb_name;
						$new_target = $target_folder.$new_thumb_name;
						$this->load->model('video_model');
						$res = $this->video_model->updateThumbName($id, $new_thumb_name, $old_path, $new_path,$new_target);
						if($res){
							header("Location: ".base_url()."admin/videos/listVideo_admin");
						}
						else {
							header("location ".base_url()."videos/loi-insert_view");
						}
					} else {
						header("location ".base_url()."videos/loi-insert_view");
					}

				}
			}
		}
		

	}
	public function listVideo_admin($page = 0)
	{
		$this->load->model('video_model');
		$page_number_admin = $this->video_model->getPageNumber_admin();
		$data_video = $this->video_model->getPagePagi_admin($page);
		$data_video = array ('data_video'=>$data_video, 'page'=>$page_number_admin, 'active' =>$page);
		$this->load->view('videos/videoList_admin', $data_video, FALSE);

	}
	public function listVideoByCat($id = 0)
	{
		if(!$this->input->post()) {
			$this->load->model('category_model');
			$data = $this->category_model->getCat4Video();
			$data_video = array (
				'id'=>"",
				'title'=>"",
				'category_id'=>"",
				'link'=>"",
				'thumb'=>"",
				'time'=>"",
				'description'=>"",
				'type'=>"",
				'comment'=>""
			);
			$error = "Chọn ID rồi nhấn nút Gửi giùm tao mày ơi";
			$data_video = array (0=>$data_video);
			$data = array ('data_cat'=>$data, 'data_video'=>$data_video, "error"=>$error);
			$this->load->view('videos/videoListByCat_view', $data, FALSE);

		}
		else  {
			//lay ra category_id
			$catID = $this->input->post('category');
			$count = 0;
			$error = "";
			$this->load->model('category_model');
			$data_cat = $this->category_model->getCat4Video();
			foreach ($data_cat as $key => $value) {
				if ($value['id'] == $catID){
					$count = $count + 1;
				}
			}
			if ($count == 0) {
				$error = "Lỗi: Chưa có id này hoặc mày đã delete nó rồi mày ạ";
			}
			//lay ra video
			$this->load->model('video_model');
			$data_video = $this->video_model->getVideoByCatID($catID);
			$data = array('data_cat'=>$data_cat, 'data_video'=>$data_video,'error'=>$error);
			$this->load->view('videos/videoListByCat_view', $data, FALSE);
		}
	}
}

/* End of file  */
/* Location: ./application/controllers/ */