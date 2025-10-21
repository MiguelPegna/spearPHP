<?php

    namespace core;

    class Controller{

        public $info;
        public $scripts;

        /**
        * Description: method for obtain view of controller 
        * @param string $view -> view path in views directory
        */
        public function view($view){
            $view = str_replace('.', '/', $view);   //TODO -> IN - string.continue, OUT - string/continue 
            $pathView = '../resources/views/'. $view. '.php';
            if(file_exists($pathView)){
                ob_start();
                //Converts the received arguments into variables.
                if($this->info !== null) extract($this->info);
                if($this->scripts !== null) extract($this->scripts);
                include($pathView);
                $view = ob_get_clean();
                echo $view;
            }
            else {
                return Route::notFound();
            }
        }

        /**
        * Description: method for send variables to view 
        * @param string $key -> name of variable 
        * @param string $value -> value of variable 
        */
        public function setVar($key='', $value=''){
            $this->info[$key] = $value;
        }

        /**
        * Description: method for send scripts JS or CCS sheets
        * @param string $key -> name of variable for script
        * @param string $type -> commonJS || EsModules || css = css file
        * @param string $name -> JS file name or css file name 
        */
        public function getScript($type = '', $scriptName = ''){
            $scriptName = str_replace('.', '/', $scriptName);
            if($type != 'commonjS' || $type != 'EsModules' || $type != 'css'){
                $file = '';
            }
            if($type == 'commonJS' ){  //script in commonJS
                $file = '<script src="'. env('JS_FILES') .'/'. $scriptName. '.js"></script>';
            }
            if($type == 'EsModules' ){ //script in EsModule
                $file = '<script type="module" src="'. env('JS_FILES') .'/'. $scriptName. '.js"></script>';
            }
            if($type == 'css' ){    
                $file = '<link href="'. env('CSS_FILES') .'/'. $scriptName. '.css" rel="stylesheet">';
            }
            return $this->scripts['scripts'] = $file;
        }

        /**
        * Description:  Indicates the type content that send or request
        * @param string $type -> indicates the content type html or json
        */
        public function typeContent($type=''){
            //define the headers config for the request
            $type = strtolower($type);
            switch  ($type){
                case 'html':
                    header('Content-Type: text/html; charset=utf-8');
                break;
                case 'json':
                    header('Content-Type: application/json; charset=utf-8');
                break;
                default:
                    header('Content-Type: text/plain; charset=utf-8'); // Por defecto, texto plano
                break;
            }
            //header("Access-Control-Allow-Origin: *");  //use for endpoints publics for his use in others sites
            //header("Access-Control-Allow-Headers: Content-Type");        //headers for cors interactions   
            //header("Access-Control-Allow-Methods: POST, GET, OPTIONS");  //headers for cors interactions
        }
    }