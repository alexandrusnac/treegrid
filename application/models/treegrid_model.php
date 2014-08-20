<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Treegrid_model extends CI_Model {
    public function select() {
        $sql = "SELECT `id`, `parent_id`, `name`, `description`, `order` FROM `data` ORDER BY `order` ASC";
        $query = $this->db->query($sql);

        $data = array();
        if ($query->num_rows() > 0) {
            $index = 0;

            foreach($query->result_array() as $row) {
                $data['rows'][$index] = array(
                    "id" => intval($row['id']),
                    "name" => $row['name'],
                    "description" => $row['description'],
                    "_parentId" => intval($row['parent_id']),
                    "order" => intval($row['order'])
                );

                $index++;
            }
        }

        echo json_encode($data);
    }

    public function insert($parent_id, $name, $description) {		
        $sql = "INSERT INTO `data` (`parent_id`, `name`, `description`, `order`) VALUES (?, ?, ?, ?)";
        $order = intval($this->get_max_order() + 1);
        if ($this->db->query($sql, array($parent_id, $name, $description, $order))) {
            echo $this->db->insert_id();
        } else {
            echo 0;
        }			
    }

    public function get_max_order() {
        $sql = "SELECT MAX(`order`) as `max_order` FROM `data`";
        $query = $this->db->query($sql);

        if($query->num_rows() > 0) {
            $row = $query->row_array();

            return $row['max_order'];
        } else {
            return 0;
        }
    }

    public function delete($id) {
        $sql = "DELETE FROM `data` WHERE `id` = ?";
        if ($this->db->query($sql, array($id))) {
                $this->delete_childs($id);

                echo 1;
        } else {
                echo 0;
        }
    }

    function delete_childs($parent_id) {
        $sql = "SELECT `id` FROM `data` WHERE `parent_id` = ?";
        $query = $this->db->query($sql, array($parent_id));

        if($query->num_rows() > 0) {
                $ids = array();
                foreach($query->result_array() as $row) {
                        $sql = "DELETE FROM `data` WHERE `id` = ?";
                        if (!$this->db->query($sql, array($row['id']))) {
                                echo 0;
                        }

                        $ids[] = $row['id'];
                }

                for($i = 0; $i < count($ids); $i++) {
                        $this->delete_childs($ids[$i]);
                }
        }
    }

    public function update($id, $name, $description) {
        if(strlen($name) > 50 || strlen($description) > 255) {
            echo 0;
        } else {
            $sql = "UPDATE `data` SET `name` = ?, `description` = ? WHERE `id` = ?";
            if($this->db->query($sql, array($name, $description, $id))) {
                echo 1;
            } else {
                echo 0;
            }
        }
    }

    public function drag_and_drop($id, $parent_id, $ids, $orders) {
        $sql = "UPDATE `data` SET `parent_id` = ? WHERE `id` = ?";
        if(!$this->db->query($sql, array($parent_id, $id))) {
            echo 0;
        }

        $ids_arr = explode(",", $ids);
        $orders_arr = explode(",", $orders);

        $nr_ids = count($ids_arr);
        if($nr_ids) {
            for($i = 0; $i < $nr_ids; $i++) {
                $sql = "UPDATE `data` SET `order` = ? WHERE `id` = ?";

                if(!$this->db->query($sql, array($orders_arr[$i], $ids_arr[$i]))) {
                    echo 0;
                }
            }

            echo 1;
        }
    }
}
