<!DOCTYPE html>

<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta content="<?= APP_TAG; ?>" name="description">
        <title><?= APP_NAME; ?> - <?= APP_VER; ?></title>
        <link rel="stylesheet" type="text/less" href="<?= CSS_PATH; ?>/main.less" />
        <link rel="stylesheet" type="text/less" href="<?= CSS_PATH; ?>/shame.less" />
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
        <script src="//cdnjs.cloudflare.com/ajax/libs/less.js/1.7.4/less.min.js"></script>
        <link href='http://fonts.googleapis.com/css?family=Roboto:300,400,700|Open+Sans:400,600,700' rel='stylesheet' type='text/css' />
        <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyASm3CwaK9qtcZEWYa-iQwHaGi3gcosAJc&sensor=false&libraries=places"></script>
        <script src="http://code.jquery.com/jquery-2.0.3.js"></script>
        <script src="<?= JS_PATH; ?>/functions.js"></script>
        <script src="<?= JS_PATH; ?>/extensions.js"></script>
        <script src="<?= JS_PATH; ?>/components.js"></script>
    </head>
    <body>
        <?php
        // Display flash message.
        echo $this->element('debug', array(
            'enabled' => true,
            'data' => $data
        ));
        ?>
        <header class="site-header">
            <div class="row-grey">
                <div class="site-wrap">
                    <div class="row">
                        <div class="small-12 medium-12 large-12 columns">
                            <a href="#" class="button button-text button-text-grey left mtm mbm">Send Feedback</a>
                            <a href="#" class="button button-text button-text-grey right mtm mbm">View Source</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row-white">
                <div class="site-wrap">
                    <div class="row">
                        <div class="small-12 medium-7 large-7 columns">
                            <div class="site-logo-container">
                                <span class="logo-text"><a href="<?= URL ?>"><?= APP_NAME; ?></a></span>
                                <span class="slogan"><?= APP_TAG; ?></span>
                            </div>
                        </div>
                        <div class="small-12 medium-5 large-5 columns">
                            <?php
                            if(isset($data['logged_in']) && $data['logged_in']){
                            ?>
                            <div class="user-controls right">
                                <div class="display-picture-container">
                                    <a href="#">
                                        <img src="http://placehold.it/32" class="display-picture" />
                                    </a>
                                </div>
                                <span class="dropdown">
                                    <a class="button button-text button-text-grey" data-event="toggle-dropdown"><?= $data['user_data']['name'] ?></a>
                                    <ul class="dropdown-menu hide">
                                        <li><a href="<?= URL; ?>user/edit">Edit Profile</a></li>
                                        <li><a href="<?= URL; ?>user/change-password">Change Password</a></li>
                                        <li><a href="<?= URL; ?>user/logout">Logout</a></li>
                                    </ul>
                                </span>
                            </div>
                            <?php
                            }

                            // User isn't logged in.
                            else{
                            ?>
                            <div class="header-sign-in right">
                                <div class="row">
                                    <div class="small-12 medium-12 large-5 columns">
                                        <a class="button button-primary" href="<?= URL; ?>user/login/">Sign in</a>
                                    </div>
                                    <div class="small-12 medium-12 large-7 columns">
                                        <span class="text">
                                            or <a class="button button-text mls" href="<?= URL; ?>user/register/">create account</a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <?php
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="site-wrap">
            <div class="notifications">
                <?php
                // Display flash message.
                echo $this->element('flash');
                ?>
            </div>
        </div>