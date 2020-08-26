<?php
class Database extends PDO
{
  protected $_arrFields;
  protected $_arrSearch;
  protected $_table;
  protected $_Pkey;
  protected $_sequence;
  private $_msgError;
  private $_driver;
  private $_objPdo;
  private $_parameters;
  private $_db_debug;
  private $db_name = 'payroll';
  private $db_host = '72.137.218.210';
  private $db_user = 'developers';
  private $db_pass = 'LHdFgZRp0oOG4SdT';
  private $driver = "mysql";
  private $port_db = "3306";
  private $db_char = 'utf8';
  public $query;

  public function __construct($test = false)
  {
    try {
      parent::__construct(
        $this->driver . ":host=" . $this->db_host . ";dbname=" . $this->db_name,
        $this->db_user,
        $this->db_pass,
        array(
          PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES ' . $this->db_char
        )
      );
    } catch (PDOException $Error) {
      if ($test) {
        $this->_msgError = $Error->getMessage();
      } else {
        echo "<p><strong>Error sql message:</strong><br />";
        echo "Config database Error:<br />" . $Error->getMessage() . "</p>";
        die();
      }
    }
  }

  /**
   * Return true or false for sql statement.
   *
   * @param [sql]			SQL statement
   * @param [inputArr]	input bind array
   *
   * @return [flag]		boolean
   */
  public function ExecuteSql($sql, $inputArr = array())
  {
    try {
      $this->_parameters = $inputArr;
      $this->_objPdo = $this->prepare($sql);
      $result = $this->_objPdo->execute($this->_parameters);
      $this->_objPdo->closeCursor();
      $this->debug();
      //echo $sql;
      if (!$result) {
        $errorMsg =  $this->_objPdo->errorInfo();
        throw new Exception('{"status":"Error","message":"No se pudo completar el comando"}', 1);
      }
    } catch (\Throwable $th) {
      die($th->getMessage());
    }
    return $result;
  }


