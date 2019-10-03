<?php
require '../libs/vendor/autoload.php';
require_once '../include/DbOperations.php';

$app = new Slim\App();

$message = array();

/**
 * Testing purpose endpoint
 * endpoint - /hello/{name}
 * method - POST
 * arguments - name
 * result - displays name with suffix hello
 */
$app->post('/hello/{name}', function($request, $response, $args) {
    return $response->write("Hello, " . $args["name"]);
});

/* ------------ implement your api endpoints here -------------- */

/* -------------------- HELPER FUNCTIONS ---------------------------- */
function hasRequiredParams($required_params, $response) {
    $error = false;
    $error_params = "";
    $request_params = $_REQUEST;

    foreach ($required_params as $param) {
        if (!isset($request_params[$param]) || strlen(trim($request_params[$param])) <= 0) {
            $error = true;
            $error_params .= $param . ", ";
        }
    }

    if ($error) {
        $message = array();
        $message['error'] = true;
        $message['message'] = "Required param(s) " . substr($error_params, 0, -2) . " is/are missing.";
        buildResponse(400, $message, $response);
        return false;
    } else {
        return true;
    }
}

function buildMessage($error, $msg) {
    $message['error'] = $error;
    $message['message'] = $msg;
}

function buildResponse($status_code, $message, $response) {
    $response->withHeader('Content-type', 'application/json');
    $response->withStatus($status_code);
    $response->write(json_encode($message));
}

/**
 * Validating email address
 * @param String $email User email address
 * @return boolean
 */
function isValidEmail($email, $response) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message["error"] = true;
        $message["message"] = "Email address is not valid";
        buildResponse(400, $message, $response);
        return false;
    } else {
        return true;
    }
}
/* -------------------- END HELPER FUNCTIONS ---------------------------- */

$app->run();
?>