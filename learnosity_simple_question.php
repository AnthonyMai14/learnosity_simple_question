<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <?php
            //load SDK
            include_once $_SERVER["DOCUMENT_ROOT"] . '/helpers/src/sdk/src/LearnositySdk/autoload.php';

            use LearnositySdk\Request\Init;
            use LearnositySdk\Utils\Uuid;

            //SDK generates a v4 UUID used for a unique session ID
            $session_id = Uuid::generate();

            //customer credentials
            $consumer_key = 'yis0TYCu7U9V4o7M';
            $consumer_secret = '74c5fd430cf1242a527f6223aebd42d30464be22';

            $security = [
                //student user id
                'user_id' => 'demo_student',
                //whitelisted domain. must be 'localhost' for this demo
                'domain' => $_SERVER['SERVER_NAME'],
                'consumer_key' => $consumer_key,
                'timestamp' => gmdate('Ymd-Hi')
            ];
            
            //the Questions API params
            $init = new Init('questions', $security, $consumer_secret, [
                'id' => 'open_web_demo',
                'name' => 'Open Web Demo',
                'type' => 'local_practice',
                'state' => 'initial',
                'session_id' => $session_id
            ]);

        ?>
    </head>
    <body>
        <div class="container container-content container-fluid" id="main-container">
            <div class="row">
                <div class="section">
                    <img src="helpers/images/learnosity_logo.jpg" id="main-logo"/><br/>
                    <h2 class="page-heading">Learnosity Technical Test - Anthony Mai</h2>

                    <!--Insert Learnosity API-->
                    <div class="question-container">
                        <span class="learnosity-response question-custom-simple-response-1"></span>
                    </div>
                </div>
            </div>
        </div>
        <!--bootstrap and stock Learnosity styles-->
        <link rel="stylesheet" href="helpers/src/static/vendor/css/bootstrap.min.css">
        <link rel="stylesheet" href="helpers/css/style.css">
        <!--jQuery-->
        <script src="helpers/src/static/vendor/js/jquery-1.11.0.min.js"></script>

        <!--Learnosity API-->
        <script src="//questions.learnosity.com"></script>
        <script>
            
            /*
            assessment JSON
            Unlike our main assessment API, the Items API, the Questions API doesn't require
            the assessment content to be part of the request signature in the PHP.
             */

            /*
            response_id = required for API/scoring access to each question
            type = question type
            js/css = question type components. full path (protocol optional) required
            stimulus, valid_response, score = question params
            instant_feedback = check answer button for easy local testing
             */
            var customQuestionJson = {
                "response_id": "custom-simple-response-1",
                "type": "custom",
                "js": "//<?php echo $_SERVER['HTTP_HOST'] ?>/learnosity_simple_question.js",
                "css": "//<?php echo $_SERVER['HTTP_HOST'] ?>/learnosity_simple_question.css",
                "stimulus": "<p>What is the shortest number of steps needed to get 4 GALLON of water using only one (1) 3 GALLON JUG and one (1) 5 GALLON JUG?</p>",
                "valid_response": "6",
                "score": 1,
                "instant_feedback": true
            };

            //pull request JSON from server-signed request (PHP above) using SDK
            var activity = <?php echo $init->generate(); ?>;

            //add assessment content
            activity.questions = [customQuestionJson];

            //init questions API (LearnosityApp) with request JSON
            var questionsApp = window.questionsApp = LearnosityApp.init(activity);
        </script>
    </body>
</html>