  /**
   * Return true or false for sql statement.
   *
   * @param [sql]			SQL statement
   * @param [inputArr]	input bind array
   *
   * @return [flag]		boolean
   */
  public function ExecuteAutoSQL(string $mode, string $table, array $columns, array $dataSQL, $inputArr = array(), $exclude = array())
  {
    try {
      switch ($mode) {
        case 'INSERT':
          $values = array();
          $valueColumn = array();

          // krsort($dataSQL);
          // krsort($columns);

          // $fields = $values = array();
          // if (!is_array($exclude)) $exclude = array($exclude);
          // foreach (array_keys($dataSQL) as $key) {
          //   if (!in_array($key, $exclude)) {
          //     $fields[] = "`$key`";
          //     $values[] = "'" . $dataSQL[$key] . "'";
          //   }
          // }
          // $fields = implode(",", $fields);
          // $values = implode(",", $values);
          // $querySql = "INSERT INTO `$table` ($fields) VALUES ($values)";
          // $values = array();
          // break;

          foreach ($dataSQL as $k => $v) {
            $values[':' . $k] = $v;
          }

          foreach ($columns as $key => $value) {
            $required = $value["required"] ?? false;
            $contain = array_key_exists($key, $dataSQL); //isset($data["$key"])
            $recivedRequired =  $contain == $required;
            $recivedNoRequired =  $contain == !$required;

            if ($required) {
              if ($recivedRequired) {
                // echo " $key recibida datos require \n";
                $valueColumn[':' . $key] = $value;
              } else {
                throw new Exception("$key Field is required", 400);
              }
            } else {
              if (!$required == !$contain) {
                continue;
              }
              if ($recivedNoRequired) {
                // echo " $key recibida datos no require \n";
                $valueColumn[':' . $key] = $value;
              }
            }
          }

          krsort($dataSQL);
          krsort($valueColumn);
          krsort($values);

          $querySql = "INSERT INTO $table ( " . implode(', ', array_keys($dataSQL)) . ") VALUES (" . implode(', ', array_keys($valueColumn)) . ")";

          break;

        case 'UPDATE':
          $dataSQL = array_filter($dataSQL, function ($value) {
            return null !== $value;
          });
          if (!array_key_exists('_documentID', $dataSQL)) {
            throw new Exception("Required documentID for update SQL", 400);
          }
          if (!isset($dataSQL[$dataSQL['_documentID']])) {
            throw new Exception("Required Value documentID", 400);
          }

          $querySql = "UPDATE $table SET";
          $values = array();
          krsort($dataSQL);
          krsort($columns);

          // foreach ($dataSQL as $key => $value) {
          //   if ($key == '_documentID') {
          //     $pointWhere = $value;
          //     continue;
          //   }
          //   if (isset($pointWhere)) {
          //     if ($key == $pointWhere) {
          //       continue;
          //     }
          //   }
          //   $querySql .=  " $key = '$value',"; // the :$key part is the placeholder, e.g. :zip
          //   // $values[':' . $key] = $value; // save the placeholder
          // }

          foreach ($dataSQL as $key => $value) {
            if ($key == '_documentID') {
              $pointWhere = $value;
              continue;
            }
            if (isset($pointWhere)) {
              if ($key == $pointWhere) {
                continue;
              }
            }
            $querySql .= ' ' . $key . ' = :' . $key . ','; // the :$key part is the placeholder, e.g. :zip
            $values[':' . $key] = $value; // save the placeholder
          }

          $querySql = substr($querySql, 0, -1); // remove last , and add a ;

          foreach ($dataSQL as $key => $value) {
            if ($key == $pointWhere) {
              $documentID = "$key = $value";
            }
          }
          $querySql .= " WHERE $documentID;";
          break;
        case 'SELECT':
          unset($dataSQL['url']);
          if (isset($dataSQL['lastID'])) {
            $values = array();
            foreach ($columns as $key => $value) {
              $primaryKey = isset($value["primaryKey"]);
              if ($primaryKey) {
                $columnNamePK =  $key;
                $documentID = ":$key";
                $values[':' . $key] = $dataSQL['lastID'];
              }
            }
            $querySql = "SELECT * FROM $table WHERE $columnNamePK = $documentID;";
            $this->_objPdo = $this->prepare($querySql);
            $this->_objPdo->execute($values);
            $outputArr = $this->_objPdo->fetch(PDO::FETCH_ASSOC);
          } elseif (count($dataSQL) > 1) {
            throw new Exception("Error Processing Request for many parameters [WIP]", 405);
          } elseif (count($dataSQL) == 1) {
            foreach ($dataSQL as $key => $value) {
              $columnNamePK =  $key;
              $documentID = ":$key";
              $values[':' . $key] = $dataSQL["$key"];
            }
            $querySql = "SELECT * FROM $table WHERE $columnNamePK = $documentID;";
            $this->_objPdo = $this->prepare($querySql);
            $this->_objPdo->execute($values);
            $outputArr = $this->_objPdo->fetch(PDO::FETCH_ASSOC);
          } elseif (count($dataSQL) == 0) {
            $querySql = "SELECT * FROM $table";
            $this->_objPdo = $this->prepare($querySql);
            $this->_objPdo->execute();
            $outputArr = $this->_objPdo->fetchAll(PDO::FETCH_ASSOC);
          }

          $this->_objPdo->closeCursor();
          $this->debug();
          $errorMsg =  $this->_objPdo->errorInfo();

          if (isset($errorMsg[1])) {
            throw new Exception("No se pudo completar el comando SQL: $errorMsg[2]", 501);
          }

          if ($outputArr == null) {
            return "No registry found";
          }

          return $outputArr;

        case 'DELETE':
          $values = array();

          foreach ($columns as $key => $value) {
            $primaryKey = isset($value["primaryKey"]);
            if ($primaryKey) {
              if (!isset($dataSQL["$key"])) {
                throw new Exception("Required Value $key", 400);
              }
              $columnNamePK =  $key;
              $documentID = ":$key";
              $values[':' . $key] = $dataSQL["$key"];
            }
          }
          $querySql = "DELETE FROM $table WHERE $columnNamePK = $documentID;";
          break;

        default:
          throw new Exception("Error Processing Mode Server Request", 405);
          break;
      }
      $this->_objPdo = $this->prepare($querySql);
      $result = $this->_objPdo->execute($values);
      $this->_objPdo->closeCursor();
      $this->debug();
      if (!$result) {
        $errorMsg =  $this->_objPdo->errorInfo();
        throw new Exception("No se pudo completar el comando SQL: $errorMsg[2]", 501);
      }
    } catch (\Throwable $th) {
      throw new Exception($th->getMessage(), $th->getCode());
    }
    return $result;
  }


