<?php
namespace Wapi;

use \Exception as NativeException;

class Exception extends NativeException {}

class ConnectionException extends Exception {}