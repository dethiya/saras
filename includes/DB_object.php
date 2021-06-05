<?php

/**
 * 
 */
class Db_object
{
	
    public $errors = array();

    public $upload_errors_array = array(


        UPLOAD_ERR_OK           => "There is no error",
        UPLOAD_ERR_INI_SIZE     => "The uploaded file exceeds the upload_max_filesize directive in php.ini",
        UPLOAD_ERR_FORM_SIZE    => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form",
        UPLOAD_ERR_PARTIAL      => "The uploaded file was only partially uploaded.",
        UPLOAD_ERR_NO_FILE      => "No file was uploaded.",               
        UPLOAD_ERR_NO_TMP_DIR   => "Missing a temporary folder.",
        UPLOAD_ERR_CANT_WRITE   => "Failed to write file to disk.",
        UPLOAD_ERR_EXTENSION    => "A PHP extension stopped the file upload."                   
                                                

);


    public static function all()
    {
        global $database;
        return static::find_this_query("SELECT * FROM " .static::$db_table." ORDER BY id ASC");
    }

    public static  function select($rows='*',$where=null,$order=null,$limit=null)
    {
        global $database;

//        $where_array=buildWhereConditions($where);
        $sql="SELECT ".$rows." FROM ".static::$db_table;
        if ($where!=null)
        {
            $sql.=" WHERE ".$where;
        }
        if ($order!=null)
        {
            $sql.=" ORDER BY ".$order;
        }
        if($limit!=null)
        {
            $sql.= " LIMIT ".$limit;
        }

        return static::find_this_query($sql);

    }

    public static function find($id)
    {
        global $database;
        $the_result_array= static::find_this_query("SELECT * FROM ".static::$db_table." WHERE id='$id' LIMIT 1");
        return !empty($the_result_array) ? array_shift($the_result_array) : false;
        
    }

    public static function find_this_query($sql)
    {
        global $database;
        $result_set = $database->query($sql);

        $the_object_array=array();
        while ($row = mysqli_fetch_array($result_set)) {
            $the_object_array[]=static::instantiation($row);
        }
        return $the_object_array;
    }


    



 public static function instantiation($the_record)
    {
        $calling_class = get_called_class(); 
        $the_object = new $calling_class;
        

        foreach ($the_record as $the_attribute => $value) {
            if ($the_object->has_the_attribute($the_attribute)) {
                $the_object->$the_attribute = $value;
            }
        }

        return $the_object;
    }

    private function has_the_attribute($the_attribute){
        $object_properties=get_object_vars($this);
        return array_key_exists($the_attribute,$object_properties);
    }
    
    
    protected function properties()
    {
        // return get_object_vars($this);
        
        $properties = array();

        foreach (static::$db_table_fields as $db_field ) {
            if (property_exists($this, $db_field)) {
                
                $properties[$db_field]=$this->$db_field;

            }
        }
        return $properties;
    }

    protected function clean_properties()
    {
        global $database;
        $clean_properties = array();
        foreach ($this->properties() as $key => $value) {
            $clean_properties[$key]=$database->escape_string($value);
        }
        return $clean_properties;
    }


    public function save()
    {
        return isset($this->id)? $this->update() : $this->create();
    }
    
    public function create()
    {

        global $database;
        $properties=$this->clean_properties();

        $sql= "INSERT INTO ".static::$db_table."(". implode(",", array_keys($properties)).")";
        $sql.= "VALUES ('". implode("','", array_values($properties))  ."')";
        
    
        if($database->query($sql))
        {
            $this->id=$database->the_insert_id();
            return true;
        }else
        { 
            return false;
        }
        
    }//create
    

    
    
    public function update()
    {
        global $database;

        $properties=$this->clean_properties();

        $properties_pairs=array();


        foreach ($properties as $key => $value) {
            $properties_pairs[]="{$key}='{$value}'";
        }


        $sql="UPDATE ".static::$db_table." SET ";
        $sql.=implode(", " , $properties_pairs);
        $sql.=" WHERE id=".$database->escape_string($this->id);
        
        
        $database->query($sql);
        
        return (mysqli_affected_rows($database->connection)==1) ? true : false;
        
    }//update method
    
    public function delete()
    {
        global $database;
        $sql="DELETE FROM ".static::$db_table." ";
        $sql.="WHERE id=".$database->escape_string($this->id);
        $sql.=" LIMIT 1";
        
        $database->query($sql);
        return (mysqli_affected_rows($database->connection)==1) ? true : false;
    }


    //upload profile image




    public function set_file($file) { 

        if(empty($file) || !$file || !is_array($file)) {
        $this->errors[] = "There was no file uploaded here";
        return false;

        }elseif($file['error'] !=0) {

        $this->errors[] = $this->upload_errors_array[$file['error']];
        return false;

        } else {


        $this->user_image =  basename($file['name']);
        $this->tmp_path = $file['tmp_name'];
        $this->type     = $file['type'];
        $this->size     = $file['size'];
        }

}

public function set_dl($file) { 

    if(empty($file) || !$file || !is_array($file)) {
    $this->errors[] = "There was no file uploaded here";
    return false;

    }elseif($file['error'] !=0) {

    $this->errors[] = $this->upload_errors_array[$file['error']];
    return false;

    } else {


    $this->dl_scanned_copy =  basename($file['name']);
    $this->tmp_path = $file['tmp_name'];
    $this->type     = $file['type'];
    $this->size     = $file['size'];
    }

}







        public static function count_all()
        {
            global $database;
            $sql="SELECT COUNT(*) FROM ".static::$db_table;
            $result_set=$database->query($sql);
            $row=mysqli_fetch_array($result_set);
            return array_shift($row);
        }

        public static function count_by_users($user)
        {
            global $database;
            $sql="SELECT COUNT(*) FROM ".static::$db_table." WHERE id_user='$user'";
            $result_set=$database->query($sql);
            $row=mysqli_fetch_array($result_set);
            return array_shift($row);
        }


    public static function new_allotments()
    {
        global $database;
        $sql="SELECT COUNT(*) FROM ".static::$db_table." WHERE allotment_dt=CURDATE()";
        $result_set=$database->query($sql);
        $row=mysqli_fetch_array($result_set);
        return array_shift($row);
    }

    public static function local_allotments($delr,$status)
    {
        global $database;
        $sql="SELECT COUNT(*) FROM ".static::$db_table." WHERE allot_status_id='$status' AND delr='$delr'";
        $result_set=$database->query($sql);
        $row=mysqli_fetch_array($result_set);
        return array_shift($row);

    }

    public static function get_results($search)
    {
        global $database;
        $sql="SELECT * FROM dispatches WHERE chassis_no LIKE '%$search%' OR engine_no LIKE '%$search%' OR `customer_name` LIKE '%$search%' OR model_code LIKE '%$search%'";
        return static::find_this_query($sql);
    }

}