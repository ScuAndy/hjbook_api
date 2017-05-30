<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic record interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package CodeIgniter
 * @subpackage  Rest Server
 * @category    Controller
 * @author  Adam Whitney
 * @link    http://outergalactic.org/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';
class records extends REST_Controller
{
    
    function index_get($id = '')
    {
    
        
        $query = $this->db->query('SELECT * FROM record');
        // Example data for testing.
        $record = $query->result();
         
        //if (!$record_id) { $record_id = $this->get('record_id'); }
        if (!$id)
            
            {
                //$record = $this->record_model->getrecord();                        
                if($record)
                    $this->response($record, 200); // 200 being the HTTP response code
                else
                    $this->response(array('error' => 'Couldn\'t find any record!'), 404);
            }
        
        //$record = $this->record_model->getrecord($id);
        
        if ($id)
            {
            $query = $this->db->query('SELECT * FROM record WHERE record_id = '.$id);

            $record = $query->result();
            if($record)
                $this->response($record, 200); // 200 being the HTTP response code
            else
                $this->response(array('error' => 'record could not be found'), 404);
            } 
        if ($id == 0) $this->response(array('error' => 'record could not be found'), 404);
        
    }
    
    
    function index_post() 
    {
        if (func_num_args() != 0) $this->response(array('error' => 'cannot post with certain id'), 401);
        $data = $this->_post_args;
        try{
            $query = $this->db->query('INSERT INTO record (user_id, book_id, status, create_datetime) VALUES ("'.$data['user_id'].'", "'.$data['book_id'].'", 1, now())');
            } catch (Exception $e){
                $this->response(array('error' => $e->getMessage()), $e->getCode());
            }
        $new = $this->db->query('SELECT @@identity');
        $result = $new->result();
        //$this->response($result, 200);
        $new_id = ($result[0]->{'@@identity'});
        $query = $this->db->query('SELECT * FROM record WHERE record_id = '.$new_id);
        $record = $query->result();
            if($record)
                $this->response($record, 200); // 200 being the HTTP response code

        
        /*
        try {
            //$id = $this->record_model->createrecord($data);
            $id = 3; // test code
            //throw new Exception('Invalid request data', 400); // test code
            //throw new Exception('record already exists', 409); // test code
        } catch (Exception $e) {
            // Here the model can throw exceptions like the following:
            // * For invalid input data: new Exception('Invalid request data', 400)
            // * For a conflict when attempting to create, like a resubmit: new Exception('record already exists', 409)
            $this->response(array('error' => $e->getMessage()), $e->getCode());
        }
        if ($id) {
            $record = array('id' => $id, 'name' => $data['name']); // test code
            //$record = $this->record_model->getrecord($id);
            $this->response($record, 201); // 201 being the HTTP response code
        } else
            $this->response(array('error' => 'record could not be created'), 404);
        */
    }
    
    public function index_put($id = '')                                                         
    {
        $data = $this->_put_args;
        if ($id) {
            //存在问题 之前两个都可以为空的
            $query = $this->db->query('UPDATE record SET user_id = "'.$data['user_id'].'", book_id = "'.$data['book_id'].'", status = "'.$data['status'].'" WHERE record_id = '.$id);
            if ($data['status'] == 0) 
            {
                $query = $this->db->query('UPDATE record SET return_datetime = now() WHERE record_id = '.$id);
            }
            $query = $this->db->query('SELECT * FROM record WHERE record_id = '.$id);
            $record = $query->result();
            //$record = array('id' => $data['id'], 'name' => $data['name']); // test code
            //$record = $this->record_model->getrecord($id);
            $this->response($record, 200); // 200 being the HTTP response code
        } else
            $this->response(array('error' => 'record could not be found'), 404);

    }
        
    function index_delete($id = '')
    {
        if (!$id) { $id = $this->get('id'); }
        if (!$id)
        {
            $this->response(array('error' => 'An ID must be supplied to delete a record'), 400);
        }

        $query = $this->db->query('DELETE FROM record WHERE record_id ='.$id);
        

        if($query) {
            $this->response(array('message' => 'Delete OK!'), 200);
        } else
            $this->response(array('error' => 'record could not be found'), 404);
    }
    
}