  /**
   * Return full array.
   *
   * @param [sql]			SQL statement
   * @param [inputArr]	input bind array
   *
   * @return [outputArr] 	array
   */
  public function GetAll($sql, $inputArr = array())
  {
    $this->_parameters = $inputArr;
    $this->_objPdo = $this->prepare($sql);
    $this->_objPdo->execute($this->_parameters);
    $outputArr = $this->_objPdo->fetchAll(PDO::FETCH_ASSOC);
    $this->_objPdo->closeCursor();
    $this->debug();
    if ($outputArr == null) {
      return "No registry found";
    }
    return $outputArr;
  }

  /**
   * Return first element of first row of sql statement.
   *
   * @param [sql]			SQL statement
   * @param [inputArr]		input bind array
   *
   * @return [value]		data field value
   */
  public function GetOne($sql, $inputArr = array())
  {
    $this->_parameters = $inputArr;
    $this->_objPdo = $this->prepare($sql);
    $this->_objPdo->execute($this->_parameters);
    $value = $this->_objPdo->fetchColumn();
    $this->_objPdo->closeCursor();
    $this->debug();
    if ($value == null) {
      return "No registry found";
    }
    return $value;
  }

  /**
   * Return first row of sql statement.
   *
   * @param [sql]			SQL statement
   * @param [inputArr]	input bind array
   *
   * @return [outputArr]	array data
   */
  public function GetRow($sql, $inputArr = array())
  {
    $this->_parameters = $inputArr;
    $this->_objPdo = $this->prepare($sql);
    $this->_objPdo->execute($this->_parameters);
    $outputArr = $this->_objPdo->fetch(PDO::FETCH_ASSOC);
    $this->_objPdo->closeCursor();
    $this->debug();
    if ($outputArr == null) {
      return "No registry found";
    }
    return $outputArr;
  }

  /**
   * return whole recordset as a 2-dimensional associative array if there are more than 2 columns.
   * The first column is treated as the key and is not included in the array.
   * If there is only 2 columns, it will return a 1 dimensional array of key-value pairs.
   *
   * @param [sql]			SQL statement
   * @param [inputArr]	input bind array
   *
   * @return an associative array indexed by the first column of the array,
   * 	or false if the data has less than 2 cols.
   */
  public function GetAssoc($sql, $inputArr = array())
  {
    $this->_parameters = $inputArr;
    $this->_objPdo = $this->prepare($sql);
    $this->_objPdo->execute($this->_parameters);
    $numCols = $this->_objPdo->columnCount();
    if ($numCols > 1) {
      $outputArr = array();
      $row = $this->_objPdo->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      while ($row) {
        $cols = 1;
        if ($numCols == 2) {
          foreach ($row as $key => $value) {
            if ($cols == 1) {
              $llave = $value;
              $cols++;
            } else {
              $outputArr[$llave] = $value;
            }
          }
        } else {
          foreach ($row as $key => $value) {
            if ($cols == 1) {
              $llave = $value;
              $cols++;
            } else {
              $temp[$key] = $value;
            }
          }
          reset($temp);
          $outputArr[$llave] = $temp;
        }
        $row = $this->_objPdo->fetch(PDO::FETCH_ASSOC, PDO::FETCH_ORI_NEXT);
      }
    } else {
      $outputArr = false;
    }
    $this->_objPdo->closeCursor();
    $this->debug();
    return $outputArr;
  }

  /**
   * Will select, getting rows from $offset (1-based), for $nrows.
   * This simulates the MySQL "select * from table limit $offset,$nrows" , and
   * the PostgreSQL "select * from table limit $nrows offset $offset". Note that
   * MySQL and PostgreSQL parameter ordering is the opposite of the other.
   *
   * @param [sql]			SQL statement
   * @param [offset]		is the row to start calculations from (1-based)
   * @param [nrows]		is the number of rows to get
   * @param [inputarr]	array of bind variables
   *
   * @return [outputArr] 	array
   */
  public function SelectLimit($sql, $nrows = -1, $offSet = -1, $inputArr = array())
  {
    switch ($this->_driver) {
      case "pgsql":
        $sql .= " LIMIT " . $nrows . " OFFSET " . $offSet;
        break;
    }
    $outputArr = $this->GetAll($sql, $inputArr);
    return $outputArr;
  }

  /**
   * Show errors and sql.
   *
   * @return [true]		boolean
   */
  private function debug()
  {
    if ($this->_objPdo instanceof PDOStatement && $this->_db_debug) {
      $posError = 2;
      ob_start();
      $this->_objPdo->debugDumpParams();
      $output = ob_get_contents();
      ob_end_clean();
      $sql = strstr($output, "Params:", true);
      echo "<p>" . $sql . "</p>";
      if (!empty($this->_parameters)) {
        echo "<p>Parameters:<br />";
        foreach ($this->_parameters as $key => $value) {
          echo $key . ": ? => " . $value . "<br />";
        }
        echo "</p>";
      }
      $Error = $this->_objPdo->errorInfo();
      echo "<p>" . $Error[$posError] . "</p>";
    }
    return true;
  }

