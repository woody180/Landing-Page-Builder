<?php namespace App\Controllers\Users;

use App\Engine\Libraries\Validation;
use \Gumlet\ImageResize;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use \R as R;

class UsersController {
    
    protected $usersModel;
    protected $usergroupsModel;
    protected $validation;
    private $mailFrom;

    public function __construct() {
        
        $this->usersModel = initModel('Users');
        $this->usergroupsModel = initModel('Usergroups');

        $this->validation = new Validation();
        
        $this->mailFrom = AUTH_MAIL;
       
    }

    
    // All users
    public function index($req, $res) {
        return $res->render('users/login');
	}


    // Activate
    public function activation($req, $res) {
        
        $user = R::findOne('users', 'vkey = ?', [$req->query('vkey')]);
        
        if (is_null($user)) {
           setFlashData('error', 'Activation key is incorrect');
            
            return $res->redirect(baseUrl('users/login'));
        }
        
        $user->import([
            'vkey' => null,
            'activated' => 1
        ]);
        R::store($user);
        
        setFlashData('success', 'Your accout is activated successfully');
        
        return $res->redirect(baseUrl('users/login'));
    }
    
    
    public function loginView($req, $res) {
        return $res->render('users/login');
    }
    
    
    public function login($req, $res) {
        
        $body = $req->body();

        $errors = $this->validation
            ->with($body)
            ->rules([
                'email' => 'required|string|valid_email|max[60]',
                'password' => 'required|string|max[60]'
            ])
            ->validate();

            
        // Check if errors
        if ($errors) {
            setForm($body);
            setFlashData('errors', $errors);
            return $res->redirectBack();
        }

        // Check if user exists
        $user = R::findOne('users', 'email = ?', [$req->body('email')]);
        if (!$user) {
            setForm($body);
            setFlashData('errors', [
                'email' => [\App\Engine\Libraries\Languages::translate('auth.user_not_found')]
            ]);
            return $res->redirectBack();
        }

        // Check password
        if (!password_verify($req->body('password'), $user->password)) {
            setForm($body);
            setFlashData('errors', [
                'password' => [\App\Engine\Libraries\Languages::translate('auth.wrong_password')]
            ]);
            return $res->redirectBack();
        }

        // Check if user is activated
        if (!$user->activated) {
            setForm($body);
            setFlashData('message', \App\Engine\Libraries\Languages::translate('auth.user_not_activated'));
            return $res->redirectBack();
        }


        // Login user
        $_SESSION['userid'] = $user->id;
        return $res->redirect(baseUrl('users/profile/' . $user->id));

    }
    
    
    public function registerView($req, $res) {
       
        return $res->render('users/register');
    }
    
    
    public function register($req, $res) {
        
        $body = $req->body();
        $body['avatar'] = $req->files('avatar')->show();


        // Check if usergroups relation exists
        if (!R::findOne('usergroups')) {
            $usersTable = R::dispense('users');
            R::store($usersTable);

            ////////////////////// Create usergroups table //////////////////////
            foreach (['Superuser', 'Manager', 'Registered'] as $value) {
                $usergroupsTable = R::dispense('usergroups');
                $usergroupsTable->name = $value;
                $usergroupsTable->description = '';
                R::store($usergroupsTable);
            }
            ////////////////////// End usergroups table //////////////////////

            // Create users table
            $usersTable->import([
                'name' => '',
                'username' => '',
                'email' => '',
                'password' => '',
                'avatar' => '',
                'activated' => '',
                'vkey' => '',
                'usergroups' => $usergroupsTable
            ]);
            R::store($usersTable);
            R::trash(R::findLast('users'));
        }

        $errors = $this->validation
            ->with($body)
            ->rules([
                'name|Name' => 'required|alpha_num_spaces|min[3]|max[20]',
                'username|Username' => 'required|alpha_num|min[3]|max[20]',
                'email|Email' => 'required|valid_email|max[60]',
                'avatar|Avatar' => 'max_size[100000]|ext[jpg,jpeg,png,bmp,gif]',
                'password|Password' => 'required|min[3]|max[60]|string',
                'password_repeat|Password repeat' => 'required|min[3]|max[60]|string'
            ])
            ->validate();

        
        // Check errors
        if (!empty($errors)) {
            setFlashData('errors', $errors);
            setForm($body);
            return $res->redirectBack();
        }

        // Check if password and password repeat fields match
        if ($req->body('password') != $req->body('password_repeat')) {
            setFlashData('errors', ['password_repeat' => [\App\Engine\Libraries\Languages::translate('auth.password_match_error')]]);
            setForm($body);
            return $res->redirectBack();
        }

        // Check if email is already taken
        if (R::findOne('users', 'email = ?', [$req->body('email')])) {
            setFlashData('errors', ['email' => [\App\Engine\Libraries\Languages::translate('auth.mail_taken')]]);
            setForm($body);
            return $res->redirectBack();
        }

        // Upload image
        if (!$req->files('avatar')->show('error')) {
            $avatar = $req->files('avatar')->upload(dirname(APPROOT) . '/public/assets/images/avatars');

            // Resize image
            $imageResize = new ImageResize($avatar);
            $imageResize->crop(250, 250);
            $imageResize->save($avatar);
            
            $body['avatar'] = explode('avatars/', $avatar)[1];
        } else {
            unset($body['avatar']);
        }
       
        // Set activation key
        $activationKey = time();
        $body['vkey'] = $activationKey;

        $body['password'] = password_hash($req->body('password'), PASSWORD_DEFAULT);
        $body['usergroups_id'] = 3;
        $body['activated'] = 1;
        $body['createdat'] = time();
        
        unset($body['password_repeat']);
        unset($body['csrf_token']);

        $users = R::dispense('users');
        $users->import($body);
        $done = R::store($users);

        if ($done) {

            // $mail = new PHPMailer(true);
            // try {
            //     //Server settings
            //     $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            //     $mail->isSMTP();                                            //Send using SMTP
            //     $mail->Host       = 'smtp.example.com';                     //Set the SMTP server to send through
            //     $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            //     $mail->Username   = 'user@example.com';                     //SMTP username
            //     $mail->Password   = 'secret';                               //SMTP password
            //     $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            //     $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            
            //     //Recipients
            //     $mail->setFrom($this->mailFrom, 'Mailer');
            //     $mail->addAddress($req->body('email'));
            
            //     //Content
            //     $mail->isHTML(true);
            //     $mail->Subject = 'Validate user account';
            //     $mail->Body    = '<a href="'.baseUrl('users/activation?vkey='.$activationKey.'').'">Account activateion on '. baseUrl().'. Follow the link for activation. </a>';
            
            //     $mail->send();
            // } catch (Exception $e) {
            //     echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            // }


            setFlashData('success', \App\Engine\Libraries\Languages::translate('auth.user_register_success'));
            delete_cookie('form');
            return $res->redirect(baseUrl('users/login'));
        }

        setFlashData('message', \App\Engine\Libraries\Languages::translate('auth.unknown'));
        return $res->redirectBack();
    }


