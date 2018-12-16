<?php

namespace App\Services;

/**
 */
class PingService
{
    /** Checks if given string is a valid host name
     *
     * @param array $hosts List of hosts to check for network latency
     *
     * @return array associative array containing elements of @param $hosts as key and the network latency to them as
     *                  the values.
     */
    public function getLatency (Array $hosts) {
        $latency = array();
        foreach ($hosts as $a_host)
        {
            // Check for network latency only for strings that could be valid Host Names. All IPv4 addresses are checked
            // regardless of whether it is valid.
            if ($this->is_valid_host_name($a_host)) { // When we are doing a string compare every IP could be a host name.
                $latency[$a_host] = $this->pingExec($a_host);
            } else {
                $latency[$a_host] = null; // the default 'No response' output
            }
        }
        return $latency;
    }

    /** Checks if given string is a valid host name
     *
     * @param string $host_name
     *
     * @return boolean returns if given string (@param $domain_name) is a valid HostName
     */
    private function is_valid_host_name($host_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $host_name) //valid chars check
            && preg_match("/^.{1,63}$/", $host_name)); //overall length check
    }

    /** Checks if given string is a valid IPv4 Address
     *
     * @param string $ip_string
     *
     * @return boolean returns if given string (@param $ip_string) is a valid IPv4 Address
     */
    private function is_valid_ip($ip_string)
    {
        if (is_string($ip_string)) {
            if ((preg_match("/^(([0-9]{1,3})(\.[0-9]{1,3}){3})$/", $ip_string) //valid chars check
                && preg_match("/^.{1,63}$/", $ip_string))) { //overall length check
                foreach(explode('.', $ip_string) as $a_num) {
                    if ($a_num < 0 || $a_num > 255) {
                        return false;
                    }
                }
                return true;
            }
            return false;
        }
        return false;
    }

    /** Checks if given string is a valid host name
     *
     * @param string $host Host name or IPv4 address to check for network latency
     *
     * @return int | null returns Network latency found when pinging the HostName given by @param $host
     */
    private function pingExec($host)
    {
        $latency = null;
        $exec_string = $this->getOSPingCommand($host);
        exec($exec_string, $output, $return);
        $latency = $this->getLatencyFromPingOutput($output);
        return $latency;
    }

    private function getOSPingCommand_x ($host, $ttl = 255, $timeout = 10)
    {
        $ttl = escapeshellcmd($ttl);
        $timeout = escapeshellcmd($timeout);
        $host = escapeshellcmd($host);
        // Exec string for Windows-based systems.
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // -n = number of pings; -i = ttl; -w = timeout (in milliseconds).
            $exec_string = 'ping -n 1 -i ' . $ttl . ' -w ' . ($timeout * 1000) . ' ' . $host;
        } // Exec string for Darwin based systems (OS X).
        else if (strtoupper(PHP_OS) === 'DARWIN') {
            // -n = numeric output; -c = number of pings; -m = ttl; -t = timeout.
            $exec_string = 'ping -n -c 1 -m ' . $ttl . ' -t ' . $timeout . ' ' . $host;
        } // Exec string for other UNIX-based systems (Linux).
        else {
            // -n = numeric output; -c = number of pings; -t = ttl; -W = timeout
            $exec_string = 'ping -n -c 1 -t ' . $ttl . ' -W ' . $timeout . ' ' . $host . ' 2>&1';
        }
        return $exec_string;
    }

    /** Returns os specific ping command. Currently supports Windows, the *nixes and MacOS.
     * Reference:
     * This is something I would do...
     *
     * @param $host - Array containing output of exec. We will be checking for strings we would expect in a 'ping'
     *                  command line output.
     * @return string
     *          string containing network latency given by the 'ping' command output.
     */
    function getOSPingCommand($host) {
        // We will be using the default ttl and timeout values. Hence...
        $host = escapeshellcmd($host);

        // Exec string for Windows-based systems.
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // -n = number of pings;
            $exec_string = 'ping -n 1 ' . $host;
        } // Exec string for Darwin based systems (OS X).
        else if (strtoupper(PHP_OS) === 'DARWIN') {
            // -n = numeric output; -c = number of pings;
            $exec_string = 'ping -n -c 1 ' . $host;
        } // Exec string for other UNIX-based systems (Linux).
        else {
            // -n = numeric output; -c = number of pings; redirect any errors to stdout
            $exec_string = 'ping -n -c 1 ' . $host . ' 2>&1';
        }
        return $exec_string;
    }

    /** Returns latency from a ping result. This is how I would normally find data in strings programmatically.
     *
     * @param array $output Array containing output of exec. We will be checking for strings we would expect in a 'ping'
     *                  command line output.
     * @return int | null Network latency if present in @param $output.
     */
    private function getLatencyFromPingOutput($output) {
        $latency = null;
        if (is_array($output)) {
            // Strip empty lines and reorder the indexes from 0 (to make results more
            // uniform across OS versions).
            $output = array_values(array_filter($output));
            if (isset($output[1]) && strpos($output[1], 'Request time') === false) {
                // keep reusing the same variable...
                $output = explode(" ", $output[1]);
                foreach ($output as $fragment) {
                    // We only need the time name value pair...
                    if (strpos($fragment, "time") !== false) {
                        // remove "time" and "ms from the fragment"
                        $output_str = strtr($fragment, array(
                            "time" => "",
                            "ms" => "",
                        ));
                        // If the remaining string contains "<" as first character or "0" as its second character, return
                        if ($output_str[0] === "<" || $output_str[1] === "0") {
                            $latency = 0;
                        } else {
                            // otherwise return substring from second to last character...
                            $latency = round(mb_substr($output_str, 1, strlen($output_str), 'UTF-8'));
                        }
                    }
                }
            }
        }
        return $latency;
    }

    /** Returns latency from a ping result. I have known we could use regex this way. Great to see it in action. Sticking
     * with my own method for this exercise.
     *
     * @param array $output Array containing output of exec. We will be checking for strings we would expect in a 'ping'
     *                  command line output.
     * @return int | null Network latency if present in @param $output.
     */
    private function getLatencyUsingRegex($output) {
        $latency = null;
        $output = array_values(array_filter($output));
        if (!empty($output[1])) {
            // Search for a 'time' value in the result line.
            $response = preg_match("/time(?<sign>[=<])(?<time>[\.0-9]+)(?:|\s)ms/", $output[1], $matches);
            // If there's a result and it's greater than 0, return the latency.
            if ($response > 0 && isset($matches['time'])) {
                // Treat all latencies (response times) less that 1ms as 0 (Zero).
                if (isset($matches['sign']) && $matches['sign'] == "<") { // Assuming 'sign' => '<' is set only when latency is less than 1 ms
                    $latency = 0;
                } else {
                    $latency = round($matches['time']);
                }
            }
        }
        return $latency;
    } // This method is just soo much better...

}
