<?php

$navbar = file_get_contents('/templates/navigation.html');

$authNavbar = file_get_contents('/templates/authNavigation.html');

$adminUsers = "<table class='uk-table'>
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
    <tbody>{{users}}</tbody>
</table>
<div id='modal'>
	<div id='modal-deleteUser' uk-modal>
		<div class='uk-modal-dialog uk-modal-body'>
			<h2 class='uk-modal-title'>Delete User</h2>
			<p>Are you sure you want to delete this user?</p>
			<p class='uk-text-right'>
				<button class='uk-button uk-button-default uk-modal-close' type='button'>No</button>
				<form action='/admin.php'>
					<input id='deleteUserUID' hidden name='uid' value=''>
					<input class='uk-button uk-button-primary'  type='submit' value='Delete User'>
				</form>
			</p>
		</div>
	</div>
	<div id='modal-makeAdmin' uk-modal>
		<div class='uk-modal-dialog uk-modal-body'>
			<h2 class='uk-modal-title'>Make Admin</h2>
			<p>Are you sure you want to make this user an admin?</p>
			<p class='uk-text-right'>
				<button class='uk-button uk-button-default uk-modal-close' type='button'>No</button>
				<form action='admin.php'>
					<input id='makeUserAdminUID' hidden name='uid' value=''>
					<input class='uk-button uk-button-primary'  type='submit' value='Make Admin'>
				</form>
			</p>
		</div>
	</div>
</div>";

?>