    public function accountView($req, $res) {
        $user = R::load('users', $_SESSION['userid']);

        return $res->render('users/account', [
            'user' => $user
        ]);
    }
    
    
    // Update account
    public function account($req, $res) {
        
        $body = $req->body();

        // User ID
        $id = $req->getSegment('3');

        // Validate
        $errors = $this->validation
            ->with($body)
            ->rules([
                'name|Name' => 'required|alpha_num_spaces|min[3]|max[60]',
                'username|Username' => 'required|alpha_num|min[3]|max[60]',
                'email|Email' => 'required|valid_email|max[60]',
                'avatar|Avatar' => 'string|max[200]|max_size[100000]|ext[jpg,jpeg,bmp,gif]',
                'avatar_hidden|Avatar' => 'string|max[200]',
                'password|Password' => 'string|max[200]',
                'password_repeat|Password repeat' => 'string|max[200]'
            ])
            ->validate();


        // If errors
        if (!empty($errors)) {
            setFlashData('errors', $errors);
            setForm($body);
            return $res->redirectBack();
        }

        // Get user
        $user = R::load('users', $id);

        // Check if passwrod and if password match
        if ( !empty($req->body('password')) ) {
            if ($req->body('password') !== $req->body('password_repeat')) {
                setFlashData('errors', ['password_repeat' => [\App\Engine\Libraries\Languages::translate('auth.password_match_error')]]);
                setForm($body);
                return $res->redirectBack();
            } else {
                $body['password'] = password_hash($req->body('password'), PASSWORD_DEFAULT);
            }
        } else {
            $body['password'] = $user->password;
        }

        
        // If new avatar
        if ($req->files('avatar')->show()['error'] === 0) {
            $avatar = $req->files('avatar')->upload(dirname(APPROOT) . '/public/assets/images/avatars');

            $imageResize = new ImageResize($avatar);
            $imageResize->crop(250, 250);
            $imageResize->save($avatar);

            $body['avatar'] = explode('avatars/', $avatar)[1];
        } else {
            $body['avatar'] = $req->body('avatar_hidden');
        }

       
        unset($body['avatar_hidden']);
        unset($body['password_repeat']);

        // Check email
        if ($req->body('email') != $user->email && R::findOne('users', 'email = ?', [$req->body('email')])) {
            setFlashData('errors', ['email' => [\App\Engine\Libraries\Languages::translate('auth.mail_taken')]]);
            setForm($body);
            return $res->redirectBack();
        }
        

        // Update account
        $user->import($body);
        $done = R::store($user);

        
        if ($done) {
            setFlashData('success', \App\Engine\Libraries\Languages::translate('auth.account_update_success'));
            delete_cookie('form');
            return $res->redirectBack();
        }

        setFlashData('message', \App\Engine\Libraries\Languages::translate('auth.unknown'));
        setForm($body);
        return $res->redirectBack();
    }


