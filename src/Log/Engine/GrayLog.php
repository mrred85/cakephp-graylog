<?php
/**
 * Graylog stream for Logging
 *
 * @link https://github.com/mrred85/cakephp-graylog
 * @copyright 2016 - present Victor Rosu. All rights reserved.
 * @license Licensed under the MIT License.
 */

namespace App\Log\Engine;

use Cake\Core\Configure;
use Cake\Log\Engine\BaseLog;

class GrayLog extends BaseLog
{
    /**
     * Used to map the string names back to their LOG_* constants
     *
     * @var array
     */
    protected $_levelMap = [
        'emergency' => LOG_EMERG,
        'alert' => LOG_ALERT,
        'critical' => LOG_CRIT,
        'error' => LOG_ERR,
        'warning' => LOG_WARNING,
        'notice' => LOG_NOTICE,
        'info' => LOG_INFO,
        'debug' => LOG_DEBUG,
    ];

    /**
     * Writes a message to GrayLog
     *
     * Map the $level back to a LOG_ constant value, split multi-line messages into multiple
     * log messages, pass all messages through the format defined in the configuration
     *
     * @param string $level The severity level of log you are making.
     * @param string $message The message you want to log.
     * @param array $context Additional information about the logged message
     * @return bool success of write.
     */
    public function log($level, $message, array $context = [])
    {
        $config = Configure::read('GrayLog');

        if ($config) {
            $httpHost = env('HTTP_HOST');
            $message = date('Y-m-d H:i:s') . ': ' . $message;

            $sendInfo = json_encode([
                'version' => $config['version'],
                'host' => isset($httpHost) ? $httpHost : parse_url(Configure::read('App.fullBaseUrl'), PHP_URL_HOST),
                'level' => $this->_levelMap[$level],
                'short_message' => explode("\n", $message)[0],
                'full_message' => $this->_format($message, $context),
            ]);

            $sock = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
            $result = socket_sendto($sock, $sendInfo, strlen($sendInfo), 0, $config['host'], $config['port']);
            socket_close($sock);

            return $result;
        }

        return false;
    }
}
