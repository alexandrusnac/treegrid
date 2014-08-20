<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Treegrid extends CI_Controller {
    public function index(){
        $this->load->view('treegrid_view');
    }

    public function select() {
        $this->load->model('treegrid_model');
        $this->treegrid_model->select();
    }

    public function edit() {
        $operation = isset($_POST['oper'])?$_POST['oper']:'';
        $oper_arr = array("add", "del", "edit", "dnd");
        $key = array_search($operation, $oper_arr);

        if(isset($oper_arr[$key])) {
                switch($oper_arr[$key]) {
                        case "add" :
                                $this->load->model('treegrid_model');

                                // Получение данных
                                $parent_id = isset($_POST['parent_id'])?intval($_POST['parent_id']):0;
                                $name = isset($_POST['name'])?$_POST['name']:'';
                                $description = isset($_POST['description'])?$_POST['description']:'';

                                $this->treegrid_model->insert($parent_id, $name, $description);
                                break;
                        case "del" :
                                $this->load->model('treegrid_model');

                                // Получение данных
                                $id = isset($_POST['id'])?intval($_POST['id']):0;

                                $this->treegrid_model->delete($id);
                                break;
                        case "edit" :
                                $this->load->model('treegrid_model');

                                // Получение данных
                                $id = isset($_POST['id'])?intval($_POST['id']):0;
                                $name = isset($_POST['name'])?$_POST['name']:'';
                                $description = isset($_POST['description'])?$_POST['description']:'';

                                $this->treegrid_model->update($id, $name, $description);
                                break;
                        case "dnd" :
                                $this->load->model('treegrid_model');

                                // Получение данных
                                $id = isset($_POST['id'])?intval($_POST['id']):0;
                                $parent_id = isset($_POST['parent_id'])?intval($_POST['parent_id']):0;
                                $ids = isset($_POST['ids'])?$_POST['ids']:'';
                                $parent_ids = isset($_POST['orders'])?$_POST['orders']:'';

                                $this->treegrid_model->drag_and_drop($id, $parent_id, $ids, $parent_ids);
                                break;
                }
        }
    }
}
