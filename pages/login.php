<?php

use App\Login;
use App\Helper;
use App\Form;
use App\Validation;
use App\User;


if (Login::isLogged(Login::$login_front)) {
	Helper::redirect(Login::$dashboard_front);
}

$objForm = new Form();
$objValid = new Validation($objForm);
$objUser = new User($this->objUrl);


// login form
if ($objForm->isPost('login_email')) {
	if (
		$objUser->isUser(
			$objForm->getPost('login_email'), 
			$objForm->getPost('login_password')
		)
	) {
		Login::loginFront($objUser->id, $this->objUrl->href($this->objUrl->get(Login::$referrer)));
	} else {
		$objValid->add2Errors('login');
	}
}




// registration form
if ($objForm->isPost('first_name')) {
	
	$objValid->expected = array(
		'first_name',
		'last_name',
		'address_1',
		'address_2',
		'town',
		'county',
		'post_code',
		'country',
		'email',
		'password',
		'confirm_password'
	);
	
	$objValid->required = array(
		'first_name',
		'last_name',
		'address_1',
		'town',
		'county',
		'post_code',
		'country',
		'email',
		'password',
		'confirm_password'
	);
	
	$objValid->special = array(
		'email' => 'email'
	);
	
	$objValid->post_remove = array(
		'confirm_password'
	);
	
	$objValid->post_format = array(
		'password' => 'password'
	);
	
	
	// validate password
	$pass_1 = $objForm->getPost('password');
	$pass_2 = $objForm->getPost('confirm_password');
	
	if (!empty($pass_1) && !empty($pass_2) && $pass_1 !== $pass_2) {
		$objValid->add2Errors('password_mismatch');
	}
	
	
	$email = $objForm->getPost('email');
	$user = $objUser->getByEmail($email);

	if (!empty($user)) {
		if ($user['active'] !== 1) {
			$emailInactive  = '<a href="#" id="emailInactive" ';
			$emailInactive .= 'data-id="';
			$emailInactive .= $user['id'];
			$emailInactive .= '">Email address already taken : Resend activation email</a>';
			$objValid->message['email_inactive'] = $emailInactive;
			$objValid->add2Errors('email_inactive');
		} else {
			$objValid->add2Errors('email_duplicate');
		}
	}
	

	if ($objValid->isValid()) {
		
		// add hash for activating account
		$objValid->post['hash'] = mt_rand().date('YmdHis').mt_rand();
		// add registration date
		$objValid->post['date'] = Helper::setDate();


		if ($objUser->addUser($objValid->post, $objForm->getPost('password'))) {
			Helper::redirect($this->objUrl->href('registered'));
		} else {
			Helper::redirect($this->objUrl->href('registered-failed'));
		}
		
	}
}

require_once('header.php');
?>

<h1>Login</h1>

<form action="" method="post">
	
	<table cellpadding="0" cellspacing="0" border="0" class="tbl_insert">
		
		<tr>
			<th>
				<label for="login_email">Login:</label>
			</th>
			<td>
				<?php echo $objValid->validate('login'); ?>
				<input type="text" name="login_email"
					id="login_email" class="fld" value="" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="login_password">Password:</label>
			</th>
			<td>
				<input type="password" name="login_password"
					id="login_password" class="fld" value="" />
			</td>
		</tr>
		
		<tr>
			<th>&#160;</th>
			<td>
				<label for="btn_login" class="fl_l">
					<input type="submit" id="btn_login" 
						class="btn btn-info" value="Login" />
				</label>
			</td>
		</tr>
		
	</table>
	
</form>

<div class="dev br_td">&#160;</div>
<h3>Not registered yet?</h3>

<form action="" method="post">
	
	<table cellpadding="0" cellspacing="0" border="0"
		class="tbl_insert">
	
		<tr>
			<th>
				<label for="first_name">First name: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('first_name'); ?>
				<input type="text" name="first_name" id="first_name" class="fld" 
					value="<?php echo $objForm->stickyText('first_name'); ?>" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="last_name">Last name: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('last_name'); ?>
				<input type="text" name="last_name" id="last_name" class="fld"
					value="<?php echo $objForm->stickyText('last_name'); ?>" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="address_1">Address 1: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('address_1'); ?>
				<input type="text" name="address_1" id="address_1" class="fld" 
					value="<?php echo $objForm->stickyText('address_1'); ?>" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="address_2">Address 2:</label>
			</th>
			<td>
				<?php echo $objValid->validate('address_2'); ?>
				<input type="text" name="address_2" id="address_2" class="fld" 
					value="<?php echo $objForm->stickyText('address_2'); ?>" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="town">Town: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('town'); ?>
				<input type="text" name="town" id="town" class="fld" 
					value="<?php echo $objForm->stickyText('town'); ?>" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="county">County: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('county'); ?>
				<input type="text" name="county" id="county" class="fld" 
					value="<?php echo $objForm->stickyText('county'); ?>" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="post_code">Post code: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('post_code'); ?>
				<input type="text" name="post_code" id="post_code" class="fld" 
					value="<?php echo $objForm->stickyText('post_code'); ?>" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="country">Country: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('country'); ?>
				<?php echo $objForm->getCountriesSelect(230); ?>
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="email">Email address: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('email'); ?>
				<?php echo $objValid->validate('email_duplicate'); ?>
				<?php echo $objValid->validate('email_inactive'); ?>
				<input type="text" name="email" id="email" class="fld" 
					value="<?php echo $objForm->stickyText('email'); ?>" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="password">Password: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('password'); ?>
				<?php echo $objValid->validate('password_mismatch'); ?>
				<input type="password" name="password" id="password" class="fld" 
					value="" />
			</td>
		</tr>
		
		<tr>
			<th>
				<label for="confirm_password">Confirm password: *</label>
			</th>
			<td>
				<?php echo $objValid->validate('confirm_password'); ?>
				<input type="password" name="confirm_password"
					id="confirm_password" class="fld" value="" />
			</td>
		</tr>
		
		<tr>
			<th>&#160;</th>
			<td>
				<label for="btn" class="fl_l">
					<input type="submit" id="btn" 
						class="btn btn-info" value="Register" />
				</label>
			</td>
		</tr>
		
	</table>
	
</form>


<?php require_once('footer.php'); ?>