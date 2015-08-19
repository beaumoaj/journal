
<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
if (isset($_SESSION['username'])) {
    ?> 
    <?php
}
if (isset($_SESSION['admin'])) {
    ?> 
    <p><b>Admin</b></p>
    <ul>
        <li><a href="<?php echo __SITE_DIR . '/userAdmin' ?>">User Administration</a></li>
        <li><a href="<?php echo __SITE_DIR . '/userAdmin/taAdmin' ?>">Add Teaching Assistant</a></li>
    </ul>
    <?php
}
if (isset($_SESSION['tutor'])) {
    ?>
    <p><b>Tutor</b></p>
    <ul>
        <li><a href="<?php echo __SITE_DIR . '/journalAdmin' ?>">Journal Administration</a></li>
    </ul>
    <?php
}
?>

</ul>


