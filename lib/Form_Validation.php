<?php

/** 

    * @package Form Validation Library 
    * @version 1.0 
    * @author MUHAMMAD Siyab
    * @link https://github.com/MUHAMMADSiyab/PHP-Form-Validation-Library
    * @license MIT
        
*/
    namespace FormValidation;
       
    class Form_Validation {
        

        // Variables declaration
        private $config = array();
        private $error_messages = array();
        private $formatted_errors = array();
        private $error_message = null;
        private $custom_error_messages = array();
        private $field_name = null;
        private $input = null;
        private $label = null;
        private $rules = array();



        /** 
         
        * (Void) validate
            * validates the form using specified rules 

            * @param array $config 
                * Array that contains validation configuration


        */
    
        public function validate ($config) {

            $this->config = $config;

            // Data passed via config array
            $this->field_name = $this->config[0];
            $this->label = $this->config[1];
            $this->rules = explode('|', $this->config[2]);


            // If custom messages array is passed
            if (in_array(@$this->config[3], $this->config)) {

                $this->custom_error_messages = array_map(function($str) {
                    return str_replace('{field}', $this->label, $str);
                }, $this->config[3]);

            }



            // Check the request method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Get input data using POST 
                $this->input = $_POST[$this->field_name];

            } else {

                // Get input data using GET 
                $this->input = $_GET[$this->field_name];

            }




            // Check if rules are passed in form of array
            if (is_array($this->rules)) {

            
                // Check if `required` rule is passed via rules array
                if (in_array('required', $this->rules)) {

                    // Check is input is empty 
                    if (empty($this->input)) {
                            
                        if (array_key_exists('required', @$this->custom_error_messages)) {
                            
                            $this->error_message = $this->custom_error_messages['required'];
                            $this->set_error_message($this->error_message);
                        
                        } else {

                            $this->error_message = $this->label . ' field must not be empty';
                            $this->set_error_message($this->error_message);

                        }
                    }

                }




                // Check if `numeric` rule is passed via rules array
                if (in_array('numeric', $this->rules) && ! empty($this->input)) {

                    // Check if input is not a number 
                    if (! is_numeric($this->input)) {

                        if (array_key_exists('numeric', @$this->custom_error_messages)) {

                            $this->error_message = $this->custom_error_messages['numeric'];
                            $this->set_error_message($this->error_message);

                        } else {

                            $this->error_message = $this->label . ' field must contain only numbers';
                            $this->set_error_message($this->error_message);

                        }

                    }

                }




                // Check if `email` rule is passed via rules array
                if (in_array('email', $this->rules) && ! empty($this->input)) {

                    // Check if field contains an invalid email 
                    if (! filter_var($this->input, FILTER_VALIDATE_EMAIL)) {

                        if (array_key_exists('email', @$this->custom_error_messages)) {

                            $this->error_message = $this->custom_error_messages['email'];
                            $this->set_error_message($this->error_message);

                        } else {

                            $this->error_message = $this->label . ' field must contain a valid email';
                            $this->set_error_message($this->error_message);

                        }

                    }

                }




                // Check if `ip` rule is passed via rules array
                if (in_array('ip', $this->rules)  && ! empty($this->input)) {

                    // Check if field contains an invalid URL 
                    if (! filter_var($this->input, FILTER_VALIDATE_IP)) {
                        
                        if (array_key_exists('ip', @$this->custom_error_messages)) {

                            $this->error_message = $this->custom_error_messages['ip'];
                            $this->set_error_message($this->error_message);

                        } else {

                            $this->error_message = $this->label . ' field must contain a valid ip';
                            $this->set_error_message($this->error_message);

                        }

                    }
                }




                // Check if `url` rule is passed via rules array
                if (in_array('url', $this->rules) && ! empty($this->input)) {

                    // Check if field contains an invalid URL 
                    if (! filter_var($this->input, FILTER_VALIDATE_URL)) {

                        if (array_key_exists('url', $this->custom_error_messages)) {

                            $this->error_message = @$this->custom_error_messages['url'];
                            $this->set_error_message($this->error_message);

                        } else {

                            $this->error_message = $this->label . ' field must contain a valid URL';
                            $this->set_error_message($this->error_message);

                        }

                    }
                }




                $match_max = preg_grep('/^[>]/', $this->rules);
                $this->max_length = implode('', $match_max);

                // Check if `max_length` rule is passed via rules array
                if (in_array($this->max_length, $this->rules) && ! empty($this->input)) {

                    // Check if field exceeds the allowed character limit
                    if (strlen($this->input) > str_replace('>', '', $this->max_length)) {

                        if (array_key_exists('max_length', @$this->custom_error_messages)) {

                            // Replace {limit} with the limit provided by user
                            $this->custom_messages = array_map(function($string) {
    
                                return str_replace('{limit}', str_replace('>', '', $this->max_length), $string);

                            }, @$this->custom_error_messages);

                            $this->error_message = $this->custom_messages['max_length'];
                            $this->set_error_message($this->error_message);

                        } else {

                            $this->error_message = $this->label . ' field exceeds the limit of ' . str_replace('>', '', $this->max_length) . ' characters';
                            $this->set_error_message($this->error_message);

                        }

                    }
                
                }




                $match_min = preg_grep('/^[<]/', $this->rules);
                $this->min_length = implode('', $match_min);

                // // Check if `min_length` rule is passed via rules array
                if (in_array($this->min_length, $this->rules) && ! empty($this->input)) {

                    // Check if field's limit is less than specified
                    if (strlen($this->input) < str_replace('<', '', $this->min_length)) {

                        if (array_key_exists('min_length', @$this->custom_error_messages)) {

                            // Replace {limit} with the limit provided by user 
                            $this->custom_messages = array_map(function($str) {
    
                                return str_replace('{limit}', str_replace('<', '', $this->min_length), $str);

                            }, @$this->custom_error_messages);

                            $this->error_message = $this->custom_messages['min_length'];
                            $this->set_error_message($this->error_message);

                        } else {

                            $this->error_message = $this->label . ' field must be at least ' . str_replace('<', '', $this->min_length) . ' characters in length';
                            $this->set_error_message($this->error_message);

                        }

                    }

                }
                



                $match_regex = preg_grep('/matches/', $this->rules);
                $regex = implode('', $match_regex);

                // Check if `regex` rule is passed via rules array
                if (in_array($regex, $this->rules) && ! empty($this->input)) {

                    // Check if field is not in the specified pattern
                    if (! preg_match(str_replace('matches', '', $regex), $this->input)) {

                        if (array_key_exists('regex', @$this->custom_error_messages)) {

                            $this->error_message = @$this->custom_error_messages['regex'];
                            $this->set_error_message($this->error_message);

                        } else {

                            $this->error_message = $this->label . ' field must be in the specfied pattern';
                            $this->set_error_message($this->error_message);

                        }

                    }
                }
                

            } 

            
        }


        // -----------------------------------------------------------

        /** 
        *
        * (Void) exists
            * Checks whether a value already exists in database 

            * @param string $field
                * Data related with field e.g name & label

            * @param string $db 
                * Database connection details e.g host, user, password & database name
            
            * @param string $table 
                * Data related with table e.g table name & column name

            * @param string $error (Optional)
                * Custom error to show on existence
        *
        *
        */
        
        public function exists ($field, $db, $table, $error = null) {

            $this->field_data = explode('|', $field);
            $this->db_data = explode('|', $db);
            $this->table_data = explode('|', $table);


            $input = $this->field_data[0];
            $label = $this->field_data[1];
            $host = $this->db_data[0];
            $user = $this->db_data[1];
            $password = $this->db_data[2];
            $db_name = $this->db_data[3];
            $table_name = $this->table_data[0];
            $column_name = $this->table_data[1];

            // Check the request method
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {

                // Get input data using POST 
                $this->input = $_POST[$input];

            } else {

                // Get input data using GET 
                $this->input = $_GET[$input];

            }


            // Connect to database 
            $con = mysqli_connect($host, $user, $password, $db_name);

            // Escaped Input
            $escaped_input = mysqli_real_escape_string($con, $this->input);

            // Query
            $query = mysqli_query($con, 'SELECT * FROM `' .$table_name. '` WHERE `' .$column_name. '` = "' .$escaped_input. '"');

            // If records exists
            if (mysqli_num_rows($query) > 0) {

                if ($error !== null) {

                    $this->set_error_message($error);

                } else {

                    $this->set_error_message($label . ' already exists');

                }

            }



        } 



        // ------------------------------------------------------------

        /** 
        *
        * (Void) set_error_message
            * Sets error messages whether default or provided by user

            * @param string $default_message 
                * Default error message
        *
        *
        */

        public function set_error_message ($error_message) {

            array_push($this->error_messages, $error_message);        

        }


        // ------------------------------------------------------------

        /** 
        *
        * (Void) set_error_markup
            * Sets custom markup for vaidation errors

            * @param array $opening _markup
                * String contains opening markup

            * @param array $closing _markup
                * String contains closing markup
        *
        *
        */

        public function set_error_markup ($opening_markup, $closing_markup) {


            // Check if error_markup is passed in form of an array
            if (! empty($opening_markup) && ! empty($closing_markup)) {

                for ($i = 0; $i < count($this->error_messages); $i++) {

                    // Format error messages
                    array_push($this->formatted_errors, $opening_markup . $this->error_messages[$i] . $closing_markup);


                }
                               

            }


        }


        // ------------------------------------------------------------


        /**
         * 
         * show_validation_errors 
            * Shows validation errors 
         * 
         * 
        */

        
        public function show_validation_errors () {

            if (count($this->formatted_errors) !== 0) {

                    return $this->formatted_errors;

            } else {

                    return $this->error_messages;

            }

        }


        // ------------------------------------------------------------


         /**
         * 
         * (Boolean) is_form_ok 
            * Checks whether validation is done with errors or not
         * 
         * 
        */     
        
        public function is_form_ok () {

            if (count($this->error_messages) === 0) {

                return true;

            } else {

                return false;

            }

        }
             

    }

    // --------------------------------------------- END OF THE SCRIPT ---------------------------------------------
