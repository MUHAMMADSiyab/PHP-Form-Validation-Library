# PHP Form Validation Library 
Performs Server-Side validation of HTML Forms

## Downloading and implementation 
- Download the zip file manually and include in your project directory <br />
  **OR** (Using Composer) <br />
  run command `composer require muhammadsiyab/form_validation` 
- Include Form_Validation library 
    ```php
    require_once './vendor/autoload.php'; 
    ```

### Validating form 
```php 
<?php

// Instantiate `Form_Validation` Class
$form_validation = new \FormValidation\Form_Validation();


// Array containing custom messages 
// (Optional parameter, if not passed, default error messages will be used)

$messages = array(
    'required' => '{field} is cumpolsary',
    'max_length' => '{field} must be in limit of {limit}',
    'min_length' => '{field} must be at least of {limit} characters',
    'regex' => '{field} must be in specific pattern'
);


// Validation rules
$form_validation->validate(array('field_name', 'field_label', 'required|>10|<3', $messages));


// Check whether a record exists in database
$form_validation->exists('field_name|field_label', 'localhost|user|password|db_name', 'table_name|column_name', 'custom_error');


// Check
if ($form_validation->is_form_ok() === false) {
    
    // Custom error markup
    $form_validation->set_error_markup('<li>', '</li>');
    
    // Showing validation errors
    $errors = $form_validation->show_validation_errors();
    
    for ($i = 0; $i < count($errors); $i++) {

        echo $errors[$i];
        
    }
    
} else {
        
        // Do something here
    
}

```

## Available methods
#### 1. validate
Validates the form using specified rules 
###### Parameters:
* ``array`` **$config** 
Array that contains validation configuration
   ###### Example
   `` array('field_name', 'field_label', 'validation_rules_separated_with_pipe', array_containing_custom_messages) ``

#### 2. exists
Checks whether a value already exists in database 
###### Parameters:
* ``string`` **$field**
   Data related with field e.g name & label
   ###### Example
   `` 'field_name|field_label' ``

* ``string`` **$db** 
   Database connection details e.g host, user, password & database name
   ###### Example
   `` 'localhost|user|password|db_name' ``

* ``string`` **$table** 
   Data related with table e.g table name & column name
   ###### Example
   `` 'table_name|column_name' ``

* ``string`` **$error** (Optional)
   Custom error to show 

#### 3. set_error_markup
Sets custom markup for validation errors

* ``string`` **$opening_markup** 
   String containing opening markup
   ###### Example
   `` '<span style="color: red">' ``

* ``string`` **$closing_markup** 
   String containing closing markup
   ###### Example
   `` '</span>' ``

#### 4. show_validation_errors 
###### @return type ``Array`` (User for loop to iterate through errors)
Shows validation errors 

#### 5. is_form_ok
Checks whether validation is done with errors or not

## Available validation rules
| Rule               | Description                        | Syntax                 |
| :----------------: | :--------------------------------: | :--------------------: |
| *required*           | Checks if a field is empty         | `required`               |
| *numeric*           | Checks if a field is not a number         | `numeric`               |
| *email*           | Checks if email is invalid          | `email`               |
| *url*           | Checks if url is invalid          | `url`               |
| *ip*           | Checks if IP address is invalid          | `ip`               |
| *min_length*           | Sets the minimum length of field          | `<6`               |
| *max_length*           | Sets the maximum length of field          | `>10`               |
| *regex*           | Performs regular expression for the field         | `matches/^[a-zA-Z0-9]/`               |
