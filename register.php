<?php
require_once 'core/init.php';



if(input::exists()){
    if(token::check(input::get('token'))) {
   $validated = new validate();
   $validation = $validated->check($_POST, array(
    'username' => array(
        'required' => 'true',
        'min' => 2,
        'max' => 20,
        'unique' => 'users'
    ),
    'password' => array(
        'required' => 'true',
        'min' => 6
    ),
    'repeatPassword' => array(
        'required' => true,
        'matches' => 'password'
    ),
    'name' => array(
        'required' => true,
        'min' => 2,
        'max' => 50

    )
   ));

   if($validation->passed()){
        $user = new user();
        echo $salt = hash::salt(32);

        try {
            $user->create(array(
                'username' => input::get('username'),
                'password' => hash::make(input::get('password'), $salt),
                'salt' => $salt,
                'name' => input::get('name'),
                'joined' => date('Y-m-d H:i:s'),
                'group' => 1
            ));

            session::flash('home', 'you have been registered and can now log in!');
            redirect::to('index.php');

        } catch(Exception $e){
            die($e->getMessage());
        }
   } else {
    foreach($validation->errors() as $error) {
        echo $error, '<br>';
    }
   }
   }
}

?>
<form action="" method="post">
    <div class="field">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" value="<?php echo escape(input::get('username'));?>" autocomplete="off">
    </div>

    <div class="field">
        <label for="password">Password:</label>
        <input type="password" name="password" id="password">
    </div>

    <div class="field">
        <label for="repeatPassword">Repeat Password:</label>
        <input type="password" name="repeatPassword" id="repeatPassword">
    </div>
    
    <div class="field">
        <label for="name">Name</label>
        <input type="text" name="name" value="<?php echo escape(input::get('name'));?>" id="name">
    </div>

    <input type="hidden" name="token" value="<?php echo token::generate()?>">
    <input type="submit" value="Register">
</form>