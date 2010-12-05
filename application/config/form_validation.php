<?PHP

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @package Application
 * @subpackage Config
 * @category FormValidation
 */

/**
 * Begin Document
 */

/*
  |--------------------------------------------------------------------------
  | Validation Rules
  |--------------------------------------------------------------------------
  |
  | Validation rules set on a controller/method basis. These rules are loaded automatically
  | by controllers that load the Form_validation.php library. Rules are automitcally applied
  | per defined method when $this->form_validation->run() is called from within that method.
  |
  | @see	http://codeigniter.com/user_guide/libraries/form_validation.html#savingtoconfig
  |
 */
$config = array(
    'GiveawayController/login' => array(
        array(
            'field' => 'login',
            'label' => 'Login ID',
            'rules' => 'required'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'required'
        )
    ),
    'GiveawayController/signup' => array(
        array(
            'field' => 'first_name',
            'label' => 'First Name',
            'rules' => 'trim|required|alpha_dash'
        ),
        array(
            'field' => 'last_name',
            'label' => 'Last Name',
            'rules' => 'trim|required|alpha_dash'
        ),
        array(
            'field' => 'gender',
            'label' => 'Gender',
            'rules' => 'required'
        ),
        array(
            'field' => 'dob_month',
            'label' => 'Birth Month',
            'rules' => 'trim|required|is_natural|max_length[2]'
        ),
        array(
            'field' => 'dob_day',
            'label' => 'Birth Day',
            'rules' => 'trim|required|is_natural|max_length[2]'
        ),
        array(
            'field' => 'dob_year',
            'label' => 'Birth Year',
            'rules' => 'trim|required|is_natural|max_length[4]'
        ),
        array(
            'field' => 'screen_name',
            'label' => 'Screen Name',
            'rules' => 'trim|min_length[4]'
        ),
        array(
            'field' => 'email',
            'label' => 'Email',
            'rules' => 'trim|required|valid_email'
        ),
        array(
            'field' => 'password',
            'label' => 'Password',
            'rules' => 'trim|required|min_length[6]|matches[password_confirm]'
        ),
        array(
            'field' => 'mobile_number',
            'label' => 'Mobile Number',
            'rules' => 'trim|alpha_dash'
        ),
        array(
            'field' => 'country',
            'label' => 'Country',
            'rules' => 'required'
        ),
        array(
            'field' => 'address1',
            'label' => 'Address',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'city',
            'label' => 'City',
            'rules' => 'trim|required|alpha_dash'
        ),
        array(
            'field' => 'state',
            'label' => 'State',
            'rules' => 'required'
        ),
        array(
            'field' => 'zip',
            'label' => 'Zip Code',
            'rules' => 'trim|required|alpha_dash|min_length[5]|max_length[12]'
        ),
        array(
            'field' => 'password_question',
            'label' => 'Security Question',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'password_answer',
            'label' => 'Security Answer',
            'rules' => 'trim|required'
        ),
        array(
            'field' => 'receive_email',
            'label' => 'Receive Email',
            'rules' => ''
        )
    )
);

/* End of file form_validation.php */
/* Location: ./application/config/form_validation.php */