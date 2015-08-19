<?php
/*
  Document   : header
  Created on : 13-Sep-2011, 15:23:05
  Author     : David Bennett
 */
?>
<div id="container">

    <div id="header">
        <div id="sitetitle"><h1><a href="<?php echo __SITE_DIR . '/home' ?>">Learning Journal</a></h1></div>

        <?php
        if (!isset($_SESSION['username'])) {
            //echo '<p>'.$_SERVER['REQUEST_URI'].'</p>';
            //if ($_SERVER['REQUEST_URI'] == '/members') 
            //{
            //echo '<p>'.$_SERVER['REQUEST_URI'].'</p>';
            ?>

            <div id="login">
                <form action="<?php echo __SITE_DIR . '/login'; ?>" method="post">
                    <table>
                        <tr>
                            <td>Username: </td>
                            <td><input type="text" name="username" /></td>
                        </tr>
                        <tr>
                            <td>Password: </td>
                            <td><input type="password" name="password" /></td>
                        </tr>
                        <tr style="text-align:right">
                            <td></td>
                            <td>

                                <input type="submit" value="Login" class="button" />
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
            <?php
        }
//}
        else {
            ?>
            <form action="<?php echo __SITE_DIR . '/logout'; ?>" method="post">
                <div id="loggedIn">
                    Welcome, 
                    <?php
                    echo $_SESSION['name'];
                    if (isset($_SESSION['tutor'])) {
                        echo '(Tutor)';
                    }
                    if (isset($_SESSION['ta'])) {
                        echo '(T.A.)';
                    }
                    if (isset($_SESSION['admin'])) {
                        echo '(Admin)';
                    }
                    ?>
                    <input type="submit" value="Logout" class="button" />
                </div>
            </form>
            <?php
        }
        ?>
    </div>

    <div id="navbar">
        <ul>
            <li><a href="<?php echo __SITE_DIR . '/home' ?>">Home</a></li>
            <li><a href="<?php echo __SITE_DIR . '/selectModule' ?>">Select Module</a></li>
            <?php
            if (isset($_SESSION['username'])) {
                    if (isset($_SESSION['selectedModule'])) {
                        echo "<li><a href=".__SITE_DIR."/viewJournal>Journal: {$_SESSION['selectedModule']}</a></li>";
                        //echo "<li  title='Logged In'><a href=".__SITE_DIR."/viewJournal>My Journal</a></li>"; 
                    } else {
                        echo '<li>No Module Selected</li>';
                        echo '<li  title="Logged In">Please Select a Module</li>';
                    }
                    ?>
                
                <?php
            } else {
                ?>
                <li>No Module Selected</li>
                <!-- li  title="Not Logged In">My Journal</li -->
                <?php
            }
            if (isset($_SESSION['currentJournal']) && isset($_SESSION['tutor']) || isset($_SESSION['ta'])) {
               echo "<li  title='Classifying'><a href=".__SITE_DIR."/classify>Classify Entries</a></li>"; 
               echo "<li  title='Agreement'><a href=".__SITE_DIR."/classify/agreed>Agreement</a></li>"; 
               echo "<li  title='Agreement Download'><a href=".__SITE_DIR."/classify/getAgreedData>Agreement Download</a></li>"; 
            }
            ?>        
        </ul>
    </div>

    <div id="content">
