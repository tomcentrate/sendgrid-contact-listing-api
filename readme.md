Sendgrid Contact Subscribe
==========================

This is built as a backend to supplement the Sendgrid Contacts API.

Sendgrid's changes to the Marketing Features does not include a built-in widget
that we can add to the site. The basic use-case covers a basic subscription widget, for a single contact list.

Built on-top Laravel's Lumen.

Expected Use Case
-----------------

1. You have a subscription widget on your website that contains a single field for an email address. This form submit's to this project's root url.
The user enters in an email and Submit's the form.

2. This would register the user with that email to a subscription list.


Setup
-----

    # setup your server
    vagrant up
    vagrant ssh

    # install dependencies
    composer install

    # copy .env.example to .env
    cp .env.example .env



Deployment
----------

Change the following values in your .env file:

    SENDGRID_API_KEY=ADD_YOUR_SENDGRID_API_KEY
    SENDGRID_SUBSCRIPTION_LIST_ID=ADD_YOUR_SUBSCRIPTION_LIST_ID

Point your Server to serve the website from the `public/` folder.



To Change URL Submission path
-----------------------------

edit the routes.php and enter the path.