  /**
   * Set field search.
   *
   * @param [key]			array key
   * @param [field]		field value
   *
   * @return [true]		boolean, always true
   */
  public function add_fieldSearch($key, $field)
  {
    $this->_arrSearch[$key] = $field;
    return true;
  }

  /**
   * Build Translate search condition.
   *
   * @param [field]		field search
   * @param [strWords]	Words search
   * @param [operator]	And - OR
   *
   * @return [Where]		SQL condition for Where
   */
  protected function strWhere($field, $strWords, $operator)
  {
    $Where = "";
    $strWords = trim($strWords);
    $arr_Words = explode(" ", $strWords);
    $last = array_pop(array_keys($arr_Words));
    foreach ($arr_Words as $key => $word) {
      if (trim($arr_Words[$key]) != "") {
        $Where .= "translate(lower(" . $field . "),'áàéèíìóòúù','aaeeiioouu') LIKE lower(('%' || translate(lower('" . trim($arr_Words[$key]) . "'),'áàéèíìóòúù','aaeeiioouu') || '%'))";
        if ($last != $key) $Where .= " " . $operator . " ";
      }
    }
    return $Where;
  }

  /**
   * Build Translate search condition.
   *
   * @param [arrSearch]		field search and value
   *
   * @return [Where]			SQL condition for Where
   */
  protected function get_where($arrSearch, $other = "")
  {
    $where = "";
    if (!array_key_exists(null, $arrSearch)) {
      $key = array_shift(array_keys($arrSearch));
      $search_value = $arrSearch[$key];
      $search_field = $this->_arrSearch[$key];
      $where = $this->strWhere($search_field, $search_value, "AND");
    }
    if (!empty($where)) {
      $where = " {$other} " . $where;
      if (empty($other)) {
        $where = " WHERE " . $where;
      }
    }
    return $where;
  }

  public function AutoExecute($mode, $where = FALSE, $index = FALSE)
  {
    $strSql = "";
    $flag = false;
    $strFields = "";
    switch ((string) $mode) {
      case "INSERT":
        foreach ($this->_arrFields as $field => $value) {
          $strFields .= $field . ",";
        }
        $strFields = substr($strFields, 0, (strlen($strFields) - 1));
        $strSql = "INSERT INTO {$this->_table}({$strFields}) VALUES (?" . str_repeat(",?", count($this->_arrFields) - 1) . ")";

        $flag = $this->ExecuteSql($strSql, array_values($this->_arrFields));
        if ($index != false) {
          $last_value = $this->lastInsertId($index);
          $flag = $last_value;
        }
        break;
      case "UPDATE":
        foreach ($this->_arrFields as $field => $value) {
          $strFields .= $field . "= ?,";
        }
        $strFields = substr($strFields, 0, (strlen($strFields) - 1));
        $strSql = "UPDATE {$this->_table} SET {$strFields} {$where}";
        $flag = $this->ExecuteSql($strSql, array_values($this->_arrFields));
        break;
      case "DELETE":
        $where == FALSE ? $where = '' : '';
        $strSql = "DELETE FROM {$this->_table} {$where}";
        $flag = $this->ExecuteSql($strSql, array_values($this->_arrFields));
        break;
      default:
        break;
    }
    return $flag;
  }

  /**
   * Load object by primary key.
   *
   * @param [Obj]			input Object with value for primary key
   *
   * @return [Obj]		output Object with all properties fill
   */
  public function loadOne($Obj)
  {
    $fields = "";
    $set_method = "set";
    $get_method = "get";
    $Ro = new ReflectionObject($Obj);
    $properties = $Ro->getProperties();
    foreach ($properties as $property) {
      $name = $property->getName();
      $name = substr($name, 1);
      $fields .= $name . ",";
    }
    $fields = substr($fields, 0, strlen($fields) - 1);
    $method = $get_method . "_" . $this->_Pkey["key"];
    $this->_Pkey["value"] = $Obj->$method();

    $sql = "SELECT {$fields} FROM {$this->_table} WHERE " . $this->_Pkey["key"] . " = ?";
    $Arr = $this->GetRow($sql, array($this->_Pkey["value"]));
    foreach ($properties as $property) {
      $name = $property->getName();
      $field = substr($name, 1);
      $method = $set_method . $name;
      $Obj->$method($Arr[$field]);
    }
    return $Obj;
  }

