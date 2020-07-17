<?php
    include './student.php';
    include './string_format.php';
    try{
        define('ID_NON_EXISTENT',"ID doesn't exist");
        define('STUDENT_FOLDER',"students");

        if(!is_dir(STUDENT_FOLDER)){
            mkdir(STUDENT_FOLDER,0755);
        }
        
        if(in_array('--action=add',$argv)){
            $student = new Student();

            $student->id = readline('Enter id: ');
            $student->name = readline('Enter name: ');
            $student->surname = readline('Enter surname: ');
            $student->age = readline('Enter age: ');
            $student->curriculum = readline('Enter curriculum: ');
            strlen($student->name);
            $student->vadidate();
            $folder = STUDENT_FOLDER .'/'.substr($student->id,0,2);
            mkdir($folder,0755);
            $path = $folder.'/'.$student->id. '.json'; 
            file_put_contents($path,json_encode($student));
        }

        if(in_array('--action=edit',$argv)){
            if(count($argv)<3){
                throw new Exception("ID required");
            }

            $split = explode('=',$argv[2]); 
            $id = array_pop($split);
            $path = STUDENT_FOLDER .'/'.substr($id,0,2).'/'.$id. '.json';
            if(!file_exists($path))
            {
                throw new Exception(ID_NON_EXISTENT);
            }

            $contents = file_get_contents($path);
            $data = json_decode($contents);
            $student = new Student();
            $student->id = $id;
            echo "Leave blanl to keep previous value" . PHP_EOL;
            $name = readline("Enter name [{$data->name}]: ");
            $student->name = empty($name)?$data->name:$name;

            $surname = readline("Enter surname [{$data->surname}]: ");
            $student->surname = empty($surname)?$data->surname:$surname;
            
            $age = readline("Enter age [{$data->age}]: ");
            $student->age = empty($age)?$data->age:$age;

            $curriculum = readline("Enter curriculum: [{$data->curriculum}] ");
            $student->curriculum = empty($curriculum)? $data->curriculum: $curriculum;

            $student->vadidate();

            file_put_contents($path,json_encode($student));
        }

        if(in_array('--action=delete',$argv)){
            if(count($argv)<3){
                throw new Exception("ID required");
            }
            
            $split = explode('=',$argv[2]); 
            $id = array_pop($split);
            $folder = STUDENT_FOLDER .'/'.substr($id,0,2);
            $path = $folder.'/'.$id. '.json';
            if(!file_exists($path))
            {
                throw new Exception(ID_NON_EXISTENT);
            }

            $is_file_deleted = unlink($path);

            $is_folder_deleted = rmdir($folder);

            if($is_file_deleted && $is_folder_deleted){
                echo "Student deleted succcessfully";
            }
            else{
                throw new Exception('Something went wrong :(');
            }
        }

        if(in_array('--action=search',$argv)){
            $name = readline("Enter Search criteria: name=");
            $dots = ['..', '.'];
            $folders = array_diff(scandir(STUDENT_FOLDER),$dots);
            $border = "---------------------------------------------------------------------------------".PHP_EOL;
            echo $border;
            echo string_format('id').string_format('Name').string_format('Surname').string_format('Age').string_format('Curriculum').PHP_EOL;
            echo $border;
            $records = "";
            foreach($folders as $folder){
                $path = STUDENT_FOLDER.'/'.$folder;
                $files = array_diff(scandir($path),$dots);
                foreach($files as $file){
                    $contents = file_get_contents($path.'/'.$file); 
                    $student = json_decode($contents);
                    if(strtolower($student->name) == strtolower($name)){
                        $records .= string_format($student->id).string_format($student->name).string_format($student->surname).string_format($student->age).string_format($student->curriculum).PHP_EOL;
                        $records .= $border;

                    }
                }                  
            }
            echo $records ? $records:" No records found".PHP_EOL.$border;
        }


    }
    catch(Exception $ex){
        echo PHP_EOL;
        echo 'Errors:';
        echo PHP_EOL;
        echo $ex->getMessage();
    }

