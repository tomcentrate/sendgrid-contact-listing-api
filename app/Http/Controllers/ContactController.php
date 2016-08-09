<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use \SendGrid as Sendgrid;
use Illuminate\Http\Request;
class ContactController extends BaseController
{
    function welcome()
    {

    }

    function register(Request $request)
    {
        $email = trim($request->input('email'));
        if(empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
          $message = json_encode(
              array('success' => true, 'message' => 'invalid email. Please try again')
          );
          return response($message, 400)->header('Content-Type','application/json');
        }

        $apiKey = getenv('SENDGRID_API_KEY');
        $list_id = getenv('SENDGRID_SUBSCRIPTION_LIST_ID');
        $sg = new SendGrid($apiKey);

        $listing = $this->addOrUpdateEmail($email, $sg);
        //TODO: Add conditional to schedule a welcome email.
        // A welcome email should be sent when $listing->new_count != 0
        // and the errors does not exist either.

        $contacts = $listing->persisted_recipients;
        $success = $this->addToSubscriberList($contacts, $list_id, $sg);
        if($success) {
          $message = json_encode(array('success' => true, 'message' => 'Thank you for registering!'));
          $status = 200;
        } else {
          $message = json_encode(array('success' => false, 'message' => 'You have already registered with this email'));
          $status = 400;
        }
        return response($message, $status)->header('Content-Type', 'application/json');
    }

    private function addOrUpdateEmail($email, SendGrid $sg) {
      $request_body = array(array('email' => $email));

      $response = $sg->client->contactdb()->recipients()->post($request_body);
      if($response->statusCode() == 201) {
          $body = json_decode($response->body());
          return $body;
      }
      return array();
    }

    private function addToSubscriberList($contacts_id, $list_id, Sendgrid $sg) {

      $response = $sg->client->contactdb()->lists()->_($list_id)->recipients()->post($contacts_id);
      if($response->statusCode()) {
        return true;
      }
      return false;
    }
}
