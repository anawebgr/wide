<?php
/* H  ΣΕΛΙΔΑ ΠΟΥ ΔΕΙΧΝΕΙ ΤΟ ΜΥΝΗΜΑ ΣΟΤΝ ΧΡΗΣΤΗ ΟΤΙ ΓΡΑΦΤΗΚΕ ΚΑΙ ΤΟΥ ΕΧΕΙ ΑΠΟΣΤΑΛΕΙ ΕΜΑΙΛ*/
require_once('../../config.php');

$PAGE->set_url(new moodle_url('/local/registration/verify.php'));
$PAGE->set_context(context_system::instance());
$PAGE->set_title(get_string('verification', 'local_registration'));

echo $OUTPUT->header();
echo $OUTPUT->heading(get_string('verification', 'local_registration'));
echo get_string('verificationinstructions', 'local_registration');
echo $OUTPUT->footer();