    public function profile($req, $res) {

        $user = R::load('users', $req->getSegment('3'));

        if (!$user->id) return abort();

        return $res->render('users/profile', [
            'user' => $user,
            'isSelf' => (isset($_SESSION['userid']) && $user->id === $_SESSION['userid']) ? true : false
        ]);
    }
    
    
    public function reset($req, $res) {
        

        // Reset password if post request
        if ($req->getMethod() == 'post') {

            $body = $req->body();
        
            // Get user email
            $errors = $this->validation
                ->with($req->body())
                ->rules([
                    'email|Email' => 'required|valid_email',
                    'password|Password' => 'required|min[5]|max[200]',
                ])
                ->validate();
            
            // If validation NOT passed
            if (!empty($errors)) {

                setFlashData('errors', $errors);
                setForm($body);
                return $res->redirectBack();
            } else {
                
                $user = R::findOne('users', 'email = ?', [$req->body('email')]);
                $verificationID = time();

                if (!$user) {
                    setForm($body);
                    setFlashData('errors', ['email' => [\App\Engine\Libraries\Languages::translate('auth.user_not_found')]]);
                    return $res->redirectBack();
                }
                
                // Hash the password and set to the session storage
                $hashedPassword = password_hash($req->body('password'), PASSWORD_DEFAULT);
                //$this->session->set("key_$verificationID", $hashedPassword);
                $_SESSION["key_$verificationID"] = $hashedPassword;
                                
                $user->import([
                    'vkey' => $verificationID
                ]);
                R::store($user);
                
                $message = \App\Engine\Libraries\Languages::translate('auth.reset_verification_message');
                
                $emailMessage = \App\Engine\Libraries\Languages::translate('auth.reset_post_message');

                $mail = new PHPMailer(true);
                try {
                    
                    $mail->SMTPOptions = array(
                        'ssl' => array(
                            'verify_peer' => false,
                            'verify_peer_name' => false,
                            'allow_self_signed' => true
                        )
                    );
                    
                    $mail->isSMTP();
                    $mail->Host       = MAIL_HOST;
                    $mail->SMTPAuth   = true;
                    $mail->Username   = MAIL_USERNAME;
                    $mail->Password   = MAIL_PASSWORD;
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port       = MAIL_PORT;
                
                    //Recipients
                    $mail->setFrom(AUTH_MAIL, AUTH_DOMAIN);
                    $mail->addAddress($req->body('email'));
                    
                    //Content
                    $mail->isHTML(true);
                    $mail->CharSet = 'UTF-8';
                    
                    $mail->Subject = 'Verification code';
                    $mail->Body    = '<a href="'.baseUrl('users/reset/?verification='.$verificationID.'').'"> '. $emailMessage .' </a>';
                
                    $mail->send();
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
                
                setFlashData('message', $message);
                return $res->redirectBack();
            }
        }
        
        
        // Load view if get request
        if ($req->getMethod() == 'get') {
            
            // If password reset verification key exists
            $verification = $req->query('verification');
            
            if ($verification) {
                // $user = $this->usersModel->where('activation_key', $verification)->first();
                $user = R::findOne('users', 'vkey = ?', [$verification]);
                
                if ($user) {
                    $user->import([
                        'vkey' => null,
                        'password' => $_SESSION["key_$verification"]
                    ]);
                    R::store($user);
                    
                    unset($_SESSION["key_$verification"]);
                    setFlashData('message', \App\Engine\Libraries\Languages::translate('auth.password_change_success'));
                    return $res->redirect(baseUrl('users/login'));
                } else {
                    
                    setFlashData('error', \App\Engine\Libraries\Languages::translate('auth.wrong_verification_code'));
                    return $res->redirect(baseUrl('users/reset'));
                }
            }
            
            return $res->render('users/reset');
        }
    }


    public function logout($req, $res) {
        unset($_SESSION['userid']);

        return $res->redirect(baseUrl('users/login'));
    }
    
    
    // Send email
    public function sendmail($req, $res) {
       
    }
}