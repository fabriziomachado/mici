<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Config
 * @category Auth
 * @author Peter Schmalfeldt <Peter@ManifestInteractive.com>
 */

/**
 * Begin Document
 */

/**
 * Load INI File
 *
 * Fetch Configuration for Framework INI File
 */
$framework_ini = (array) unserialize(FRAMEWORK_INI);

/**
 * Default Member Role
 *
 * Default role of a member who registers on the site.
 *
 * Options are: admin, developer, editor, moderator, super_admin, user
 */
$config['default_role'] = 'user';

/**
 * Website details
 *
 * These details are used in emails sent by authentication library.
 */
$config['website_name'] = $framework_ini['auth']['website_name'];
$config['webmaster_email'] = $framework_ini['auth']['webmaster_email'];

/**
 * Registration settings
 *
 * 'allow_registration' = Registration is enabled or not
 * 'captcha_registration' = Registration uses CAPTCHA
 * 'email_activation' = Requires user to activate their account using email after registration.
 * 'email_activation_expire' = Time before users who don't activate their account getting deleted from database. Default is 48 hours (60*60*24*2).
 * 'email_account_details' = Email with account details is sent after registration (only when 'email_activation' is FALSE).
 * 'use_username' = Username is required or not.
 *
 * 'username_min_length' = Min length of user's username.
 * 'username_max_length' = Max length of user's username.
 * 'password_min_length' = Min length of user's password.
 * 'password_max_length' = Max length of user's password.
 */
$config['allow_registration'] = $framework_ini['auth']['allow_registration'];
$config['captcha_registration'] = $framework_ini['auth']['captcha_registration'];
$config['email_activation'] = $framework_ini['auth']['email_activation'];
$config['email_activation_expire'] = $framework_ini['auth']['email_activation_expire'];
$config['email_account_details'] = $framework_ini['auth']['email_account_details'];
$config['use_username'] = $framework_ini['auth']['use_username'];

$config['username_min_length'] = 4;
$config['username_max_length'] = 20;
$config['password_min_length'] = 4;
$config['password_max_length'] = 20;

/**
 * Login settings
 *
 * 'login_by_username' = Username can be used to login.
 * 'login_by_email' = Email can be used to login.
 * You have to set at least one of 2 settings above to TRUE.
 * 'login_by_username' makes sense only when 'use_username' is TRUE.
 *
 * 'login_record_ip' = Save in database user IP address on user login.
 * 'login_record_time' = Save in database current time on user login.
 *
 * 'login_count_attempts' = Count failed login attempts.
 * 'login_max_attempts' = Number of failed login attempts before CAPTCHA will be shown.
 * 'login_attempt_expire' = Time to live for every attempt to login. Default is 24 hours (60*60*24).
 */
$config['login_by_username'] = TRUE;
$config['login_by_email'] = TRUE;
$config['login_record_ip'] = TRUE;
$config['login_record_time'] = TRUE;
$config['login_count_attempts'] = TRUE;
$config['login_max_attempts'] = 5;
$config['login_attempt_expire'] = 86400;

/**
 * Auto login settings
 *
 * 'autologin_cookie_name' = Auto login cookie name.
 * 'autologin_cookie_life' = Auto login cookie life before expired. Default is 2 months (60*60*24*31*2).
 */
$config['autologin_cookie_name'] = $framework_ini['auth']['autologin_cookie_name'];
$config['autologin_cookie_life'] = $framework_ini['auth']['autologin_cookie_life'];

/**
 * Forgot password settings
 *
 * 'forgot_password_expire' = Time before forgot password key become invalid. Default is 15 minutes (60*15).
 */
$config['forgot_password_expire'] = 900;

/**
 * Captcha
 *
 * You can set captcha that created by Auth library in here.
 * 'captcha_path' = Directory where the catpcha will be created.
 * 'captcha_fonts_path' = Font in this directory will be used when creating captcha.
 * 'captcha_font_size' = Font size when writing text to captcha. Leave blank for random font size.
 * 'captcha_grid' = Show grid in created captcha.
 * 'captcha_expire' = Life time of created captcha before expired, default is 3 minutes (180 seconds).
 * 'captcha_case_sensitive' = Captcha case sensitive or not.
 */
$config['captcha_path'] = $framework_ini['auth']['captcha_path'];
$config['captcha_fonts_path'] = $framework_ini['auth']['captcha_fonts_path'];
$config['captcha_width'] = $framework_ini['auth']['captcha_width'];
$config['captcha_height'] = $framework_ini['auth']['captcha_height'];
$config['captcha_font_size'] = $framework_ini['auth']['captcha_font_size'];
$config['captcha_grid'] = $framework_ini['auth']['captcha_grid'];
$config['captcha_expire'] = $framework_ini['auth']['captcha_expire'];
$config['captcha_case_sensitive'] = $framework_ini['auth']['captcha_case_sensitive'];

/**
 * reCAPTCHA
 *
 * 'use_recaptcha' = Use reCAPTCHA instead of common captcha
 * You can get reCAPTCHA keys by registering at http://recaptcha.net
 */
$config['use_recaptcha'] = $framework_ini['auth']['use_recaptcha'];
$config['recaptcha_public_key'] = $framework_ini['auth']['recaptcha_public_key'];
$config['recaptcha_private_key'] = $framework_ini['auth']['recaptcha_private_key'];
$config['recaptcha_theme'] = $framework_ini['auth']['recaptcha_theme'];

/* End of file auth.php */
/* Location: ./application/config/auth.php */