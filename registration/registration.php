<?php
/*George Tompeas WIDE test  ΔΙΑΧΕΙΡΙΣΗ ΔΕΔΟΜΕΝΩΝ ΑΠΟ ΤΗΝ ΦΟΡΜΑ ΚΑΙ ΔΗΜΙΟΥΡΓΕΙΑ ΧΡΗΣΤΗ
 ΤΟΝ ΔΗΜΙΟΥΡΓΩ ΚΑΙ ΣΤΗΝ ΣΥΝΕΧΕΙΑ ΚΑΝΩ UPDATE ΤΑ ΥΠΟΛΟΙΠΑ ΠΕΔΙΑ
 ΠΡΟΣΧΟΗ ΠΕΡΝΑΩ ΤΟ EMAIL ΣΑΝ USERNAME
*/
require_once '../../config.php';
require_once 'classes/form/register_form.php';
require_once $CFG->libdir . '/moodlelib.php';
require_once $CFG->dirroot . '/user/lib.php';

@error_reporting(E_ALL | E_STRICT);
@ini_set('display_errors', '1');

$CFG->debugdisplay = 1;
$CFG->debugdatabase = true;

$PAGE->set_url(new moodle_url('/local/registration/registration.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('register', 'local_registration'));

$mform = new \local_registration\form\register_form();

if ($mform->is_cancelled()) {
    redirect(new moodle_url('/'));
} else if ($data = $mform->get_data()) {
    // Generate a temporary password
    $temp_password = generate_password(8);

    // Create a new user record in the Moodle user table
    $user = new stdClass();
    $user->username = $data->email; //email as username
    $user->password = hash_internal_user_password($temp_password);
    $user->firstname = $data->name;
    $user->lastname = $data->surname;
    $user->email = $data->email;
    $user->city = $data->country;
    $user->phone1 = $data->mobile;
    $user->confirmed = 0;
    $user->auth = 'manual';
    $user->mnethostid = $CFG->mnet_localhost_id;
    $user->lang = $CFG->lang;
    $user->timecreated = time();
    $user->timemodified = time();
    $user->policyagreed = 0;
    $user->deleted = 0;
    $user->suspended = 0;
    $user->idnumber = '';
    $user->emailstop = 0;
    $user->phone2 = '';
    $user->institution = '';
    $user->department = '';
    $user->address = '';
    $user->country = '';
    $user->calendartype = 'gregorian';
    $user->theme = '';
    $user->timezone = '99';
    $user->firstaccess = 0;
    $user->lastaccess = 0;
    $user->lastlogin = 0;
    $user->currentlogin = 0;
    $user->lastip = '';
    $user->secret = '';
    $user->picture = 0;

    $user->descriptionformat = 1;
    $user->mailformat = 1;
    $user->maildigest = 0;
    $user->maildisplay = 2;
    $user->autosubscribe = 1;
    $user->trackforums = 0;
    $user->trustbitmask = 0;

    // Create the user record and get the user ID
    $newuser = create_user_record($user->username, $temp_password);
    $user->id = $newuser->id;

    // Update the user record with the rest of the data
    try {
        $DB->update_record('user', $user);

        // Debugging: Check if the record was updated
        $updated_user = $DB->get_record('user', ['id' => $user->id]);

        // Send verification email
        $a = new stdClass();
        $a->name = fullname($user);
        $a->temp_password = $temp_password;
        $subject = get_string('verificationemailsubject', 'local_registration');
        $body = get_string('verificationemailbody', 'local_registration', $a);
        email_to_user($user, get_admin(), $subject, $body);

        // Redirect to a verification page
        redirect(new moodle_url('/local/registration/verify.php'));

    } catch (Exception $e) {
        echo "Error updating user: " . $e->getMessage();
    }

}

echo $OUTPUT->header();
$mform->display();
echo $OUTPUT->footer();