  /**
   * Save object by Moon Model.
   *
   * @param [Obj]			input Object with value for primary key
   *
   * @return [Obj]		output Object with all properties fill
   */
  public function add($Obj)
  {
    $fields = "";
    $Params = array();
    $set_method = "set";
    $get_method = "get";

    $Ro = new ReflectionObject($Obj);
    $properties = $Ro->getProperties();
    foreach ($properties as $property) {
      $name = $property->getName();
      $name = substr($name, 1);
      if ($this->_Pkey["key"] != $name) {
        $fields .= $name . ",";
        $method = $get_method . "_" . $name;
        $Params[] = $Obj->$method();
      }
    }
    $numParams = count($Params) - 1;
    $fields = substr($fields, 0, strlen($fields) - 1);

    $sql = "INSERT INTO {$this->_table}({$fields}) VALUES (?" . str_repeat(",?", $numParams) . ")";
    $flag = $this->ExecuteSql($sql, $Params);
    if ($flag == false) return false;
    $last_id = $this->lastInsertId($this->_sequence);
    $method = $set_method . "_" . $this->_Pkey["key"];
    $Obj->$method($last_id);
    return $Obj;
  }

  public function getTimestamp()
  {
    // date_default_timezone_set("America/Bogota");
    // $date = new DateTime();
    // return $date->getTimestamp();
    return Carbon\Carbon::now('America/Bogota')->timestamp;
  }


   public function getImageUrl($image,$companyName,$assistantDocNumber)
    {

        $image=str_replace("\/", "/", $image);
        $image=str_replace("data:image/png;base64,", "", $image);
        $image=str_replace("data:image/jpeg;base64,", "", $image);
        $image=str_replace("data:image/jpg;base64,", "", $image);
        $image=str_replace(" ", "+", $image);
        //$companyName=str_replace(" ", "_", $companyName);
       error_reporting(E_ALL ^ E_WARNING); 
        $image=base64_decode($image);
        $image =imageCreateFromString($image);
        if (!$image) {
           $endJSON['status'] = 'error';
          $endJSON["message"] = "El formato de imagen no es compatible";
          echo json_encode($endJSON);

  die();
}

        date_default_timezone_set('America/Bogota'); 
          $currDate=date('Y-m-d H:i:s');

         $randomNum=rand(10000, 1000000);

          $FileName=substr(base64_encode($randomNum), -15);
          $FileName=$assistantDocNumber."_".$FileName."".rand().".png";

          $filePath="./payRollFiles/".$companyName;

        if (!file_exists($filePath))
          {
               if (!mkdir($filePath, 0, true)) 
               {
                   throw new Exception("Cant Create Image Path".$filePath, 1);
               }
          }

          $filePath="./payRollFiles/".$companyName."/".$FileName;
            imagepng($image, $filePath, 0);

          $fileUrl=$filePath;
          $fileUrl=str_replace(" ", "%20", $fileUrl);
    
         return $fileUrl;
  }

    public function getFileUrl($file,$companyName,$assistantInfo)
  { 
        $file=str_replace("\/", "/", $file);
        $file=str_replace("data:application/pdf;base64,", "", $file);
        $file=str_replace(" ", "+", $file);
        //$companyName=str_replace(" ", "_", $companyName);
        $bin = base64_decode($file, true);

        if (strpos($bin, '%PDF') !== 0) 
        {
            $endJSON['status'] = 'error';
            $endJSON["message"] = "El formato debe ser en PDF";
            echo json_encode($endJSON);
            die();
        }



         $randomNum=rand(10000, 1000000);

          $FileName=substr(base64_encode($randomNum), -15);
          $FileName=$assistantInfo."_".$FileName."".rand().".pdf";

          $filePath="public/Documents/".$companyName."/Assistants";

          if (!file_exists($filePath))
          {
               if (!mkdir($filePath, 0, true)) 
               {
                   throw new Exception("Cant Create Assistant Path".$filePath, 1);
               }
          }
          chmod($filePath, 755);

          $copyFilePath=$filePath;

          $filePath.="/".$FileName;
           file_put_contents( $filePath, $bin);

         $fileUrl="http://api.teella.com/".$filePath;
        $fileUrl=str_replace(" ", "%20", $fileUrl);
    
         return $fileUrl;
  }
}
