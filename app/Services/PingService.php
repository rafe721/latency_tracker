<?php
/**
 * Created by PhpStorm.
 * User: Rahul
 * Date: 15/12/2018
 * Time: 11:37 AM
 */

namespace App\Services;


class PingService
{

    public function getLatency (Array $hosts) {
        $latency = array();
        foreach ($hosts as $a_host)
        {
            if ($this->is_valid_host_name($a_host)) { // When we are doing a string compare every IP could be a host name.
                $latency[$a_host] = $this->pingExec($a_host);
            } else {
                $latency[$a_host] = null;
            }
        }
        return $latency;
    }

    private function is_valid_host_name($domain_name)
    {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domain_name) //valid chars check
            && preg_match("/^.{1,63}$/", $domain_name)); //overall length check
//            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domain_name)   ); //length of each label
    }

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

    private function pingFsockopen($host_or_ip) {
        $start = microtime(true);
        // fsockopen prints a bunch of errors if a host is unreachable. Hide those
        // irrelevant errors and deal with the results instead.
        $fp = @fsockopen($host_or_ip, 80, $errno, $errstr, 10);
        if (!$fp) {
            $latency = null;
        }
        else {
            $latency = microtime(true) - $start;
            $latency = round($latency * 1000);
        }
        return $latency;
    }

    private function pingExec($host)
    {
        $latency = null;
        $exec_string = $this->getOSPingCommand($host);
        exec($exec_string, $output, $return);
        // Strip empty lines and reorder the indexes from 0 (to make results more
        // uniform across OS versions).
        $commandOutput = implode($output, '');

        $output = array_values(array_filter($output));
        // If the result line in the output is not empty, parse it.
        if (!empty($output[1])) {
            // Search for a 'time' value in the result line.
            $response = preg_match("/time(?:=|<)(?<time>[\.0-9]+)(?:|\s)ms/", $output[1], $matches);
            // If there's a result and it's greater than 0, return the latency.
            if ($response > 0 && isset($matches['time'])) {
                $latency = round($matches['time']);
//                $latency = $matches['time'];
            }
//            $latency = $output[1];
        }
        return $latency;
    }

    private function getOSPingCommand ($host, $ttl = 255, $timeout = 10)
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

}
