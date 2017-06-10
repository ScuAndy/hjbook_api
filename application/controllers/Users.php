<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
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
class users extends REST_Controller
{
    function index_get($id = '')
    {
        $query = $this->db->query('SELECT * FROM user');
        // Example data for testing.
        $user = $query->result();
         
        //if (!$user_id) { $user_id = $this->get('user_id'); }
        if (!$id)
            
            {
                //$user = $this->user_model->getuser();                        
                if($user)
                    $this->response($user, 200); // 200 being the HTTP response code
                else
                    $this->response(array('error' => 'Couldn\'t find any user!'), 404);
            }
        
        //$user = $this->user_model->getuser($id);
        
        if ($id)
            {
            $query = $this->db->query('SELECT * FROM user WHERE user_id = '.$id);

            $user = $query->result();
            if($user)
                $this->response($user, 200); // 200 being the HTTP response code
            else
                $this->response(array('error' => 'user could not be found'), 404);
            } 
        if ($id == 0) $this->response(array('error' => 'user could not be found'), 404);
    }
    
    function index_post() 
    {
        if (func_num_args() != 0) $this->response(array('error' => 'cannot post with certain id'), 401);
        $data = $this->_post_args;
        try{
            $query = $this->db->query('INSERT INTO user (user_name, password, real_name, user_email, auth) VALUES ("'.$data['user_name'].'", "'.$data['password'].'","'.$data['real_name'].'","'.$data['user_email'].'", 0)');
            } catch (Exception $e){
                $this->response(array('error' => $e->getMessage()), $e->getCode());
            }
        $new = $this->db->query('SELECT @@identity');
        $result = $new->result();
        //$this->response($result, 200);
        $new_id = ($result[0]->{'@@identity'});
        $query = $this->db->query('SELECT * FROM user WHERE user_id = '.$new_id);
        $user = $query->result();
            if($user)
                $this->response($user, 200); // 200 being the HTTP response code

        
        /*
        try {
            //$id = $this->user_model->createuser($data);
            $id = 3; // test code
            //throw new Exception('Invalid request data', 400); // test code
            //throw new Exception('user already exists', 409); // test code
        } catch (Exception $e) {
            // Here the model can throw exceptions like the following:
            // * For invalid input data: new Exception('Invalid request data', 400)
            // * For a conflict when attempting to create, like a resubmit: new Exception('user already exists', 409)
            $this->response(array('error' => $e->getMessage()), $e->getCode());
        }
        if ($id) {
            $user = array('id' => $id, 'name' => $data['name']); // test code
            //$user = $this->user_model->getuser($id);
            $this->response($user, 201); // 201 being the HTTP response code
        } else
            $this->response(array('error' => 'user could not be created'), 404);
        */
    }
    
    public function index_put($id = '')                                                         
    {
        $data = $this->_put_args;
        if ($id) {
            //存在问题 之前两个都可以为空的
            //是否可以修改名字未知，暂时可以
            $query = $this->db->query('UPDATE user SET user_name = "'.$data['user_name'].'", password = "'.$data['password'].'" WHERE user_id = '.$id);
            $query = $this->db->query('SELECT * FROM user WHERE user_id = '.$id);
            $user = $query->result();
            //$user = array('id' => $data['id'], 'name' => $data['name']); // test code
            //$user = $this->user_model->getuser($id);
            $this->response($user, 200); // 200 being the HTTP response code
        } else
            $this->response(array('error' => 'user could not be found'), 404);

    }
        
    function index_delete($id = '')
    {
        if (!$id) { $id = $this->get('id'); }
        if (!$id)
        {
            $this->response(array('error' => 'An ID must be supplied to delete a user'), 400);
        }

        $query = $this->db->query('DELETE FROM user WHERE user_id ='.$id);
        

        if($query) {
            $this->response(array('message' => 'Delete OK!'), 200);
        } else
            $this->response(array('error' => 'user could not be found'), 404);
    }
}


