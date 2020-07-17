<?php
    class Student{
        public $id;
        public $name;
        public $surname;
        public $age;
        public $curriculum;
        private $errors;

        public function vadidate(){
            $this->errors = [];
            $this->set_id($this->id);
            $this->set_name($this->name);
            $this->set_surname($this->surname);
            $this->set_age($this->age);
            $this->set_curriculum($this->curriculum);
            if(count($this->errors)>0){
                $output = implode(PHP_EOL,$this->errors);
                throw new Exception($output);
            }
        }

        public function set_id($id){
            if(strlen($id) == 7 && is_numeric($id)) {
                $this->id = $id;
            }
            else{
                $this->errors[] = "Student id must be unique and consist of 7 digits";
            }
        }
        public function set_name($name){
            if(!empty($name)) {
                $this->name = $name;
            }
            else{
                $this->errors[] = "Name is required";
            }
        }
        public function set_surname($surname){
            if(!empty($surname)) {
                $this->surname = $surname;
            }
            else{
                $this->errors[] = "Surname is required";
            }
        }

        public function set_age($age){
            if(is_numeric($age)) {
                $this->age = $age;
            }
            else{
                $this->errors[] = "Age is required and must be numeric";
            }
        }

        public function set_curriculum($curriculum){
            if(!empty($curriculum)) {
                $this->curriculum = $curriculum;
            }
            else{
                $this->errors[] = "Curriculum is required";
            }
        }

        public function get_id(){
           return $this->id;
        }

        public function get_name(){
            return  $this->name;
        }

        public function get_surname(){
            return $this->surname;
        }

        public function get_age(){
            return $this->age;
        }

        public function get_curriculum(){
           return $this->curriculum;
        }
    }