<?php

require_once('../../config.php');

$id = required_param('id', PARAM_INT);
$category_id = optional_param('category', -1, PARAM_INT);

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);

$str_report = '';
$params = array('id' => $id);
if ($category_id < 0) {
    $str_report = get_string('key61', 'block_sibcms');
} else {
    $str_report = get_string('key21', 'block_sibcms');
    $params['category'] = $category_id;
}

$PAGE->set_url('/blocks/sibcms/report.php', $params);

require_login($course);
$context = context_course::instance($course->id);

if ($category_id < 0) {
    require_capability('block/sibcms:activity_report', $context);
} else {
    require_capability('block/sibcms:monitoring_report', $context);
    if ($category_id > 0) {
        $contextcoursecat = context_coursecat::instance($category_id);
        require_capability('block/sibcms:monitoring_report_category', $contextcoursecat);
    }
}

$PAGE->set_title("$course->shortname: $str_report");
$PAGE->set_heading($course->fullname);
$PAGE->set_pagelayout('incourse');

if ($category_id >= 0) {
    $PAGE->requires->css('/blocks/sibcms/css/styles-plugin.css');
    $PAGE->requires->js_call_amd('block_sibcms/monitoringtable', 'init');
}

echo $OUTPUT->header();
echo $OUTPUT->heading($str_report);

$renderer = $PAGE->get_renderer('block_sibcms');
if ($category_id < 0) {
    echo $renderer->display_activity_report($id);
    $event = \block_sibcms\event\activity_viewed::create(array('context' => $context));
    $event->trigger();
} else {
    echo $renderer->display_monitoring_report($id, $category_id);
    $event = \block_sibcms\event\monitoring_viewed::create(array('context' => $context, 'objectid' => $category_id));
    $event->trigger();
}

echo $OUTPUT->footer();