<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Issue extends CI_Controller {


	function __construct()
	{
		parent::__construct();

		$this->load->library('MY_Requests');
	}

	public function index()
	{

		$this->lists();
	}

	public function lists_ajax($type = 'all')
	{
		$order = '';
		$sort = array();

		$start = ( !empty($this->input->post('start')) ) ? $this->input->post('start') : '';
		$length = ( !empty($this->input->post('length')) ) ? $this->input->post('length') : 10;	

		$TotalRecords = 10;

		$filter_issue_id = '';
		$filter_subject = '';
		$filter_issue = '';

		if ($this->input->post('issue_id') != '' ) {
			$filter_issue_id = $this->input->post('issue_id');
		}

		if ($this->input->post('subject') != '' ) {
			$filter_subject = $this->input->post('subject');
		}

		if ($this->input->post('issue') != '' ) {
			$filter_issue = $this->input->post('issue');
		}

		$headers = array(
			'AMT-API-KEY' => 'g8gkgo0sw0w44gkos4o40ww0g88c0cwwsc4c8sk0',
			'AMT-JWT' => $this->session->userdata('token')
		);

		switch ($type) {
			case 'all':
				$request = Requests::get(api_url("issue/outlet/all?outlet_id=".$this->session->userdata('outlet_id')."&issue_id=$filter_issue_id&subject=$filter_subject&issue=$filter_issue&page=$start&per_page=$length"), $headers);
				break;
			case 'open':
				$request = Requests::get(api_url("issue/outlet/open?outlet_id=".$this->session->userdata('outlet_id')."&issue_id=$filter_issue_id&subject=$filter_subject&issue=$filter_issue&page=$start&per_page=$length"), $headers);
				break;
			case 'pending':
				$request = Requests::get(api_url("issue/outlet/pending?outlet_id=".$this->session->userdata('outlet_id')."&issue_id=$filter_issue_id&subject=$filter_subject&issue=$filter_issue&page=$start&per_page=$length"), $headers);
				break;
			case 'progress':
				$request = Requests::get(api_url("issue/outlet/progress?outlet_id=".$this->session->userdata('outlet_id')."&issue_id=$filter_issue_id&subject=$filter_subject&issue=$filter_issue&page=$start&per_page=$length"), $headers);
				break;
			case 'history':
				$request = Requests::get(api_url("issue/outlet/history?outlet_id=".$this->session->userdata('outlet_id')."&issue_id=$filter_issue_id&subject=$filter_subject&issue=$filter_issue&page=$start&per_page=$length"), $headers);
				break;
			default:
				$request = Requests::get(api_url("issue/outlet/all?outlet_id=".$this->session->userdata('outlet_id')."&issue_id=$filter_issue_id&subject=$filter_subject&issue=$filter_issue&page=$start&per_page=$length"), $headers);
				break;
		}
		

		$results = json_decode($request->body);
		
		if ( $request->status_code != 200 || !$results->status ) {
			return false;
		}

		$TotalRecords = $results->pagination->total_record;

		$TotalRecordsFiltered = $TotalRecords;
  		
	    $sEcho = intval($this->input->post('draw'));

	    $records = array();
	    $records['data'] = array();
	    
	    if (!empty($results->data)) {
			$no = $start;

			foreach($results->data as $d) {
				$no++;

				$attachment = '';
				if (!empty($d->attachment)) {
					$attachment_array = explode(";", $d->attachment);
					for ($i=0; $i < count($attachment_array); $i++) { 
						$img_url = api_url('uploads/issue/'.$attachment_array[$i]);
						$attachment .= '<a data-toggle="modal" href="#show_image" data-id="'.$img_url.'"  class="open-ImageDialog"><img src="'.$img_url.'" height="50"/></a><br/><br/>';
					}
				}

				$attachment_checkout = '';
				if (!empty($d->attachment_checkout)) {
					$attachment_checkout_array = explode(";", $d->attachment_checkout);
					for ($i=0; $i < count($attachment_checkout_array); $i++) { 
						$img_url = api_url('uploads/issue/'.$attachment_checkout_array[$i]);
						$attachment_checkout .= '<a data-toggle="modal" href="#show_image" data-id="'.$img_url.'"  class="open-ImageDialog"><img src="'.$img_url.'" height="50"/></a><br/><br/>';
					}
				}

				switch ($type) {
					case 'all':
						$records['data'][] = array(
							$no,
							$d->issue_id,
							$d->date_request,
							$d->subject,
							$d->issue,
							$attachment,
							$d->staff_name,
							strtoupper($d->status)
						);
						break;
					case 'open':
						$records['data'][] = array(
							$no,
							$d->issue_id,
							$d->date_request,
							$d->subject,
							$d->issue,
							$attachment,
							$d->staff_name
						);
						break;
					case 'pending':
						$records['data'][] = array(
							$no,
							$d->issue_id,
							$d->date_request,
							$d->subject,
							$d->issue,
							$attachment
						);
						break;
					case 'progress':
						$records['data'][] = array(
							$no,
							$d->issue_id,
							$d->date_request,
							$d->subject,
							$d->issue,
							$attachment,
							$d->staff_name,
							$d->date_checkin
						);
						break;
					case 'history':
						$records['data'][] = array(
							$no,
							$d->issue_id,
							$d->date_request,
							$d->subject,
							$d->issue,
							$attachment,
							$d->staff_name,
							$d->date_checkin,
							$d->date_checkout,
							$d->note,
							$attachment_checkout
						);
						break;
					default:
						$records['data'][] = array(
							$no,
							$d->issue_id,
							$d->date_request,
							$d->subject,
							$d->issue,
							$attachment,
							$d->staff_name,
							strtoupper($d->status)
						);
						break;
				}
				
			}
		}

		$records["draw"] = $sEcho;
		$records["recordsTotal"] = $TotalRecords;
		$records["recordsFiltered"] = $TotalRecordsFiltered;

		echo json_encode($records);
	}

	public function lists()
	{
		$data['title'] = 'List Ticket';

		if( !empty($this->session->flashdata('message')) )
		{
			$data['alert'] = $this->session->flashdata('message');
		}

		$datac = array(
			'title'	=> 'All Tickets',
			'table_url'	=> base_url('issue/lists_ajax/all')
		);

		$this->parser->set_partial('header','partials/lists-header');
		$this->parser->set_partial('footer','partials/lists-footer');
		$this->parser->set_partial('content','lists',$datac);

		$this->parser->parse('default',$data);
	}

	public function open()
	{
		$data['title'] = 'List Ticket';

		if( !empty($this->session->flashdata('message')) )
		{
			$data['alert'] = $this->session->flashdata('message');
		}

		$datac = array(
			'title'	=> 'Open Tickets',
			'table_url'	=> base_url('issue/lists_ajax/open')
		);

		$this->parser->set_partial('header','partials/lists-header');
		$this->parser->set_partial('footer','partials/lists-footer');
		$this->parser->set_partial('content','lists-open',$datac);

		$this->parser->parse('default',$data);
	}

	public function pending()
	{
		$data['title'] = 'List Ticket';

		if( !empty($this->session->flashdata('message')) )
		{
			$data['alert'] = $this->session->flashdata('message');
		}

		$datac = array(
			'title'	=> 'Pending Tickets',
			'table_url'	=> base_url('issue/lists_ajax/pending')
		);

		$this->parser->set_partial('header','partials/lists-header');
		$this->parser->set_partial('footer','partials/lists-footer');
		$this->parser->set_partial('content','lists-pending',$datac);

		$this->parser->parse('default',$data);
	}

	public function progress()
	{
		$data['title'] = 'List Ticket';

		if( !empty($this->session->flashdata('message')) )
		{
			$data['alert'] = $this->session->flashdata('message');
		}

		$datac = array(
			'title'	=> 'In Progress Tickets',
			'table_url'	=> base_url('issue/lists_ajax/progress')
		);

		$this->parser->set_partial('header','partials/lists-header');
		$this->parser->set_partial('footer','partials/lists-footer');
		$this->parser->set_partial('content','lists-progress',$datac);

		$this->parser->parse('default',$data);
	}

	public function solved()
	{
		$data['title'] = 'List Ticket';

		if( !empty($this->session->flashdata('message')) )
		{
			$data['alert'] = $this->session->flashdata('message');
		}

		$datac = array(
			'title'	=> 'Solved Tickets',
			'table_url'	=> base_url('issue/lists_ajax/history')
		);

		$this->parser->set_partial('header','partials/lists-header');
		$this->parser->set_partial('footer','partials/lists-footer');
		$this->parser->set_partial('content','lists-history',$datac);

		$this->parser->parse('default',$data);
	}

	public function add()
	{
		$data['title'] = 'Form Open Ticket';

		$datac = array(
			'title' 	=> 'Form Open Ticket',
			'action'	=> 'add',
			'api_url'	=> api_url('issue/outlet/insert'),
			'token'		=> $this->session->userdata('token')
		);

		$this->parser->set_partial('header','partials/form-header');
		$this->parser->set_partial('footer','partials/form-footer');
		$this->parser->set_partial('content','form',$datac);

		$this->parser->parse('default',$data);
	}
}
