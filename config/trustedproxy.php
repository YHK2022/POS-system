<?php

return [

    /*
     * Set trusted proxy IP addresses.
     *
     * Both IPv4 and IPv6 addresses are
     * supported, along with CIDR notation.
     *
     * The "*" character is syntactic sugar
     * within TrustedProxy to trust any proxy
     * that connects directly to your server,
     * a requirement when you cannot know the
     * address of your proxy (e.g. if using ELB
     * or similar).
     */

    'proxies' => env('TRUSTED_PROXIES', null), // [<ip addresses>,], '*'

    /*
     * To trust one or more specific proxies that connect
     * directly to your server, use an array of IP addresses:
     */
    // 'proxies' => ['192.168.1.1', '10.0.0.1'],

    /*
     * Or, to trust all proxies that connect
     * directly to your server, use a "*"
     */
    // 'proxies' => '*',

    /*
     * Or, to trust all proxies that connect
     * directly to your server, along with
     * any intermediate proxies that a trusted
     * proxy connects to, use a "**"
     */
    // 'proxies' => '**',

    /*
     * Default Header Names
     *
     * Change these if the proxy does not send the default header names.
     *
     * Note, that Laravel's default TrustedProxy
     * middleware will overwrite these settings
     * using the following environment variables:
     *
     * X_FORWARDED_FOR_HEADER and
     * X_FORWARDED_HOST_HEADER and
     * X_FORWARDED_PORT_HEADER and
     * X_FORWARDED_PROTO_HEADER
     *
     * These may be set at your web server as well.
     */

    'headers' => [
        Illuminate\Http\Request::HEADER_FORWARDED    => 'FORWARDED',
        Illuminate\Http\Request::HEADER_X_FORWARDED_FOR  => 'X_FORWARDED_FOR',
        Illuminate\Http\Request::HEADER_X_FORWARDED_HOST => 'X_FORWARDED_HOST',
        Illuminate\Http\Request::HEADER_X_FORWARDED_PORT => 'X_FORWARDED_PORT',
        Illuminate\Http\Request::HEADER_X_FORWARDED_PROTO => 'X_FORWARDED_PROTO',
    ],

];
