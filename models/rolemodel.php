<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Rolemodel extends CI_Model 
{

	function __construct()
	{
		parent::__construct();

		$this->load->library('Appunto_auth');
        $this->load->database();

		$prefix = $this->config->item('db_table_prefix','appunto_auth');

        $this->table = $prefix.'auth_role';
        $this->permission_table = $prefix.'auth_permission';
        $this->role_permission_table = $prefix.'auth_role_permission';
	}
    

	/**
	 * Get roles
	 *
	 * @return	object
	 */
	function enumerate($offset,$rows,$sort,$dir,$filters)
	{
        // define table here for count all results
        $this->db->from($this->table);
        $total = $this->db->count_all_results();  

        $this->db->select('role.id, role.name,role.description');

        if (!empty($sort) && !empty($dir)) 
        {
            $this->db->order_by('UPPER('.$sort.')',$dir);
		}
		else
		{
        	$this->db->order_by('role.name','ASC');
        }

        // execute query
		$query = $this->db->get($this->table. ' role');

		// format and return result to controller
        return $this->appunto_auth->formatQueryResult($query,$total);
	}

	/**
	 * Get role
	 *
	 * @return role
	 */
	function get($id)
	{
		// define query
        $this->db->select('role.id, role.name,role.description');

		$this->db->where('role.id',$id);

        // execute query
		$query = $this->db->get($this->table.' role');

		// format and return result to controller
        return $query->result();

	}

	/**
	 * Get role permissions
	 *
	 * @return	object
	 */
	function get_role_permissions($id,$sort,$dir)
	{
        $this->db->select('if(isnull(rp.role_id),0,1) as inRole',false);
        $this->db->select('p.id, p.name, p.description');

        $this->db->join($this->permission_table.' p','p.id = rp.permission_id and rp.role_id ='.$id,'right');

        // execute query
		$query = $this->db->get($this->role_permission_table.' rp');

        return $this->appunto_auth->formatQueryResult($query,$query->num_rows());
	}

	/**
	 * Get user roles
	 *
	 * @return	object
	 */
	function get_role_permission($permission_id,$role_id)
	{
        $this->db->select('if(isnull(rp.role_id),0,1) as inRole',false);
        $this->db->select('p.id, p.name, p.description');

        $this->db->join($this->role_permission_table.' rp','p.id = rp.permission_id and rp.role_id ='.$role_id,'left');

		$this->db->where('p.id',$permission_id);

        // execute query
		$query = $this->db->get($this->permission_table.' p');

        return $query->row() ;
	}

	/**
	 * Create record
	 *
	 * @param	array
	 * @return	object
	 */
	function create_record($data) 
	{
        // execute query
		$query = $this->db->insert($this->table, $data);

		// get the id to select a full record to return to UI
		$id = $this->db->insert_id();

        // return formatted result
        return $this->appunto_auth->formatOperationResult($query,$this->get($id));
	}

	/**
	 * Update record
	 *
	 * @param	array
	 * @return	object
	 */
	function update_record($data) 
	{
        // get/set the id and remove it from the data array
		$id = $data['id'];
        $this->db->where('id', $id);
        unset($data['id']);

        // execute query
		$query = $this->db->update($this->table, $data);

        // return formatted result
        return $this->appunto_auth->formatOperationResult($query,$this->get($id));
	}

	/**
	 * Delete record
	 *
	 * @param	array
	 * @return	object
	 */
	function delete_record($data) 
	{
        // get/set the id 
        $this->db->where('id', $data['id']);

        // execute query
		$query = $this->db->delete($this->table);

        // return formatted result
        return $this->appunto_auth->formatOperationResult($query);
	}

	/**
	 * Add a permission to a role 
	 *
	 * @param	array
	 * @return	object
	 */
	function add_permission($permission_id,$role_id) 
	{
        // execute query
		$query = $this->db->insert($this->role_permission_table, array(
			'permission_id'	=> $permission_id,
			'role_id'	=> $role_id
		));

        // return formatted result
        return $this->appunto_auth->formatOperationResult($query,$this->get_role_permission($permission_id,$role_id));
	}

	/**
	 * Remove a permission from a role
	 *
	 * @param	array
	 * @return	object
	 */
	function remove_permission($permission_id,$role_id) 
	{
        // execute query
		$query = $this->db->delete($this->role_permission_table, array(
			'permission_id'	=> $permission_id,
			'role_id'	=> $role_id
		));

        // return formatted result
        return $this->appunto_auth->formatOperationResult($query,$this->get_role_permission($permission_id,$role_id));
	}
};
