/*global LearnosityAmd*/
LearnosityAmd.define(['jquery-v1.10.2'], function ($) {
    'use strict';

    //function to build up HTML table and add onclick handlers
    function buildHtmlAndInteractions(init, lrnUtils) {
        var $htmlObj = $('<div></div><input id="question_input" type="text" placeholder="e.g. 1" />');
        
        
        //return either as JQuery Element or String of HTML
        return $htmlObj;
    }

    //function to change UI based on correct or incorrect answer status
    function addValidationUI(questionAnswerStatus) {
        var inputField = $('#question_input');
        inputField.removeClass('invalid valid');
        if (questionAnswerStatus == false) {
            inputField.addClass('invalid');
        } else {
            inputField.addClass('valid');
        }
    }


    //build custom question
    function CustomQuestion(init, lrnUtils) {
        
        //create example table and button elements for constructing numberpad.
        var $questionTypeHtml = buildHtmlAndInteractions(init);
        this.$el = init.$el;

        // add Check Answer button
        init.$el.html($questionTypeHtml);
        init.$el.append('<div data-lrn-component="checkAnswer"/>');
        lrnUtils.renderComponent('CheckAnswerButton', this.$el.find('[data-lrn-component="checkAnswer"]').get(0));

        //Reset input field valid/invalid classes on focus
        this.$el.find('#question_input')
            .on('focus', function () {
                $(this).removeClass('invalid valid');
            })
            .on('change', function () {
                init.events.trigger('changed', $(this).val());
            });
        
        //add on validate
        init.events.on('validate', function () {
            init.response = $('#question_input').val();
            // Create scorer
            var scorer = new CustomQuestionScorer(init.question, init.response);
            //check if answer is correct, and pass true or false to the function to update validation UI
            addValidationUI(scorer.isValid());
        });

        //tell "host API" that this question is ready
        init.events.trigger('ready');
    }

    //set question and response
    function CustomQuestionScorer(question, response) {
        this.question = question;
        this.response = response;
    }

    //check if answer is valid
    CustomQuestionScorer.prototype.isValid = function () {
        return $('#question_input').val() === this.question.valid_response;
    };

    //question score
    CustomQuestionScorer.prototype.score = function () {
        return this.isValid() ? this.maxScore() : 0;
    };

    //max score
    CustomQuestionScorer.prototype.maxScore = function () {
        return this.question.score || 1;
    };

    //check if a valid response was set so validation can proceed
    CustomQuestionScorer.prototype.canValidateResponse = function () {
        return !!this.question.valid_response;
    };
    
    //return custom question and scoring hook to "host API"
    return {
        Question: CustomQuestion,
        Scorer: CustomQuestionScorer
    };
});