<?php

$adminOnly = True;
include_once('includes/header.php');

if (!isset($_SESSION['admin'])) {
	$GLOBALS['errors'] .= "<script>UIkit.notification({message: 'This page is admin only', status: 'danger'})</script>";
} else {
	$users = db('getAllUsers');
?>


<table class="uk-table">
    <caption>Users</caption>
    <thead>
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Email</th>
            <th>Created</th>
            <th>emailVerified</th>
            <th>isAdmin</th>
        </tr>
    </thead>
    <tbody>
		<?php foreach ($users as $user): ?>
			<tr>
				<td><?= $user['user_id'] ?></td>
				<td><?= $user['username'] ?></td>
				<td><?= $user['email'] ?></td>
				<td><?= $user['created'] ?></td>
				<td><?= $user['emailVerified'] ?></td>
				<td><?= $user['isAdmin'] ?></td>
			</tr>
		<?php endforeach ?>
    </tbody>
</table>


<?php 
}
include_once('includes/footer.php');

?>