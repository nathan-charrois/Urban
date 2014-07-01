<?php

/**
 *  Users Model
 */
    class UsersModel{

        /**
         *  @var private $_db   instance of database.
         */
            private $_db = null,
                    $_data,
                    $_sessionName,
                    $_cookieName,
                    $_isLoggedIn = false;

        /**
         *  Create instance of database and check if the logged in user exists.
         *
         *  @param string   $user   retreive a specific user's data.
         */
            function __construct($user = null) {
                // Get an instance of the database.
                $this->_db = DB::getInstance();

                // Set the session name.
                $this->_sessionName = SESSION_NAME;

                // Set the cooke name.
                $this->_cookieName = COOKIE_NAME;

                // Make sure this isn't for a specific user.
                if(!$user) {
                    // And this user is logged in.
                    if(Session::exists($this->_sessionName)) {
                        // Get the user's id.
                        $user = Session::get($this->_sessionName);

                        // Check if the user exists.
                        if($this->find($user)) {
                            // Set them as logged in.
                            $this->_isLoggedIn = true;
                        }

                        // Process Logout.
                        else{
                            $this->_isLoggedIn = false;
                            $this->logout();
                        }

                    }
                }

                // If the user has been defined.
                else{
                    // Get a user's data.
                    $this->find($user);
                }
            }

        /**
         *  Add user to database.
         *
         *  @param array  $fields   the fields to add.
         */
            public function register_user($fields = array()) {
                // Insert user into database, else throw an error.
                if(!$this->_db->insert('users', $fields)) {
                    throw new Exception('There was a problem creating this account.');
                }
            }

        /**
         *  Find a user.
         *
         *  @param  string/int   find by email or id.
         *
         *  @todo  replace query() method with get() method.
         *  @todo  since find() searches by ID or email, users can login with their user ID.
         *         create new validation rule to ensure email field is a proper email.
         */
            public function find($user = null){
                if($user) {
                    // If $user if an integer, search by id.
                    $field = (is_numeric($user)) ? 'id' : 'email';

                    // Search in the database.
                    $data = $this->_db->query("SELECT * FROM users WHERE $field = ?", array($user));

                    // If the user does exist...
                    if($data->count()) {
                        // Set the user's data.
                        $this->_data = $data->first();

                        return true;
                    }

                    return false;
                }
            }

        /**
         *  Login User.
         *
         *  @param string  $username
         *  @param string  $password
         *
         *  @todo  abstract the session name 'user' into a global config file.
         */
            public function login($username = null, $password = null, $remember = false) {


                // When username or password haven't been defined, but the current user exists.
                if(!$username && !$password && $this->exists()) {
                    // Create a session for this user.
                    Session::put($this->_sessionName, $this->data()->id);
                }

                else{
                    // Check that fields are passed.
                    $user = $this->find($username);

                    // If we found a user.
                    if($user) {
                        // Create new hash and check if it matches their password.
                        if($this->data()->password === Hash::make($password, $this->data()->salt)) {
                            // Create a session with this user's id.
                            Session::put($this->_sessionName, $this->data()->id);

                            // Also create cookies to remember user.
                            if($remember){
                                // Generate a hash and check that it doesn't exist.
                                $hash = Hash::unique();
                                $hashCheck = $this->_db->query("SELECT * FROM users_session WHERE user_id = ?", array($this->data()->id));

                                // If the hash doesn't exist.
                                if(!$hashCheck->count()) {

                                    // Insert one into DB.
                                    $this->_db->insert('users_session', array(
                                        'user_id' => $this->data()->id,
                                        'hash' => $hash
                                    ));
                                }

                                else {
                                    $hash = $hashCheck->first()->hash;
                                }

                                // Create the cookie.
                                Cookie::create($this->_cookieName, $hash, COOKIE_EXPIRY);
                            }

                            return true;
                        }
                    }

                }

                return false;
            }

        /**
         *  Logout user.
         */
            public function logout(){
                // Remove active session from DB.
                $this->_db->query("DELETE FROM users_session WHERE user_id = ?", array($this->data()->id));

                // Delete session.
                Session::delete($this->_sessionName);

                // Delete cookie.
                Cookie::delete($this->_cookieName);
            }


        /**
         *  Get the user's data.
         */
            public function data() {
                return $this->_data;
            }

        /**
         *  Check if the current user is logged in.
         */
            public function isLoggedIn() {
                return $this->_isLoggedIn;
            }

        /**
         *  Check if user exists.
         */
            public function exists() {
                return (!empty($this->_data)) ? true : false;
            }
    }