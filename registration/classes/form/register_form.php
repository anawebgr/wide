<?php

/*George Tompeas WIDE test  Η ΦΟΡΜΑ ΜΑΣ*/

namespace local_registration\form;

defined('MOODLE_INTERNAL') || die();

require_once("$CFG->libdir/formslib.php");

class register_form extends \moodleform {
    public function definition() {
        $mform = $this->_form;

        $mform->addElement('text', 'email', get_string('email'));
        $mform->setType('email', PARAM_EMAIL);
        $mform->addRule('email', null, 'required', null, 'client');
        $mform->addRule('email', null, 'email', null, 'client');

        $mform->addElement('text', 'name', get_string('name', 'local_registration'));
        $mform->setType('name', PARAM_NOTAGS);
        $mform->addRule('name', null, 'required', null, 'client');

        $mform->addElement('text', 'surname', get_string('surname', 'local_registration'));
        $mform->setType('surname', PARAM_NOTAGS);
        $mform->addRule('surname', null, 'required', null, 'client');

        $mform->addElement('text', 'country', get_string('country', 'local_registration'));
        $mform->setType('country', PARAM_NOTAGS);
        $mform->addRule('country', null, 'required', null, 'client');

        $mform->addElement('text', 'mobile', get_string('mobile', 'local_registration'));
        $mform->setType('mobile', PARAM_NOTAGS);
        $mform->addRule('mobile', null, 'required', null, 'client');

        $this->add_action_buttons(false, get_string('register', 'local_registration'));
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        if (!validate_email($data['email'])) {
            $errors['email'] = get_string('invalidemail');
        }

        return $errors;
    }
}