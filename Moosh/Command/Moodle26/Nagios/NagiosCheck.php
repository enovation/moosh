<?php
/**
 * moosh - Moodle Shell
 *
 * @copyright  2012 onwards Tomasz Muras
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace Moosh\Command\Moodle26\Nagios;
use Moosh\MooshCommand;

class NagiosCheck extends MooshCommand {
    public function __construct() {
        parent::__construct('check', 'nagios');
    }

    public function bootstrapLevel() {
        return self::$BOOTSTRAP_FULL_NOCLI;
    }

    public function execute() {
        global $CFG;

        if (!function_exists('curl_version')) {
            die("PHP cURL not installed\n");
        }

        $credentials = admin_login();

        $target = $CFG->wwwroot . '/admin/index.php';
        $cookiename = $credentials['cookiename'];
        $cookie = $credentials['cookie'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_COOKIE, sprintf('%s=%s', $cookiename, $cookie));
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $target);
        curl_setopt($ch, CURLOPT_HEADER, true);

        curl_getinfo($ch);
        $result = curl_exec($ch);
        echo $result;
        curl_close($ch);
    }
}
